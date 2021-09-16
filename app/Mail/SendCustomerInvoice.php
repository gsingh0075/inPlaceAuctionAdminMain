<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCustomerInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invoiceData = Invoice::with(['customer', 'items.item'])->findorfail($this->invoice->invoice_auth_id);
        $authorizationPdf = PDF::loadView('invoice.invoiceAuthPdf', ['authorization' => $invoiceData]);

        $uploadFilePath = storage_path('app/public/invoice/' . $this->invoice->invoice_auth_id);

        if (!file_exists($uploadFilePath)) {
            mkdir($uploadFilePath, 0777, true);
        }

        if( file_exists(storage_path('app/public/invoice/'.$this->invoice->invoice_auth_id.'/invoice_'.$this->invoice->invoice_auth_id.'.pdf'))) {
            unlink(storage_path('app/public/invoice/'.$this->invoice->invoice_auth_id.'/invoice_'.$this->invoice->invoice_auth_id.'.pdf'));
        }

        $authorizationPdf->save(storage_path('app/public/invoice/'.$this->invoice->invoice_auth_id.'/invoice_'.$this->invoice->invoice_auth_id.'.pdf'));

        return $this->view('invoice.email')
            ->subject('Invoice from InPlaceAuction ( ID:'.$this->invoice->invoice_number.' )')
            ->with(['invoice' => $invoiceData])
            ->attach(storage_path('app/public/invoice/'.$this->invoice->invoice_auth_id.'/invoice_'.$this->invoice->invoice_auth_id.'.pdf'));

    }
}
