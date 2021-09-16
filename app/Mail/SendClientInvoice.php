<?php

namespace App\Mail;

use App\Models\Assignment;
use App\Models\ClientInvoices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendClientInvoice extends Mailable
{
    use Queueable, SerializesModels;


    public $invoice;
    public $assignment;

    public function __construct( ClientInvoices $invoice, Assignment $assignment)
    {
        $this->invoice = $invoice;
        $this->assignment = $assignment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $clientInvoice  =  ClientInvoices::with(['client','lines.expense'])->findorfail( $this->invoice->client_invoice_id);
        $assignment = Assignment::findorfail($this->assignment->assignment_id);

        $InvoicePdf = PDF::loadView('assignment.clientInvoicePdf', ['clientInvoice' => $clientInvoice, 'assignment' => $assignment]);

        $uploadFilePath = storage_path('app/public/clientInvoice/' . $this->invoice->client_invoice_id);

        if (!file_exists($uploadFilePath)) {
            mkdir($uploadFilePath, 0777, true);
        }

        if( file_exists(storage_path('app/public/clientInvoice/'.$this->invoice->client_invoice_id.'/invoice_'.$this->invoice->invoice_number.'.pdf'))) {
            unlink(storage_path('app/public/clientInvoice/'.$this->invoice->client_invoice_id.'/invoice_'.$this->invoice->invoice_number.'.pdf'));
        }

        $InvoicePdf->save(storage_path('app/public/clientInvoice/'.$this->invoice->client_invoice_id.'/invoice_'.$this->invoice->invoice_number.'.pdf'));

        return $this->view('invoice.clientEmail')
            ->subject('Invoice from InPlaceAuction ( ID:'.$this->invoice->invoice_number.' )')
            ->with(['invoice' => $clientInvoice])
            ->attach(storage_path('app/public/clientInvoice/'.$this->invoice->client_invoice_id.'/invoice_'.$this->invoice->invoice_number.'.pdf'));

    }
}
