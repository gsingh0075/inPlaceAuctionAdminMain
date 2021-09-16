<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\ClientInvoices;
use App\Models\Clients;
use App\Models\Communications;
use App\Models\ContractorAuth;
use App\Models\Invoice;
use App\Models\State;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AssignmentClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:client');
    }

    /* Get All the Assignments For Client */
    public function get() {

        $clientId = auth()->user()->CLIENT_ID;

        $assignment = Assignment::with(['communicationsPrivate','communicationsPublic','client.clientInfo','items'])
                        ->whereHas('client', function ($q) use ($clientId){
                            $q->where('CLIENT_ID','=',$clientId);
                        })
                        //->where('isopen', '=', 1)
                        ->get();

        return view('clientDashboard.assignment.list', [ 'assignment' => $assignment ]);
    }

    /* Edit Assignment */
    public function show($id) {

        $assignment = Assignment::with(['communicationsPrivate',
                                        'communicationsPublic',
                                        'client.clientInfo',
                                        'clientFiles',
                                        'items.bids.customer',
                                        'items.categories.category',
                                        'items.clientReports'])->findorfail($id);

        //Log::info($assignment);

        $clientInvoiceData = array();
        $itemsData = array();

        if(isset($assignment->items) && !empty($assignment->items)) {

            foreach($assignment->items as $item) {
                array_push($itemsData, $item['ITEM_ID']);
            }

            // We can alo PULLS Client Invoices.
            if(!empty($itemsData)){
                $clientInvoice  =  ClientInvoices::with(['lines.expense']);
                $clientInvoice->whereHas('lines.expense', function($query) use ($itemsData) {
                    $query->whereIn('item_id', $itemsData);
                });
                $clientInvoiceData = $clientInvoice->get();
            }

        }
            //Log::info($assignment);
        return view('clientDashboard.assignment.show',[
            'assignment' => $assignment,
            'clientInvoiceData' => $clientInvoiceData
        ]);

    }

    // Search new Assignment
    public function searchNewAssignment(){

        return view('clientDashboard.assignment.search', []);

    }

    //Add new Assignment
    public function addNewAssignment() {

        $months = array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July ',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        );


        return view('clientDashboard.assignment.add',[
            'states'  => State::all(),
            'months' => $months
        ]);
    }

    // Save Notes
    public function saveClientCommunicationAssignment( Request $request){

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->input('assignment_id'),
                'note'          => $request->input('note'),
            ),
            array(
                'assignment_id' => 'required',
                'note'          => 'required',
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try {

                $assignmentId = $request->input('assignment_id');
                $note = $request->input('note');

                $clientId = auth()->user()->CLIENT_ID;

                Assignment::findOrFail($assignmentId);
                Clients::findOrFail($clientId);

                $communication = new Communications();
                $communication->client_id = $clientId;
                $communication->assignment_id = $assignmentId;
                $communication->ispublic = 1;
                $communication->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                $communication->posted_by = 'CLIENT';
                $communication->ip_address = $request->ip();
                $communication->communication_note = $note;
                $communication->save();

                $this->updateAssignmentCache($assignmentId);


                return response(['status' => true, 'message' => array('Note was saved successfully')], 200);


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

    // Save Notes
    public function saveClientCommunication( Request $request){

        $validator = \Validator::make(
            array(
                'note'          => $request->input('note'),
            ),
            array(
                'note'          => 'required',
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try {

                $note = $request->input('note');

                $clientId = auth()->user()->CLIENT_ID;

                Clients::findOrFail($clientId);

                $communication = new Communications();
                $communication->client_id = $clientId;
                $communication->ispublic = 1;
                $communication->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                $communication->posted_by = 'CLIENT';
                $communication->ip_address = $request->ip();
                $communication->communication_note = $note;
                $communication->save();

                return response(['status' => true, 'message' => array('Note was saved successfully')], 200);


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


    // Cache the Assignment
    public function updateAssignmentCache($id){

        // Lets Add to Cache
        $assignmentQuery = Assignment::with(['communicationsPrivate',
            'communicationsPublic',
            'client.clientInfo',
            'files',
            'items.categories.category',
            'items.bids.customer',
            'items.expense',
            'items.itemContractor',
            'items.invoiceAuth.invoice.remittance'])->findorfail($id);

        if (isset($assignmentQuery) && !empty($assignmentQuery)) {
            Cache::put('assignmentView-'.$id, $assignmentQuery, Carbon::now()->addMinutes(30));
        }

        return true;
    }

}
