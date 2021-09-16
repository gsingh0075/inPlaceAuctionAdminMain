<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\ClientInvoices;
use App\Models\ClientRemittance;
use App\Models\Clients;
use App\Models\CustomerInvoice;
use App\Models\Fmv;
use App\Models\Invoice;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /* Loads the Home Page */
    public function index()
    {
        //Lets get Client invoice Data as well.
        $clientInvoices = ClientInvoices::get();
        $sentInvoiceAmount = 0;
        $paidInvoiceAmount = 0;
        if(isset($clientInvoices) && !empty($clientInvoices)){
            foreach( $clientInvoices as $invoice){
                if($invoice->sent === 1){
                    $sentInvoiceAmount += $invoice->invoice_amount;
                }
                if($invoice->paid === 1){
                    $paidInvoiceAmount += $invoice->invoice_amount;
                }
            }
        }

        // Get Total Number of Pending Client Invoices
        $clientInvoicesOut = ClientInvoices::with(['client'])->whereNull('paid')->where('sent','=',1)->get();
        // Customer Invoices Out
        $customerInvoicesOut = Invoice::with(['customer','items.item'])->whereNull('paid')->where('email_sent','=',1)->get();
        //Log::info($customerInvoicesOut);
        $customerSentInvoiceAmount = 0;
        $customerPaidInvoiceAmount = 0;
        $customerInvoicesTotal = Invoice::get();
        if(isset($customerInvoicesTotal) && !empty($customerInvoicesTotal)){
            foreach($customerInvoicesTotal as $customerInvoice){
                if($customerInvoice->email_sent === 1){
                    $customerSentInvoiceAmount += $customerInvoice->invoice_amount;
                }
                if($customerInvoice->paid === 1){
                    $customerPaidInvoiceAmount += $customerInvoice->invoice_amount;
                }
            }
        }
        // Get number of Active Clients
        $clients = Clients::where('STATUS','1')->get();
        // Get total FMV
        $fmv = FMV::all();
        $fmvToAssignment = Fmv::whereNotNull('assignment_id')->get();

        $months = array('Jan' => '01' ,'Feb' => '02','Mar' => '03','Apr' => '04','May' => '05','Jun' => '06','Jul'=> '07','Aug' => '08','Sep'=>'09','Oct' =>'10' ,'Nov'=>'11','Dec'=> '12');
        $years = array('2016', '2017', '2018', '2019', '2020', '2021');

        return view('home', [
                         'sentInvoiceAmount' => $sentInvoiceAmount,
                         'paidInvoiceAmount' => $paidInvoiceAmount,
                         'totalActiveClients' => count($clients),
                         'totalFMV' => count($fmv),
                         'clientInvoicesOut' => $clientInvoicesOut,
                         'customerInvoicesOut' => $customerInvoicesOut,
                         'customerSentInvoiceAmount' =>$customerSentInvoiceAmount,
                         'customerPaidInvoiceAmount' => $customerPaidInvoiceAmount,
                         'totalFmvToAssignment' => count($fmvToAssignment),
                         'year' => $years,
                         'months' => $months
                ]

        );
    }

    /* Loads the Column Chart for Types of FMV */
    public function loadFmvTypeAnalysis( Request $request)
    {

        $year = $request->input('year');
        $currentYear = Carbon::now()->year;

        if(isset($year)&& !empty($year)){
            $filterYear = $year;
        } else {
            $filterYear = $currentYear;
        }

        //Log::info($filterYear);

        $fmvData = Fmv::with(['items'])->orderBy('cdate', 'asc')->whereYear('cdate', '=', $filterYear)->get();
        $assignmentData = Assignment::with(['items'])->orderBy('dt_stmp','asc')->whereYear('dt_stmp','=',$filterYear)->get();
        
        //Log::info($fmvData);
        // Lets generate Empty Stats for Calculations.
        $lowFmvItems = array('Jan' => 0 ,'Feb' => 0,'Mar' => 0,'Apr' => 0,'May' => 0,'Jun' => 0,'Jul'=> 0,'Aug'=>0,'Sep'=>0,'Oct' =>0 ,'Nov'=> 0,'Dec'=> 0);
        $medFmvItems = array('Jan' => 0 ,'Feb' => 0,'Mar' => 0,'Apr' => 0,'May' => 0,'Jun' => 0,'Jul'=> 0,'Aug'=>0,'Sep'=>0,'Oct' =>0 ,'Nov'=> 0,'Dec'=> 0);
        $highFmvItems = array('Jan' => 0 ,'Feb' => 0,'Mar' => 0,'Apr' => 0,'May' => 0,'Jun' => 0,'Jul'=> 0,'Aug'=>0,'Sep'=>0,'Oct' =>0 ,'Nov'=> 0,'Dec'=> 0);
        $assignmentItems = array('Jan' => 0 ,'Feb' => 0,'Mar' => 0,'Apr' => 0,'May' => 0,'Jun' => 0,'Jul'=> 0,'Aug'=>0,'Sep'=>0,'Oct' =>0 ,'Nov'=> 0,'Dec'=> 0);

        $yearMonth = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
        $FmvGenerated = array('Jan' => 0 ,'Feb' => 0,'Mar' => 0,'Apr' => 0,'May' => 0,'Jun' => 0,'Jul'=> 0,'Aug'=>0,'Sep'=>0,'Oct' =>0 ,'Nov'=> 0,'Dec'=> 0);
        $assignmentGenerated = array('Jan' => 0 ,'Feb' => 0,'Mar' => 0,'Apr' => 0,'May' => 0,'Jun' => 0,'Jul'=> 0,'Aug'=>0,'Sep'=>0,'Oct' =>0 ,'Nov'=> 0,'Dec'=> 0);

        if (isset($fmvData) && !empty($fmvData)) {
            foreach ($fmvData as $fmv) {
                if (isset($fmv['sent_date']) && !empty($fmv['sent_date'])) {
                    $rawSentDate = explode(' ', $fmv['sent_date']);
                    if(isset($rawSentDate) && !empty($rawSentDate)) {
                        $sentDate = Carbon::createFromFormat('Y-m-d', $rawSentDate[0])->format('M');
                        foreach($yearMonth as $month){
                            if($month == $sentDate){
                                $FmvGenerated[$month] +=1;
                                if(isset($fmv['assignment_id']) && !empty($fmv['assignment_id'])){
                                    $assignmentGenerated[$month] +=1;
                                }
                                if(isset($fmv['items']) && !empty($fmv['items'])){
                                    foreach($fmv['items'] as $item){
                                        if(isset($item['low_fmv_estimate']) && !empty($item['low_fmv_estimate'])){
                                            $lowFmvItems[$month] += $item['low_fmv_estimate'];
                                        }
                                        if(isset($item['mid_fmv_estimate']) && !empty($item['mid_fmv_estimate'])){
                                            $medFmvItems[$month] += $item['mid_fmv_estimate'];
                                        }
                                        if(isset($item['high_fmv_estimate']) && !empty($item['high_fmv_estimate'])){
                                            $highFmvItems[$month] += $item['high_fmv_estimate'];
                                        }
                                    }
                                }
                            }
                        }
                    }

                }
            }
        }
        
          // Processing assignment data
        if(isset($assignmentData) && !empty($assignmentData)){
            foreach ($assignmentData as $assignment) {
                if (isset($assignment['dt_stmp']) && !empty($assignment['dt_stmp'])) {
                    $rawSentDate = explode(' ', $assignment['dt_stmp']);
                    if(isset($rawSentDate) && !empty($rawSentDate)) {
                        $sentDate = Carbon::createFromFormat('Y-m-d', $rawSentDate[0])->format('M');
                        foreach($yearMonth as $month){
                            if($month == $sentDate) {
                                if (isset($assignment['items']) && !empty($assignment['items'])) {
                                    foreach ($assignment['items'] as $item) {
                                        if (isset($item['FMV']) && !empty($item['FMV'])) {
                                            $assignmentItems[$month] += $item['FMV'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //Log::info($lowFmvItems);
        return response(['status' => true,
                         'data'   => $fmvData ,
                         'lowFmv' => $lowFmvItems,
                         'medFmv' => $medFmvItems,
                         'highFmv'=> $highFmvItems,
                         'assignmentFmv' => $assignmentItems,
                         'FmvGenerated' => $FmvGenerated,
                         'assignmentGenerated' => $assignmentGenerated,
                         'filterYear' => $filterYear], 200);
    }

    /* Load Basic Data */
    public function homeAnalytics( Request $request ){

        $validator = \Validator::make(
            array(
                'month' => $request->get('month'),
                'year'  => $request->get('year')
            ),
            array(
                'month' => 'nullable',
                'year'  => 'nullable'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            $month = $request->input('month');
            $year = $request->input('year');
            $currentYear = Carbon::now()->year;
            $currentMonth = Carbon::now()->month;


            $filterMonth = array($currentMonth);
            $filterYear = $currentYear;

            if(isset($month)&& !empty($month)){
                $filterMonth = $month;
            }

            if(isset($year)&& !empty($year)){
                $filterYear = $year;
            }

            // Lets Get Number of Assignments

            $assignments = Assignment::with(['items.invoiceAuth.invoice.remittance', 'items.expense.expenseAuth.invoice'])
                           //->whereMonth('dt_stmp', '=', $filterMonth)
                           ->whereIn(DB::raw('MONTH(dt_stmp)'), $filterMonth)
                           ->whereYear('dt_stmp','=', $filterYear)->get();

            //Log::info($assignments);

            $approvedAssignments = 0;
            $activeAssignments = 0;
            $openAssignments = 0;
            $closedAssignments = 0;
            $totalAssignments = count($assignments);
            $totalAssets = 0;
            $expectedOlvValue = 0;
            $assetsSold = 0;
            $customerInvoiceSent = 0;
            $customerInvoicePaid = 0;
            $totalCustomerInvoice = 0;

            $feeInvoiceSent = 0;
            $feeInvoicePaid = 0;
            $totalFeeInvoice = 0;

            $clientRemittanceAmount = 0;

            if(isset($assignments) && !empty($assignments)){

                foreach($assignments as $assignment) {
                        if($assignment->active === 1){
                            $activeAssignments += 1;
                        }
                        if($assignment->approved === 1){
                            $approvedAssignments += 1;
                        }
                        if($assignment->isopen === 1){
                            $openAssignments += 1;
                        }
                        if($assignment->isopen === 0){
                            $closedAssignments += 1;
                        }

                        // Lets Count Total Assets
                        if(isset($assignment->items) && !empty($assignment->items)){
                            $totalAssets += count($assignment->items);

                            foreach($assignment->items as $item) {

                                if($item->SOLD_FLAG === 1 ){
                                    $assetsSold += 1;
                                }
                                $expectedOlvValue += $item->FMV;
                            }
                        }

                }
            }

            //Client Remittance
            $clientRemittanceOut = ClientRemittance::where('SENT', '=', 1)
                                   //->whereMonth('SENT_DATE', '=', $filterMonth)
                                   ->whereIn(DB::raw('MONTH(SENT_DATE)'), $filterMonth)
                                   ->whereYear('SENT_DATE','=', $filterYear)->get();

            if(isset($clientRemittanceOut) && !empty($clientRemittanceOut)) {
                foreach($clientRemittanceOut as $remittanceOut) {
                    $clientRemittanceAmount += $remittanceOut->REMITTANCE_AMT;
                }
            }

            // Client Invoice Sent
            $clientInvoicesOut = ClientInvoices::with(['client'])
                ->where('sent','=',1)
                //->whereMonth('sent_dt', '=', $filterMonth)
                ->whereIn(DB::raw('MONTH(sent_dt)'), $filterMonth)
                ->whereYear('sent_dt','=', $filterYear)->get();

            if(isset($clientInvoicesOut) && !empty($clientInvoicesOut)) {
                foreach($clientInvoicesOut as $invoiceOut) {
                    $totalFeeInvoice += 1;
                    $feeInvoiceSent += $invoiceOut->invoice_amount;
                }
            }

            $clientInvoicesPaid = ClientInvoices::with(['client'])
                ->where('sent','=',1)
                ->where('paid', '=', '1')
                //->whereMonth('paid_dt1', '=', $filterMonth)
                ->whereIn(DB::raw('MONTH(paid_dt1)'), $filterMonth)
                ->whereYear('paid_dt1','=', $filterYear)->get();

            if(isset($clientInvoicesPaid) && !empty($clientInvoicesPaid)) {
                foreach($clientInvoicesPaid as $invoicePaid) {
                    $feeInvoicePaid += $invoicePaid->invoice_amount;
                }
            }
           //Log::info($clientInvoicesPaid);
            // Customer Invoice Out
            $customerInvoicesOut = Invoice::with(['customer','items.item'])
                ->where('email_sent','=',1)
                //->whereMonth('sent_date', '=', $filterMonth)
                ->whereIn(DB::raw('MONTH(sent_date)'), $filterMonth)
                ->whereYear('sent_date','=', $filterYear)
                ->get();

            if(isset($customerInvoicesOut) && !empty($customerInvoicesOut)) {
                foreach($customerInvoicesOut as $customerInvoiceOut) {
                    $totalCustomerInvoice += 1;
                    $customerInvoiceSent += $customerInvoiceOut->invoice_amount;
                }
            }

            $customerInvoicesPaid = Invoice::with(['customer','items.item'])
                ->where('email_sent','=',1)
                ->where('paid','=',1)
                //->whereMonth('paid_dt', '=', $filterMonth)
                ->whereIn(DB::raw('MONTH(paid_dt)'), $filterMonth)
                ->whereYear('paid_dt','=', $filterYear)
                ->get();

            if(isset($customerInvoicesPaid) && !empty($customerInvoicesPaid)) {
                foreach($customerInvoicesPaid as $customerPaid) {
                    $customerInvoicePaid += $customerPaid->invoice_amount;
                }
            }


            //Log::info($customerInvoicesOut);

            $grossCommission = $customerInvoicePaid-$clientRemittanceAmount;
            //Log::info($customerPendingInvoiceNo);
            $data = array();
            $data['approvedAssignments'] = $approvedAssignments;
            $data['activeAssignments'] = $activeAssignments;
            $data['openAssignments'] = $openAssignments;
            $data['closedAssignments'] = $closedAssignments;
            $data['totalAssignments'] = $totalAssignments;
            $data['totalAssets'] = $totalAssets;
            $data['expectedOlvValue'] = $expectedOlvValue;
            $data['assetsSold'] = $assetsSold;
            $data['customerInvoiceSent'] = $customerInvoiceSent;
            $data['customerInvoicePaid'] = $customerInvoicePaid;
            $data['totalCustomerInvoice'] = $totalCustomerInvoice;
            $data['feeInvoiceSent'] = $feeInvoiceSent;
            $data['feeInvoicePaid'] = $feeInvoicePaid;
            $data['totalFeeInvoice'] = $totalFeeInvoice;
            $data['clientRemittanceAmount'] = $clientRemittanceAmount;
            $data['commissionEarned'] = $grossCommission;
            $data['profit']           = $grossCommission+$feeInvoicePaid;



            return response()->json(['status' => true, 'html' => View('home.analytics', compact('data'))->render()]);

        }


    }
    /* Load the Client Remittance */

    public function getClientRemittance( Request $request)
    {

        $validator = \Validator::make(
            array(
                'month' => $request->get('month'),
                'year' => $request->get('year')
            ),
            array(
                'month' => 'nullable|array',
                'year' => 'nullable'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $month = $request->input('month');
                $year = $request->input('year');
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;


                $filterMonth = array($currentMonth);
                $filterYear = $currentYear;

                if(isset($month)&& !empty($month)){
                    $filterMonth = $month;
                }

                if(isset($year)&& !empty($year)){
                    $filterYear = $year;
                }

                //Log::info($filterMonth);
                //Log::info($filterYear);

                $clientRemittanceData = ClientRemittance::with(['invoice'])
                    ->whereIn(DB::raw('MONTH(GENERATED_DATE)'), $filterMonth)
                    ->whereYear('GENERATED_DATE','=', $filterYear)->get();

                //::info($clientRemittanceData);

                return response()->json(['status' => true, 'html' => View('home.clientRemittance', compact('clientRemittanceData'))->render()]);

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
    /* Load the Client Invoices */

    public function getEquipmentInvoices( Request $request){

        $validator = \Validator::make(
            array(
                'month' => $request->get('month'),
                'year'  => $request->get('year')
            ),
            array(
                'month' => 'nullable|array',
                'year'  => 'nullable'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $month = $request->input('month');
                $year = $request->input('year');
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;


                $filterMonth = array($currentMonth);
                $filterYear = $currentYear;

                if(isset($month)&& !empty($month)){
                    $filterMonth = $month;
                }

                if(isset($year)&& !empty($year)){
                    $filterYear = $year;
                }

                //Log::info($filterMonth);
                //Log::info($filterYear);

                $clientInvoicesOut = ClientInvoices::with(['client', 'lines.expense.item'])
                                     ->where('sent','=',1)
                                     //->whereMonth('sent_dt', '=', $filterMonth)
                                     ->whereIn(DB::raw('MONTH(paid_dt1)'), $filterMonth)
                                     ->whereYear('sent_dt','=', $filterYear)->get();

                //Log::info($clientInvoicesOut);


                return response()->json(['status' => true, 'html' => View('home.clientInvoice', compact('clientInvoicesOut'))->render()]);

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

    /* Load Customer invoices */
    public function getCustomerInvoices( Request $request){

        $validator = \Validator::make(
            array(
                'month' => $request->get('month'),
                'year'  => $request->get('year')
            ),
            array(
                'month' => 'nullable|array',
                'year'  => 'nullable'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $month = $request->input('month');
                $year = $request->input('year');
                $currentYear = Carbon::now()->year;
                $currentMonth = Carbon::now()->month;


                $filterMonth = array($currentMonth);
                $filterYear = $currentYear;

                if(isset($month)&& !empty($month)){
                    $filterMonth = $month;
                }

                if(isset($year)&& !empty($year)){
                    $filterYear = $year;
                }

                //Log::info($filterMonth);
                //Log::info($filterYear);

                $customerInvoicesOut = Invoice::with(['customer','items.item'])
                                       ->where('email_sent','=',1)
                                       //->whereMonth('sent_date', '=', $filterMonth)
                                       ->whereIn(DB::raw('MONTH(paid_dt)'), $filterMonth)
                                       ->whereYear('sent_date','=', $filterYear)
                                       ->orderBy('generated_date','asc')->get();

                //Log::info($customerInvoicesOut);
                return response()->json(['status' => true, 'html' => View('home.customerInvoice', compact('customerInvoicesOut'))->render()]);

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
    /* Load All the previous assignment */
    public function getAllClosedAssignments(){

        try{
            $cacheExpiresAt = Carbon::now()->addMinutes(30);
            $assignment = '';

            if(Cache::get('home-allClosedAssignments')){
                $assignment = Cache::get('home-allClosedAssignment'); // Lets Read Cache Data if existed.
            } else {
                $assignmentQuery = Assignment::with(['items.invoiceAuth.invoice.remittance', 'items.itemContractor'])
                    ->whereHas('items')
                    ->where('isopen', '=', 0)
                    ->where('approved', '=', 1)
                    ->get();

                if (isset($assignmentQuery) && !empty($assignmentQuery)) {

                    $assignment = $assignmentQuery;
                    Cache::put('home-allClosedAssignment', $assignmentQuery, $cacheExpiresAt);
                }
            }

            return response()->json(['status' => true, 'data' => $assignment ]);

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
    /* Load Items not sold yet */
    public function getUnsoldItemsAssignments(){

        try{

            $cacheExpiresAt = Carbon::now()->addMinutes(30);

            $assignment = '';

            if(Cache::get('home-unsoldItemsAssignment')){

                $assignment = Cache::get('home-unsoldItemsAssignment'); // Lets Read Cache Data if existed.

            } else {

                $assignmentQuery = Assignment::with(['items.invoiceAuth.invoice.remittance', 'items.itemContractor'])
                    //->whereDoesntHave('items.invoiceAuth')
                    ->whereHas('items')
                    ->where('isopen', '=', '1')
                    ->where('approved', '=', 1)
                    ->get();

                if (isset($assignmentQuery) && !empty($assignmentQuery)) {

                    $assignment = $assignmentQuery;
                    Cache::put('home-unsoldItemsAssignment', $assignmentQuery, $cacheExpiresAt);
                }
            }

            if(isset($assignment) && !empty($assignment)) {

                $i=0;
                foreach($assignment as $a) {
                    if(isset($a->items) && count($a->items) > 0) {
                       foreach($a->items as $item){
                           if(!empty($item->itemContractor)){
                               $assignment[$i]['assign_status'] = 'ItemRecovery';
                           }
                           if(!empty($item->invoiceAuth)){
                               $assignment[$i]['assign_status'] = 'ItemSold';
                               if(isset($item->invoiceAuth->invoice) && !empty($item->invoiceAuth->invoice)){
                                   if($item->invoiceAuth->invoice->paid === 1){
                                       $assignment[$i]['assign_status'] = 'CustomerPaid';
                                   }
                               }
                               if(isset($item->invoiceAuth->invoice->remittance) && !empty($item->invoiceAuth->invoice->remittance)){
                                   if($item->invoiceAuth->invoice->remittance->SENT === 1){
                                       $assignment[$i]['assign_status'] = 'ClientPaid';
                                   }
                               }

                           }
                       }
                   }
                $i++;}
            }
            //Log::info($assignment);

            return response()->json(['status' => true, 'data' => $assignment ]);

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

    /** Assignment Marker **/

    public function assignmentMarker( Request $request ){

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->input('assignment_id'),
            ),
            array(
                'assignment_id' => 'required',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $assignment_id = $request->input('assignment_id');

                $assignment = Assignment::with(['items.invoiceAuth.invoice.remittance','items.itemContractor'])->findOrFail($assignment_id);
                //Log::info($assignment);

                $data['assignment'] = $assignment;

                return response()->json(['success' => true, 'html' => View('home.assignmentMarker', compact('data'))->render()]);


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
