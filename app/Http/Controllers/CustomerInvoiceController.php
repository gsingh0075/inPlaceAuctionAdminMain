<?php

namespace App\Http\Controllers;

use App\Mail\SendCustomerInvoice;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerInvoiceController extends Controller
{

    /* View PDF */
    public function viewCustomerInvoice($InvoiceId){

        //Log::info($assignmentId);
        $invoiceAuthorization = Invoice::with(['customer', 'items.item.bids'])->findorfail($InvoiceId);

        $authorizationPdf = PDF::loadView('invoice.invoiceAuthPdf', ['authorization' => $invoiceAuthorization]);
        return $authorizationPdf->download('IPA_customer_invoice#'.$invoiceAuthorization->invoice_auth_id.'.pdf');

    }

    /* Send Invoice */
    public function sendInvoice($InvoiceId){

        $invoice = Invoice::with(['customer'])->findorfail($InvoiceId);

        if(isset($invoice->send_to_email) && !empty($invoice->send_to_email)) {

            if($invoice->customer->invoice_email) {
                Mail::to($invoice->send_to_email)->cc(['arizzo@inplaceauction.com','ecastagna@inplaceauction.com'])->send(new SendCustomerInvoice($invoice));
            } else {
                Mail::to('ecastagna@inplaceauction.com')->cc(['arizzo@inplaceauction.com'])->send(new SendCustomerInvoice($invoice));
            }

            $invoice->update(['sent_date' => Carbon::now()->format('Y-m-d H:i:s'), 'email_sent' => 1]);
            return response(['status' => true, 'message' => array('Email sent successfully')], 200);

        } else {
            return response(['status' => false, 'message' => array('Missing the request by email address')], 200);
        }

    }

}
