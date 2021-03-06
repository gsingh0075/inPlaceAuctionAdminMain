<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\ClientInvoices;
use App\Models\ClientRemittance;
use App\Models\Clients;
use App\Models\Invoice;
use App\Models\InvoiceHasItems;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AccountingController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // Return the view for Client Invoices
    public function getClientInvoices(){

        $clientInvoices = ClientInvoices::with(['client', 'lines.expense.item.assignment'])
                          ->whereDate('generated_dt','>=', '2015-08-01')
                          //->orderBy('generated_dt','asc')
                          ->get();
        //Log::info($clientInvoices);
        return view('accounting.clientInvoicesList', ['clientInvoices' => $clientInvoices ]);
    }

    // Return the view for Client Invoices
    public function getClientReceivables(){

        $clientInvoices = ClientInvoices::with(['client', 'lines.expense.item'])
                          ->where('sent', '=', 1)
                          ->where( function($q) {
                               $q->where('paid', '!=', 1);
                               $q->orWhereNull('paid');
                           })->get();

        $totalPendingAmount = 0;
        $totalWriteOffAmount = 0;
        if(isset($clientInvoices) && !empty($clientInvoices)){
            foreach($clientInvoices as $invoice){
                if(!$invoice->write_off) {
                    $totalPendingAmount += $invoice->invoice_amount;
                } else {
                    $totalWriteOffAmount += $invoice->invoice_amount;
                }
            }
        }

        return view('accounting.clientReceivableList',  ['clientInvoices' => $clientInvoices, 'totalPendingAmount' => $totalPendingAmount, 'totalWriteOffAmount' => $totalWriteOffAmount ]);
    }

    //Update the Client Invoice
    public function updateClientReceivables( Request $request){

        $validator = \Validator::make(
            array(
                'client_invoice_id' => $request->input('client_invoice_id'),
            ),
            array(
                'client_invoice_id' => 'required|int',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $validFields = ['write_off'];
                $clientInvoice = ClientInvoices::findorfail($request->input('client_invoice_id'));
                $updateModel = false;

                foreach($validFields as $field){
                    $value = $request->input($field);
                    if(isset($value)){
                        $updateModel = true;
                        $clientInvoice->$field = $value;
                    }
                }

                if($updateModel){
                    $clientInvoice->save();
                }

                return response(['status' => true, 'message' => array('Invoice updated successfully')], 200);

            } catch (Exception $e) {

                if ($e instanceof ModelNotFoundException) {
                    Log::error('Model Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'No Entry matched for Model ' . str_replace('App\v2\\', '', $e->getModel()), 'value' => $e->getIds())], 400);

                } elseif ($e instanceof QueryException) {
                    Log::error('Query Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'Data save exception. Please contact administrator')], 500);

                } else {
                    Log::error('Unknown Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'Something went wrong')], 500);

                }
            }

        }

    }

    // Return the view for Customer Invoices
    public function getCustomerInvoices(){

        $customerInvoices = Invoice::with(['customer','items.item'])
                            ->whereDate('create_dt','>=', '2015-07-15')
                            ->get();
        //Log::info($customerInvoices);
        return view('accounting.customerInvoicesList',['customerInvoices' => $customerInvoices]);
    }

    // Delete Customer Invoice
    public function deleteCustomerInvoice(  Request $request){

        $validator = \Validator::make(
            array(
                'invoice_id'  => $request->input('invoice_id')
            ),
            array(
                'invoice_id'  => 'required'
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try{

                $invoice_id = $request->input('invoice_id');

                $customerInvoice = Invoice::with(['items'])->findOrFail($invoice_id);

                $itemsArr = [];
                if(isset($customerInvoice->items) && !empty($customerInvoice->items)){
                    foreach($customerInvoice->items as $item){
                            array_push($itemsArr,$item->item_id);
                    }
                }
                //print_r($itemsArr); exit;
                if(!empty($itemsArr)) Bid::whereIn('ITEM_ID',$itemsArr)->update(['BID_ACCEPTED'=>0]);
                InvoiceHasItems::where('invoice_auth_id',$invoice_id)->delete();
                $customerInvoice->delete();

                return response( ['status' => true,  'message' => 'Invoice is deleted successfully'] , 200);



            }catch (Exception $e) {

                if ($e instanceof ModelNotFoundException) {
                    Log::error('Model Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'No Entry matched for Model ' . str_replace('App\v2\\', '', $e->getModel()), 'value' => $e->getIds())], 400);

                } elseif ($e instanceof QueryException) {
                    Log::error('Query Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'Data save exception. Please contact administrator')], 500);

                } else {
                    Log::error('Unknown Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'Something went wrong')], 500);

                }
            }

        }

    }

    // Return the view for Customer Receivables
    public function getCustomerReceivables(){

        $customerInvoices = Invoice::with(['customer','items.item'])
                            ->where('email_sent', '=', 1)
                            ->where( function($q) {
                                $q->where('paid', '!=', 1);
                                $q->orWhereNull('paid');
                            })
                            ->whereDate('create_dt','>=', '2015-07-15')
                            ->get();

        $totalPendingAmount = 0;

        if(isset($customerInvoices) && !empty($customerInvoices)){
            foreach( $customerInvoices as $customerInvoice ){
                $totalPendingAmount += $customerInvoice->invoice_amount;
            }
        }

        return view('accounting.customerReceivableList', ['customerInvoices' => $customerInvoices, 'totalPendingAmount' => $totalPendingAmount]);
    }

    // Return the view for Client Remittance
    public function getClientRemittance(){

        $allClientRemittance = ClientRemittance::pluck('invoice_auth_id')->all();

        $clientRemittance = Invoice::with(['customer','items.item.expense.expenseAuth.invoice','items.item.assignment.client.clientInfo'])
                            ->where('invoice_number','>=','150137')
                            //->whereNotNull(['sent_date','paid'])
                            ->whereNotNull(['paid'])
                           //->where('generated','=',1)
                            ->whereNotIn('invoice_auth_id',$allClientRemittance)->get();

        //Log::info($clientRemittance);
        return view('accounting.clientRemittanceList', ['clientRemittance' => $clientRemittance]);
    }

    // Get Client Receivable Report.
    public function getClientReceivableReport(){

        $clientInvoices = Clients::with(['invoices'])->has('invoices')->orderBy('COMPANY','asc')->get();
        //Log::info($clientInvoices);
        return view('accounting.clientReceivableReport', ['clientInvoices' => $clientInvoices]);

    }

    // Get Client Remittance Report.
    public function getClientRemittanceReport(){

        $clientRemittance = ClientRemittance::with(['invoice.items.item.expense','client'])
                            ->whereDate('GENERATED_DATE','>=', '2015-07-01')
                            //->orderBy('CLIENT_REMITTANCE_NUMBER', 'desc')
                            ->get();
        //Log::info($clientRemittance);

        return view('accounting.clientRemittanceReport', ['clientRemittance' => $clientRemittance]);
    }

    // Return Get Info for specific Auth
    public function getClientRemittanceDetails(Request $request){

        $validator = \Validator::make(
            array(
                'invoice_auth_id'  => $request->input('invoice_auth_id'),


            ),
            array(
                'invoice_auth_id'  => 'required|int',
            )
        );
        if ($validator->fails()) {

            return response(['status' => false, 'errors' => $validator->messages()], 200);

        } else {

            try {

                $invoice_auth_id = $request->get('invoice_auth_id');
                $clientRemittance = Invoice::with(['customer','items.item.expense.expenseAuth.invoice','items.item.assignment.client.clientInfo'])->findOrFail($invoice_auth_id);

                return response()->json(['status' => true, 'html' => View('accounting.unPaidExpense', compact('clientRemittance'))->render()]);

            } catch (Exception $e) {

                if ($e instanceof ModelNotFoundException) {
                    Log::error('Model Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'No Entry matched for Model ' . str_replace('App\v2\\', '', $e->getModel()), 'value' => $e->getIds())], 400);

                } elseif ($e instanceof QueryException) {
                    Log::error('Query Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'Data save exception. Please contact administrator')], 500);

                } else {
                    Log::error('Unknown Exception : ' . $e->getMessage());
                    return response(['status' => false, 'errors' => array('error' => 'Something went wrong')], 500);

                }
            }


        }

    }



}
