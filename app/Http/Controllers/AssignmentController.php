<?php

namespace App\Http\Controllers;

use App\Helpers\GeocodeHelper;
use App\Mail\SendClientInvoice;
use App\Mail\SendContractorAuthorization;
use App\Mail\SendCustomerInvoice;
use App\Models\Assignment;
use App\Models\AssignmentHasClients;
use App\Models\AssignmentHasFiles;
use App\Models\ClientInvoices;
use App\Models\ClientRemittance;
use App\Models\ClientRemittanceHasExpense;
use App\Models\Clients;
use App\Models\Communications;
use App\Models\ContractorAuth;
use App\Models\ContractorAuthItem;
use App\Models\Contractors;
use App\Models\Customer;
use App\Models\CustomerInvoice;
use App\Models\ExpenseType;
use App\Models\Fmv;
use App\Models\FmvHasFiles;
use App\Models\Invoice;
use App\Models\ItemHasExpense;
use App\Models\Items;
use App\Models\State;
use App\Models\User;
use App\Notifications\NewAssignmentNotification;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{

    protected $cacheExpires;

    public function __construct()
    {
        $this->middleware('auth')->except(['fmvToAssignmentByClient','createAssignmentFromFmvByClient','uploadAuthorizedItemPictures']);

        $this->cacheExpires = Carbon::now()->addMinutes(30);

    }

    // Get All Assignments
    public function get() {
        return view('assignment.list');
    }

    //Load the Assignments Via Ajax Data Tables
    public function getDatatable( Request $request ){

        //Log::info($request);
        $totalRecordsPerPage = $request->get('length');
        $draw = $request->get('draw');
        $start = $request->get('start');
        $length = $request->get('length');

        // Datatable Parameters.
        $columnOrder = $request->get('order');
        $columnsData = $request->get('columns');
        $searchData = $request->get('search');

        if( (isset($start) && !empty($start)) && ( isset($length) && !empty($length)) ){

            $pageNo = ($start/$length)+1;

        } else {
            $pageNo = 1;
        }


        if( isset($totalRecordsPerPage) && !empty($totalRecordsPerPage) ){
            $recordsPerPage = $totalRecordsPerPage;
        } else {
            $recordsPerPage = 10;
        }

        $assignment = Assignment::with(['communicationsPrivate','communicationsPublic','client.clientInfo','items']);


        if(!empty($columnOrder)) {
            foreach($columnOrder as $colOrder){
                //Lets Explode for the JOIN data.
                $columnName = explode('.',$columnsData[$colOrder['column']]['data']);
                if(count($columnName) == 1) {
                    $assignment->orderBy($columnsData[$colOrder['column']]['data'], $colOrder['dir']);
                } else {
                    //Log::info('No sorting needed');
                }
            }
        }

        /* Client Filter */
        if(isset($columnsData[6]['search']['value']) && !empty($columnsData[6]['search']['value'])){
            $searchClient = $columnsData[6]['search']['value'];
            $assignment->whereHas('client', function($query) use ($searchClient) {
                         $query->where('client_id',$searchClient);
            });
        }

        /* Assigment is Open Status Default Open*/
        if($columnsData[8]['search']['value'] !== NULL){
            $status = $columnsData[8]['search']['value'];
            $assignment->where('isopen',$status);
        }else {
            //Log::info('OPEN');
            $assignment->where('isopen', '=', 1);
        }

        /* Assigment Status */
        if(isset($columnsData[9]['search']['value'])){
            $status = $columnsData[9]['search']['value'];
            $assignment->where('approved',$status);
        }

        if($columnsData[10]['search']['value'] !== NULL) {
            $status = $columnsData[10]['search']['value'];
            $assignment->where('is_appraisal', $status);
        }

        if(!empty($searchData['value'])){
            //Log::info($searchData['value']);
            $searchVar = $searchData['value'];
            //Log::info($searchVar);
            $assignment->where( function($q) use ($searchVar) {

                $q->whereHas('items', function($query) use ($searchVar) {
                    if(is_numeric($searchVar)){
                       //$query->where('ITEM_ID', '=', $searchVar);
                        $query->where('ITEM_ID', 'LIKE', '%'.$searchVar.'%');
                        $query->orWhere('ITEM_SERIAL', 'LIKE', '%'.$searchVar.'%');
                    } else{
                        $query->where('ITEM_MAKE', 'LIKE', '%'.$searchVar.'%');
                        $query->orWhere('ITEM_MODEL', 'LIKE', '%'.$searchVar.'%');
                        $query->orWhere('ITEM_SERIAL', 'LIKE', '%'.$searchVar.'%');
                        $query->orWhere('ITEM_DESC', 'LIKE', '%'.$searchVar.'%');
                    }

                });

                if(!is_numeric($searchVar)){
                    $q->orWhere('lease_nmbr', 'LIKE', '%'.$searchVar.'%');
                    //$q->orWhere('ls_full_name', 'LIKE', '%'.$searchVar.'%');
                    $q->orWhere('ls_company', 'LIKE','%'.$searchVar.'%');
                    //$q->orWhere('ls_address1', 'LIKE', '%'.$searchVar.'%');
                }
                if(is_numeric($searchVar)){
                    $q->orWhere('assignment_id', 'LIKE', '%'.$searchVar.'%');
                }

            });
        }

        //Log::info($assignment->toSql());

        $assignment = $assignment->orderBy('dt_stmp','desc')->paginate($recordsPerPage, ['*'], 'page', $pageNo);

        //Log::info($assignment);

        return response( ['status' => true,
            'draw' => $draw ,
            'recordsTotal' => $assignment->total(),
            'recordsFiltered' => $assignment->total(),
            'data' => $assignment->items() ] , 200);
    }

    //Create Assignment to FMV By Client without Authentication

    public function fmvToAssignmentByClient( Request $request){

        if (!$request->hasValidSignature()) {
            redirect(401);
        }

        //Log::info($request->fmv_id);
        $fmv = Fmv::with(['user', 'client'])->findOrFail($request->fmv_id);

        if(isset($fmv->assignment_id) && !empty($fmv->assignment_id)){
            return view('frontend.assignment.assignmentEmailExits');
        }

        return view('frontend.assignment.assignmentEmail',['fmv_id' => $request->fmv_id, 'states' => State::all()]);

    }

    // Allowing Contractors to upload Pictures Still working URL.
    public function uploadAuthorizedItemPictures( Request $request ){

        $contractorAuthorizations =  ContractorAuth::with(['authItems.item', 'contractor'])->findorfail(37680);

        //Log::info($contractorAuthorizations);

        return view('frontend.contractor.updateAuthorizedItems',['contractorAuthorizations' => $contractorAuthorizations]);

    }

    //Upload Pictures By Contractor
    public function saveAuthorizedItemPictures( Request $request){

        $assignmentFiles = $request->file('file');
    }

    // Create Assignment From FMV By client
    public function createAssignmentFromFmvByClient( Request $request){

        $validator = \Validator::make(
            array(
                'fmv_id'  => $request->get('fmv_id'),
                'ls_full_name' => $request->get('ls_full_name'),
                'ls_company' => $request->get('ls_company'),
                'ls_address1'  => $request->get('ls_address1'),
                'ls_city' => $request->get('ls_city'),
                'ls_state' => $request->get('ls_state'),
                'ls_zip' => $request->get('ls_zip'),
                'ls_buss_phone' => $request->get('ls_buss_phone'),
                'client_note'  => $request->get('client_note')
            ),
            array(
                'fmv_id'  => 'required',
                'ls_full_name' => 'required',
                'ls_company' => 'required',
                'ls_address1' => 'required',
                'ls_city' => 'required',
                'ls_state' => 'required',
                'ls_zip' => 'required',
                'ls_buss_phone' => 'nullable',
                'client_note' => 'nullable',
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                $fmv_id = $request->get('fmv_id');
                $ls_full_name = $request->get('ls_full_name');
                $ls_company = $request->get('ls_company');
                $ls_address1 = $request->get('ls_address1');
                $ls_city = $request->get('ls_city');
                $ls_state = $request->get('ls_state');
                $ls_zip = $request->get('ls_zip');
                $ls_buss_phone = $request->get('ls_buss_phone');
                $client_note = $request->get('client_note');
                $assignmentFiles = $request->file('file');

                $fmv = Fmv::with(['user', 'client'])->findOrFail($fmv_id);

                if(isset($fmv) && !empty($fmv)){
                    // let make Assignment

                    $assignment = new Assignment();
                    $assignment->lease_nmbr = $fmv->lease_number;
                    $assignment->active = 0;
                    $assignment->approved = 0;
                    $assignment->isopen = 1;
                    $assignment->ls_full_name = $ls_full_name;
                    $assignment->ls_company = $ls_company;
                    $assignment->ls_address1 = $ls_address1;
                    $assignment->ls_city = $ls_city;
                    $assignment->ls_state = $ls_state;
                    $assignment->ls_zip = $ls_zip;
                    $assignment->ls_buss_phone = $ls_buss_phone;
                    $assignment->clients_note = $client_note;
                    $assignment->lst_upd = Carbon::now()->format('Y-m-d H:i:s');
                    $assignment->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                    $assignment->is_FMVCENTRAL_generated = 1;
                    if(!empty($fmv->client)){
                        $assignment->placed_by = $fmv->client->FIRSTNAME.' '.$fmv->client->LASTNAME;
                    }
                    $assignment->source = 'CLIENT';
                    $assignment->save();

                    $fmv->update(['assignment_id' => $assignment->assignment_id ]); // update Assignment

                    //Lets Add Client for Assignment As well.
                    $assignmentHasClient = new AssignmentHasClients();
                    $assignmentHasClient->client_id = $fmv->client_id;
                    $assignmentHasClient->assignment_id =  $assignment->assignment_id;
                    $assignmentHasClient->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                    $assignmentHasClient->save();

                    if(isset($assignmentFiles) && !empty($assignmentFiles)){

                        foreach($assignmentFiles as $uploadFile){

                            $fileOriginalName = trim($uploadFile->getClientOriginalName());
                            $fileOriginalName = str_replace(" ", "_", $fileOriginalName);
                            $fileInfo  = Storage::disk('gcs')->put('assignment/'.$assignment->assignment_id, $uploadFile);
                            Storage::disk('gcs')->setVisibility($fileInfo, 'public');
                            $assignmentHasFiles = new AssignmentHasFiles();
                            $assignmentHasFiles->assignment_id = $assignment->assignment_id;
                            $assignmentHasFiles->filename = $fileInfo;
                            $assignmentHasFiles->fileType = $uploadFile->getClientOriginalExtension();
                            $assignmentHasFiles->logs = $fileOriginalName;
                            $assignmentHasFiles->status = true;
                            $assignmentHasFiles->save();

                        }

                    }

                    $admins = User::all();
                    $notificationFor = $fmv->client->COMPANY;
                    Notification::send($admins, new NewAssignmentNotification($assignment,$notificationFor, $fmv->client->FIRSTNAME.' '.$fmv->client->LASTNAME, 'New Assignment added'));
                    return response(['status' => true , 'message' => array('Assignment submitted successfully') ], 200);
                }
                return response(['status' => false , 'message' => array('Missing FMV information')], 200);



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
    // Change the assignment Assign
    public function reassignAssignment(){

        $assignment =  Assignment::with(['client.clientInfo'])->orderBy('dt_stmp','desc')->get();

        return view('assignment.reassign',[
            'assignment' => $assignment,
            'clients' => Clients::orderBy('FIRSTNAME', 'asc')->get()
        ]);

    }
    // AJAX request assignment Assign
    public function ajaxReassignAssignment( Request $request){

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->input('assignment_id'),
                'client_id'     => $request->get('client_id'),
            ),
            array(
                'assignment_id' => 'required',
                'client_id'     => 'required',
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try {

                $assignmentId = $request->get('assignment_id');
                $client_id = $request->get('client_id');

                Assignment::findOrFail($assignmentId);
                Clients::findOrFail($client_id);

                $assignmentDetails = Assignment::with(['client'])->find($assignmentId);

                $assignmentHasClient = AssignmentHasClients::find($assignmentDetails->client->id);
                $assignmentHasClient->client_id = $client_id;
                $assignmentHasClient->save();

                //$this->updateAssignmentCache($assignmentId);

                return response(['status' => true, 'message' => array('Assignment was updated successfully')], 200);


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
    // Create Assignment From FMV
    public function createAssignmentFromFmv($id){

        try {

            $fmv = Fmv::with(['user', 'client', 'items'])->findOrFail($id);

            if(isset($fmv) && !empty($fmv)){

                 // let make Assignment
                 $assignment = new Assignment();
                 $assignment->lease_nmbr = $fmv->lease_number;
                 $assignment->active = 0;
                 $assignment->approved = 0;
                 $assignment->isopen = 1;
                 $assignment->ls_company = $fmv->debtor_company;
                 $assignment->lst_upd = Carbon::now()->format('Y-m-d H:i:s');
                 $assignment->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                 $assignment->is_FMVCENTRAL_generated = 1;
                 $assignment->placed_by = Auth::user()->name;
                 $assignment->placed_by_admin_id = Auth::id();
                 $assignment->source = 'ADMIN';
                 $assignment->save();

                 $fmv->update(['assignment_id' => $assignment->assignment_id ]); // update Assignment

                //Lets Copy the FMV File to Assignment As well.

                $fmvPdf = PDF::loadView('fmv.fmvPdf', ['fmv' => $fmv]);
                $fileOriginalName = 'FMV_'.Carbon::now()->timestamp.'.pdf';
                Storage::disk('gcs')->put('assignment/'.$assignment->assignment_id.'/'.$fileOriginalName, $fmvPdf->output());

                $assignmentHasFiles = new AssignmentHasFiles();
                $assignmentHasFiles->assignment_id = $assignment->assignment_id;
                $assignmentHasFiles->filename = 'assignment/'.$assignment->assignment_id.'/'.$fileOriginalName;
                $assignmentHasFiles->fileType = 'pdf';
                $assignmentHasFiles->logs = 'FMV file';
                $assignmentHasFiles->status = false;
                $assignmentHasFiles->save();

                 //Lets Add Client for Assignment As well.
                $assignmentHasClient = new AssignmentHasClients();
                $assignmentHasClient->client_id = $fmv->client_id;
                $assignmentHasClient->assignment_id =  $assignment->assignment_id;
                $assignmentHasClient->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                $assignmentHasClient->save();

                // Lets Add the Items as well.
                if(isset($fmv->items) && !empty($fmv->items)){
                    $i=1;foreach($fmv->items as $it){

                        // Get the Lat And Lng
                        $itemAddress = $it->equip_city.''.$it->equip_state.''.$it->equip_zip;
                        $address= urlencode($itemAddress);

                        $geoCode = new GeocodeHelper();
                        $LatLng = $geoCode->geocode($address);

                        //Log::info($LatLng);
                        $itemLat = '';
                        $itemLng = '';

                        if( (isset($LatLng['latitude']) && !empty($LatLng['longitude'])) && ( isset($LatLng['longitude']) && !empty($LatLng['longitude']) ) ) {
                            $itemLat = $LatLng['latitude'];
                            $itemLng = $LatLng['longitude'];
                        }

                        $item = new Items();
                        $item->ITEM_NMBR = $i;
                        $item->ASSIGNMENT_ID = $assignment->assignment_id;
                        $item->QUANTITY = 1;
                        $item->ITEM_YEAR = $it->equip_year;
                        $item->ITEM_MAKE = $it->make;
                        $item->ITEM_MODEL = $it->model;
                        $item->ITEM_SERIAL = $it->ser_nmbr;
                        $item->ITEM_DESC = $it->item_description;
                        $item->LOC_CITY = $it->equip_city;
                        $item->LOC_STATE = $it->equip_state;
                        $item->LOC_ZIP = $it->equip_zip;
                        $item->ORIG_COST = $it->orig_amt;
                        $item->lat = $itemLat;
                        $item->lng = $itemLng;
                        $item->DT_STMP = Carbon::now()->format('Y-m-d H:i:s');
                        $item->LST_UPDT = Carbon::now()->format('Y-m-d H:i:s');
                        //$item->FMV = $it->high_fmv_estimate;
                        $item->FMV = $it->mid_fmv_estimate;
                        $item->save();
                    $i++;}
                }

                //$admins = User::all();
                //$notificationFor = $fmv->client->COMPANY;
                //Notification::send($admins, new NewAssignmentNotification($assignment,$notificationFor, Auth::user()->name, 'New Assignment added'));

                 return response(['status' => true , 'message' => array('Assignment created successfully'),'assignment_id' => $assignment->assignment_id ], 200);
            }
            return response(['status' => false , 'message' => array('Missing FMV information')], 200);

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

    // Add new Assignment Form
    public function addForm(){

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

        return view('assignment.add',[
            'months' => $months,
            'clients' => Clients::orderBy('FIRSTNAME', 'asc')->get(),
            'states'  => State::all()
        ]);

    }

    // Update Assignment
    public function updateAssignment( Request $request){

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->input('assignment_id'),
                'ls_full_name' => $request->input('ls_full_name'),
                'ls_company' => $request->input('ls_company'),
                'ls_address1' => $request->input('ls_address1'),
                'ls_city' => $request->input('ls_city'),
                'ls_state' => $request->input('ls_state'),
                'ls_zip' => $request->input('ls_zip'),
                'ls_buss_phone' => $request->input('ls_buss_phone'),
                'lease_numbr' => $request->input('lease_numbr'),
                'dt_lease_inception_month' => $request->input('dt_lease_inception_month'),
                'dt_lease_inception_year' => $request->input('dt_lease_inception_year'),
                'lease_term' => $request->input('lease_term'),
                'isopen' => $request->input('isopen'),
                'approved' => $request->input('approved'),
                'recovered' => $request->input('recovered'),
                'recovery_month' => $request->input('recovery_month'),
                'recovery_day'=> $request->input('recovery_day'),
                'recovery_year' => $request->input('recovery_year'),
                'client_note' => $request->input('client_note'),
                'res_repo' => $request->input('res_repo'),
                'res_coll' => $request->input('res_coll'),
                'res_skip' => $request->input('res_skip'),
                'res_rmkt' => $request->input('res_rmkt'),
                'res_app' => $request->input('res_app'),
                'res_drive' => $request->input('res_drive'),
                'res_fmv'   => $request->input('res_fmv'),
                'res_knock' => $request->input('res_knock'),
                'res_inv'   => $request->input('res_knock'),
                'res_ins'   => $request->input('res_ins'),
                'res_eol'   => $request->input('res_ins'),
                'make_prior_contact' => $request->input('make_prior_contact'),
                'is_appraisal' => $request->input('is_appraisal'),
            ),
            array(
                'assignment_id' => 'required|int',
                'ls_full_name' => 'required',
                'ls_company' => 'required',
                'ls_address1' => 'required',
                'ls_city' => 'required',
                'ls_state' => 'required',
                'ls_zip' => 'required',
                'ls_buss_phone' => 'required',
                'lease_numbr' => 'required',
                'dt_lease_inception_month' => 'nullable',
                'dt_lease_inception_year' => 'nullable',
                'lease_term' => 'nullable',
                'isopen' => 'nullable',
                'approved' => 'nullable',
                'recovered' => 'nullable',
                'recovery_month' => 'nullable',
                'recovery_day' => 'nullable',
                'recovery_year' => 'nullable',
                'client_note' => 'nullable',
                'res_repo' => 'nullable',
                'res_coll' => 'nullable',
                'res_skip' => 'nullable',
                'res_rmkt' => 'nullable',
                'res_app' => 'nullable',
                'res_drive' => 'nullable',
                'res_fmv'   => 'nullable',
                'res_knock' => 'nullable',
                'res_inv'   => 'nullable',
                'res_ins'   => 'nullable',
                'res_eol'   => 'nullable',
                'make_prior_contact' => 'nullable',
                'is_appraisal' => 'nullable'

            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $assignment_id = $request->input('assignment_id');
                $ls_full_name = $request->input('ls_full_name');
                $ls_company = $request->input('ls_company');
                $ls_address1 = $request->input('ls_address1');
                $ls_city = $request->input('ls_city');
                $ls_state = $request->input('ls_state');
                $ls_zip = $request->input('ls_zip');
                $ls_buss_phone = $request->input('ls_buss_phone');
                $lease_numbr = $request->input('lease_numbr');
                $dt_lease_inception_month =  $request->input('dt_lease_inception_month');
                $dt_lease_inception_year = $request->input('dt_lease_inception_year');
                $lease_term = $request->input('lease_term');
                $isopen = $request->input('isopen');
                $approved = $request->input('approved');
                $client_note = $request->input('client_note');
                $res_repo = $request->input('res_repo');
                $res_coll = $request->input('res_coll');
                $res_skip = $request->input('res_skip');
                $res_rmkt = $request->input('res_rmkt');
                $res_app = $request->input('res_app');
                $res_drive = $request->input('res_drive');
                $res_fmv = $request->input('res_fmv');
                $res_knock = $request->input('res_knock');
                $res_inv = $request->input('res_knock');
                $res_ins = $request->input('res_ins');
                $res_eol  = $request->input('res_ins');
                $make_prior_contact = $request->input('make_prior_contact');
                $recovered = $request->input('recovered');
                $recovery_month  = $request->input('recovery_month');
                $recovery_day  = $request->input('recovery_day');
                $recovery_year = $request->input('recovery_year');
                $is_appraisal = $request->input('is_appraisal');



                // let make Assignment
                $assignment = Assignment::findorfail($assignment_id);
                $assignment->ls_full_name = $ls_full_name;
                $assignment->ls_company = $ls_company;
                $assignment->ls_company = $ls_company;
                $assignment->ls_address1 = $ls_address1;
                $assignment->ls_city = $ls_city;
                $assignment->ls_state = $ls_state;
                $assignment->ls_zip = $ls_zip;
                $assignment->ls_buss_phone = $ls_buss_phone;
                $assignment->lease_nmbr = $lease_numbr;
                $assignment->lease_term = $lease_term;
                $assignment->clients_note = $client_note;
                $assignment->active = 1;
                $assignment->approved = $approved;
                $assignment->isopen = $isopen;
                $assignment->res_repo = $res_repo;
                $assignment->res_coll =  $res_coll;
                $assignment->res_skip = $res_skip;
                $assignment->res_rmkt = $res_rmkt;
                $assignment->res_app = $res_app;
                $assignment->res_fmv = $res_fmv;
                $assignment->res_drive = $res_drive;
                $assignment->res_inv = $res_inv;
                $assignment->res_knock = $res_knock;
                $assignment->res_ins = $res_ins;
                $assignment->res_eol = $res_eol;
                $assignment->prior_contact = $make_prior_contact;
                $assignment->lst_upd = Carbon::now()->format('Y-m-d H:i:s');
                $assignment->is_FMVCENTRAL_generated = 0;
                $assignment->placed_by = Auth::user()->name;
                $assignment->placed_by_admin_id = Auth::id();
                $assignment->source = 'ADMIN';
                $assignment->is_appraisal = $is_appraisal;
                if(!empty($dt_lease_inception_year) && !empty($dt_lease_inception_month)) {
                    $assignment->dt_lease_inception = $dt_lease_inception_year . '-' . $dt_lease_inception_month . '-' . '01 00:00:00';
                }
                $assignment->recovered = $recovered;
                if(!empty($recovery_day) && !empty($recovery_month) && !empty($recovery_year)) {
                    $assignment->recovery_dt = $recovery_year . '-' . $recovery_month . '-' . $recovery_day .' 00:00:00';
                }
                $assignment->save();

                //$this->updateAssignmentCache($assignment_id);

                return response(['status' => true , 'message' => array('Assignment updated successfully'), 'assignment_id' => $assignment->assignment_id ], 200);


            }  catch (Exception $e) {

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

    // Add New Assignment
    public function addNewAssignment( Request $request){

        $validator = \Validator::make(
            array(
                'client_id' => $request->input('client_id'),
                'ls_full_name' => $request->input('ls_full_name'),
                'ls_company' => $request->input('ls_company'),
                'ls_address1' => $request->input('ls_address1'),
                'ls_city' => $request->input('ls_city'),
                'ls_state' => $request->input('ls_state'),
                'ls_zip' => $request->input('ls_zip'),
                'ls_buss_phone' => $request->input('ls_buss_phone'),
                'lease_numbr' => $request->input('lease_numbr'),
                'dt_lease_inception_month' => $request->input('dt_lease_inception_month'),
                'dt_lease_inception_year' => $request->input('dt_lease_inception_year'),
                'lease_term' => $request->input('lease_term'),
                'isopen' => $request->input('isopen'),
                'approved' => $request->input('approved'),
                'recovered' => $request->input('recovered'),
                'recovery_month' => $request->input('recovery_month'),
                'recovery_day'=> $request->input('recovery_day'),
                'recovery_year' => $request->input('recovery_year'),
                'client_note' => $request->input('client_note'),
                'res_repo' => $request->input('res_repo'),
                'res_coll' => $request->input('res_coll'),
                'res_skip' => $request->input('res_skip'),
                'res_rmkt' => $request->input('res_rmkt'),
                'res_app' => $request->input('res_app'),
                'res_drive' => $request->input('res_drive'),
                'res_fmv'   => $request->input('res_fmv'),
                'res_knock' => $request->input('res_knock'),
                'res_inv'   => $request->input('res_knock'),
                'res_ins'   => $request->input('res_ins'),
                'res_eol'   => $request->input('res_ins'),
                'make_prior_contact' => $request->input('make_prior_contact'),
                'is_appraisal' => $request->input('is_appraisal'),
                'is_inspection' => $request->input('is_inspection'),
            ),
            array(
                'client_id' => 'required|int',
                'ls_full_name' => 'required',
                'ls_company' => 'required',
                'ls_address1' => 'required',
                'ls_city' => 'required',
                'ls_state' => 'required',
                'ls_zip' => 'required',
                'ls_buss_phone' => 'required',
                'lease_numbr' => 'required',
                'dt_lease_inception_month' => 'nullable',
                'dt_lease_inception_year' => 'nullable',
                'lease_term' => 'nullable',
                'isopen' => 'nullable',
                'approved' => 'nullable',
                'recovered' => 'nullable',
                'recovery_month' => 'nullable',
                'recovery_day' => 'nullable',
                'recovery_year' => 'nullable',
                'client_note' => 'nullable',
                'res_repo' => 'nullable',
                'res_coll' => 'nullable',
                'res_skip' => 'nullable',
                'res_rmkt' => 'nullable',
                'res_app' => 'nullable',
                'res_drive' => 'nullable',
                'res_fmv'   => 'nullable',
                'res_knock' => 'nullable',
                'res_inv'   => 'nullable',
                'res_ins'   => 'nullable',
                'res_eol'   => 'nullable',
                'make_prior_contact' => 'nullable',
                'is_appraisal' => 'nullable',
                'is_inspection' => 'nullable'

            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

             try{

                 $client_id = $request->input('client_id');
                 $ls_full_name = $request->input('ls_full_name');
                 $ls_company = $request->input('ls_company');
                 $ls_address1 = $request->input('ls_address1');
                 $ls_city = $request->input('ls_city');
                 $ls_state = $request->input('ls_state');
                 $ls_zip = $request->input('ls_zip');
                 $ls_buss_phone = $request->input('ls_buss_phone');
                 $lease_numbr = $request->input('lease_numbr');
                 $dt_lease_inception_month =  $request->input('dt_lease_inception_month');
                 $dt_lease_inception_year = $request->input('dt_lease_inception_year');
                 $lease_term = $request->input('lease_term');
                 $isopen = $request->input('isopen');
                 $approved = $request->input('approved');
                 $client_note = $request->input('client_note');
                 $res_repo = $request->input('res_repo');
                 $res_coll = $request->input('res_coll');
                 $res_skip = $request->input('res_skip');
                 $res_rmkt = $request->input('res_rmkt');
                 $res_app = $request->input('res_app');
                 $res_drive = $request->input('res_drive');
                 $res_fmv = $request->input('res_fmv');
                 $res_knock = $request->input('res_knock');
                 $res_inv = $request->input('res_knock');
                 $res_ins = $request->input('res_ins');
                 $res_eol  = $request->input('res_ins');
                 $make_prior_contact = $request->input('make_prior_contact');
                 $is_appraisal = $request->input('is_appraisal');
                 $is_inspection = $request->input('is_inspection');

                 Clients::findorfail($client_id);

                 // let make Assignment
                 $assignment = new Assignment();
                 $assignment->ls_full_name = $ls_full_name;
                 $assignment->ls_company = $ls_company;
                 $assignment->ls_company = $ls_company;
                 $assignment->ls_address1 = $ls_address1;
                 $assignment->ls_city = $ls_city;
                 $assignment->ls_state = $ls_state;
                 $assignment->ls_zip = $ls_zip;
                 $assignment->ls_buss_phone = $ls_buss_phone;
                 $assignment->lease_nmbr = $lease_numbr;
                 $assignment->lease_term = $lease_term;
                 $assignment->clients_note = $client_note;
                 $assignment->active = 1;
                 $assignment->approved = $approved;
                 $assignment->isopen = $isopen;
                 $assignment->res_repo = $res_repo;
                 $assignment->res_coll =  $res_coll;
                 $assignment->res_skip = $res_skip;
                 $assignment->res_rmkt = $res_rmkt;
                 $assignment->res_app = $res_app;
                 $assignment->res_fmv = $res_fmv;
                 $assignment->res_drive = $res_drive;
                 $assignment->res_inv = $res_inv;
                 $assignment->res_knock = $res_knock;
                 $assignment->res_ins = $res_ins;
                 $assignment->res_eol = $res_eol;
                 $assignment->prior_contact = $make_prior_contact;
                 $assignment->lst_upd = Carbon::now()->format('Y-m-d H:i:s');
                 $assignment->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                 $assignment->is_FMVCENTRAL_generated = 0;
                 $assignment->placed_by = Auth::user()->name;
                 $assignment->placed_by_admin_id = Auth::id();
                 $assignment->source = 'ADMIN';
                 $assignment->is_appraisal = $is_appraisal;
                 $assignment->is_inspection = $is_inspection;
                 if(!empty($dt_lease_inception_year) && !empty($dt_lease_inception_month)) {
                     $assignment->dt_lease_inception = $dt_lease_inception_year . '-' . $dt_lease_inception_month . '-' . '01 00:00:00';
                 }
                 $assignment->save();

                 //Lets Add Client for Assignment As well.
                 $assignmentHasClient = new AssignmentHasClients();
                 $assignmentHasClient->client_id = $client_id;
                 $assignmentHasClient->assignment_id =  $assignment->assignment_id;
                 $assignmentHasClient->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                 $assignmentHasClient->save();

                 return response(['status' => true , 'message' => array('Assignment created successfully'), 'assignment_id' => $assignment->assignment_id ], 200);


             }  catch (Exception $e) {

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

    // Get Child Information Assignment
    public function getChildInfoAssignment( Request $request){

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->get('assignment_id'),
            ),
            array(
                'assignment_id' => 'required',
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
             try{

                 $assignmentId = $request->get('assignment_id');
                 $assignment = Assignment::with(['communicationsPrivate','communicationsPublic','client.clientInfo', 'files', 'items.categories.category', 'items.bids.customer','items.expense'])->findorfail($assignmentId);

                 $itemsData = array();
                 $contractData = array();
                 $clientInvoiceData = array();
                 $customerInvoiceData = array();

                 if(isset($assignment->items) && !empty($assignment->items)) {

                     // Lets get the Contractor Authorizations.
                     foreach($assignment->items as $item) {
                         array_push($itemsData, $item['ITEM_ID']);
                     }

                     //Log::info($itemsData);
                     if(!empty($itemsData)) {
                         $contractorAuthorizations = ContractorAuth::with(['authItems.item', 'contractor']);
                         $contractorAuthorizations->whereHas('authItems.item', function ($query) use ($itemsData) {
                             if (isset($itemsData) && !empty($itemsData)) {
                                 $query->whereIn('item_id', $itemsData);
                             }
                         });
                         $contractData = $contractorAuthorizations->get();
                     }
                     // We can alo PULLS Client Invoices.
                     if(!empty($itemsData)){
                         $clientInvoice  =  ClientInvoices::with(['lines.expense']);
                         $clientInvoice->whereHas('lines.expense', function($query) use ($itemsData) {
                             $query->whereIn('item_id', $itemsData);
                         });
                         $clientInvoiceData = $clientInvoice->get();
                     }

                     // Lets Get Customer Invoices as well.
                     if(!empty($itemsData)){
                         $customerInvoice =  Invoice::with(['customer','items']);
                         $customerInvoice->whereHas('items', function($query) use ($itemsData) {
                             $query->whereIn('item_id', $itemsData);
                         });
                         $customerInvoiceData = $customerInvoice->get();
                     }


                 }

                 return response()->json(['success' => true, 'html' => View('assignment.childList', compact('assignment','contractData','clientInvoiceData','customerInvoiceData'))->render()]);

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

    // Edit Assignment
    public function show($id) {

       $assignmentQuery = Assignment::with(['communicationsPrivate',
                'communicationsPublic',
                'client.clientInfo',
                'files',
                'items.categories.category',
                'items.bids.customer',
                'items.expense',
                'items.itemContractor',
                'items.invoiceAuth.invoice.remittance'])->findorfail($id);

        //Log::info($assignment);

        $assignment = $assignmentQuery;
        $itemsData = array();
        $contractData = array();
        $clientInvoiceData = array();
        $customerInvoiceData = array();

        // Get List of All the Customers.
        $customers = Customer::where('ACTIVE', '=', 1)->get();

        // Get All different types of expense
        //$expenseType = ItemHasExpense::distinct()->get(['expense_type']);
        $expenseType = ExpenseType::where('status',1)->get();

        if(isset($assignment->items) && !empty($assignment->items)) {

            // Lets get the Contractor Authorizations.
            foreach($assignment->items as $item) {
                array_push($itemsData, $item['ITEM_ID']);
            }

             //Log::info($itemsData);
            if(!empty($itemsData)) {
                $contractorAuthorizations = ContractorAuth::with(['authItems.item', 'contractor']);
                $contractorAuthorizations->whereHas('authItems.item', function ($query) use ($itemsData) {
                    if (isset($itemsData) && !empty($itemsData)) {
                        $query->whereIn('item_id', $itemsData);
                    }
                });
                $contractData = $contractorAuthorizations->get();
                //Log::info($contractData);
            }
            // We can alo PULLS Client Invoices.
            if(!empty($itemsData)){
                $clientInvoice  =  ClientInvoices::with(['lines.expense']);
                $clientInvoice->whereHas('lines.expense', function($query) use ($itemsData) {
                           $query->whereIn('item_id', $itemsData);
                });
                $clientInvoiceData = $clientInvoice->get();
            }

            // Lets Get Customer Invoices as well.
            if(!empty($itemsData)){
                $customerInvoice =  Invoice::with(['customer','items']);
                $customerInvoice->whereHas('items', function($query) use ($itemsData) {
                    $query->whereIn('item_id', $itemsData);
                });
                $customerInvoiceData = $customerInvoice->get();
            }


        }

        //Log::info($customerInvoiceData);

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
        //Log::info($assignment);
        return view('assignment.edit',[
                          'assignment' => $assignment,
                          'contractorData' => $contractData,
                          'clientInvoiceData' => $clientInvoiceData,
                          'customerInvoiceData' => $customerInvoiceData,
                          'months' => $months,
                          'customers' => $customers,
                          'expenseType' => $expenseType,
                          'contractors' => Contractors::where('active','=',1)
                                           ->where('use_contractor','=',1)
                                           ->where('approved','=','1')
                                           ->orderBy('first_name','asc')->get()
               ]);

    }

    // Add Files to Assignment
    public function addFiles(  Request $request )
    {

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->get('assignment_id'),
                'file' => $request->file('file')
            ),
            array(
                'assignment_id' => 'required',
                'file' => 'required'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $assignmentId = $request->get('assignment_id');
                $assignmentFiles = $request->file('file'); // Attached files
                //Log::info($assignmentFiles);

                foreach ($assignmentFiles as $uploadFile) {

                    $fileOriginalName = trim($uploadFile->getClientOriginalName());
                    $fileOriginalName = str_replace(" ", "_", $fileOriginalName);
                    $fileInfo = Storage::disk('gcs')->put('assignment/' . $assignmentId, $uploadFile);
                    Storage::disk('gcs')->setVisibility($fileInfo, 'public');
                    $assignmentHasFiles = new AssignmentHasFiles();
                    $assignmentHasFiles->assignment_id = $assignmentId;
                    $assignmentHasFiles->filename = $fileInfo;
                    $assignmentHasFiles->fileType = $uploadFile->getClientOriginalExtension();
                    $assignmentHasFiles->logs = $fileOriginalName;
                    $assignmentHasFiles->status = false;
                    $assignmentHasFiles->save();

                }

                //$this->updateAssignmentCache($assignmentId);

                return response(['status' => true, 'message' => array('Assignment was updated successfully')], 200);

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

    // delete File
    public function deleteFile($id) {

        try{

            $assFile = AssignmentHasFiles::findorfail($id);
            //$this->updateAssignmentCache($assFile->assignment_id); // Update Cache
            Storage::disk('gcs')->delete($assFile->filename);
            $assFile->delete();

            return response(['status' => true, 'message' => array('File successfully removed')], 200);


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

    // Update Visibility
    public function visibilityReportFiles( Request $request ){

        $validator = \Validator::make(
            array(
                'status'    => $request->input('status'),
                'file_id' => $request->input('file_id')
            ),
            array(
                'status' => 'required',
                'file_id' => 'required|int'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try{

                $status = $request->input('status');
                $file_id = $request->input('file_id');

                $files = AssignmentHasFiles::findorfail($file_id);
                $files->update(['status' => $status]);

                //$this->updateAssignmentCache($files->assignment_id); // Update Cache

                return response(['status' => true, 'data' => $files], 200);


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
    public function saveCommunicationAssignment( Request $request){

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->get('assignment_id'),
                'client_id'     => $request->get('client_id'),
                'note'          => $request->get('note'),
                'type'          => $request->get('type')
            ),
            array(
                'assignment_id' => 'required',
                'client_id'     => 'required',
                'note'          => 'required',
                'type'          => 'required'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
             try {

                 $assignmentId = $request->get('assignment_id');
                 $client_id = $request->get('client_id');
                 $note = $request->get('note');
                 $type = $request->get('type');

                 Assignment::findOrFail($assignmentId);
                 Clients::findOrFail($client_id);

                 $communication = new Communications();
                 $communication->client_id = $client_id;
                 $communication->assignment_id = $assignmentId;
                 if($type == 'public'):
                     $communication->ispublic = 1;
                 else:
                     $communication->isprivate = 1;
                 endif;
                 $communication->dt_stmp = Carbon::now()->format('Y-m-d H:i:s');
                 $communication->admin_id = Auth::user()->id;
                 $communication->posted_by = 'ADMIN';
                 $communication->ip_address = $request->ip();
                 $communication->communication_note = $note;
                 $communication->save();

                 //$this->updateAssignmentCache($assignmentId);

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
    // Find Near By Contractors
    public function findNearByContractors( Request $request ){

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->get('assignment_id'),
                'contractor_ids' => $request->get('contractor_ids')
            ),
            array(
                'assignment_id' => 'required',
                'contractor_ids' => 'nullable|array'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $assignmentId = $request->get('assignment_id');
                $contractor_ids = $request->get('contractor_ids');
                //Log::info($contractor_ids);
                $assignment = Assignment::with(['items'])->findOrFail($assignmentId);
                $dataContractors = array();
                //Log::info($assignment->items);
                $stateFilter = array();
                $latFilterValue = '';
                $lngFilterValue = '';

                if( isset($assignment->items) && !empty($assignment->items)){
                     foreach($assignment->items as $item) {
                            // Lets get States
                            if(!empty($item['LOC_STATE'])) {
                                if( !in_array($item['LOC_STATE'], $stateFilter)){
                                    array_push($stateFilter, $item['LOC_STATE']);
                                }
                            }
                            if(!empty($item['lat']) && !empty($item['lng'])){
                                $latFilterValue = $item['lat'];
                                $lngFilterValue = $item['lng'];
                            }
                     }
                    //Log::info($stateFilter);
                }

                //Log::info($stateFilter);

                if(!empty($stateFilter)) {

                    //$contractors = Contractors::all();
                    $contractorsQuery = Contractors::query();

                    if(!empty($contractor_ids)){ // if we are searching
                        $contractorsQuery->whereIn('contractor_id', $contractor_ids);
                    } else {

                        if(!empty($latFilterValue) && !empty($lngFilterValue)){ // if we have Lat and Lng

                            $contractorsQuery->select(DB::raw("*, ( 3959 * acos( cos( radians('$latFilterValue') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$lngFilterValue') ) + sin( radians('$latFilterValue') ) * sin( radians( lat ) ) ) ) AS distance"))->havingRaw('distance < 200');

                        } else { // Filter by State
                            $contractorsQuery->whereIn('state', $stateFilter)
                                ->where('active','=',1)
                                ->where('use_contractor','=',1)
                                ->where('approved','=','1');
                        }

                    }

                    $contractors = $contractorsQuery->get();

                    //Log::info($contractors);
                    if(isset($contractors) && !empty($contractors)){

                        $i=0;foreach($contractors as $contr){

                            $contractorAddress = $contr->address1.''.$contr->state.''.$contr->city.''.$contr->zip1;

                            if(!empty($contr->lat) && !empty($contr->lng)){
                                $LatLong = array('latitude' => $contr->lat, 'longitude' => $contr->lng , 'address' => $contractorAddress );
                            } else {
                                $LatLong = array('latitude' => '', 'longitude' => '' , 'address' => $contractorAddress);
                            }
                            $dataContractors[$i]['contractor_id'] = $contr->contractor_id;
                            $dataContractors[$i]['name'] = $contr->first_name.' '.$contr->last_name;
                            $dataContractors[$i]['type']  = 'C';
                            $dataContractors[$i]['address_code'] = $LatLong;
                            $i++;
                        }

                         //Lets Add Items as well. Not Doing right now.
                        foreach($assignment->items as $item) {
                            if(!empty($item['lat']) && !empty($item['lng'])) {
                                $LatLong = array('latitude' => $item['lat'], 'longitude' => $item['lng'] , 'address' => '' );
                                $dataContractors[$i]['name'] = $assignment->ls_full_name;
                                $dataContractors[$i]['type']  = 'A';
                                $dataContractors[$i]['address_code'] = $LatLong;

                            }
                        $i++;
                        }

                    }

                } else {
                    return response(['status' => false, 'data' => array(), 'errors' => array('Items do not have address associated')], 200);
                }

                return response(['status' => true, 'data' => $dataContractors ], 200);

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

        // Contractor Marker Point
        public function contractorMarker( Request $request ){

            $validator = \Validator::make(
                array(
                    'assignment_id' => $request->get('assignment_id'),
                    'contractor_id' => $request->get('contractor_id')
                ),
                array(
                    'assignment_id' => 'required',
                    'contractor_id' => 'required'
                )
            );

            if ($validator->fails()) {
                return response(['status' => false, 'errors' => $validator->messages()], 200);
            } else {

                try{

                    $assignment_id = $request->get('assignment_id');
                    $contractor_id = $request->get('contractor_id');

                    $assignment = Assignment::with(['items'])->findOrFail($assignment_id);
                    $contractor = Contractors::with(['contractorCategories.category'])->findOrFail($contractor_id);

                    $data['assignment'] = $assignment;
                    $data['contractor'] = $contractor;

                    //Log::info($assignment);

                    return response()->json(['success' => true, 'html' => View('assignment.contractorMarker', compact('data'))->render()]);


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

    // View Authorization
    public function viewContractorAuthorization($authId, $assignmentId){

        //Log::info($assignmentId);
        $contractorAuthorizations =  ContractorAuth::with(['authItems.item', 'contractor'])->findorfail($authId);
        $assignment = Assignment::findorfail($assignmentId);

        $authorizationPdf = PDF::loadView('assignment.contractorAuthPdf', ['authorization' => $contractorAuthorizations, 'assignment' => $assignment]);
        return $authorizationPdf->download('IPA_pickup_authorization#'.$assignmentId.'.pdf');

    }

    // View Client Invoice
    public function viewClientInvoice($invoiceId, $assignmentId){

        $clientInvoice  =  ClientInvoices::with(['client','lines.expense.item'])->findorfail($invoiceId);
        $assignment = Assignment::findorfail($assignmentId);

        $InvoicePdf = PDF::loadView('assignment.clientInvoicePdf', ['clientInvoice' => $clientInvoice, 'assignment' => $assignment]);
        return $InvoicePdf->download('IPA_client_invoice#'.$assignmentId.'.pdf');

    }

    // Client Remittance PDF.
    public function viewClientRemittancePdf( $remittanceId ){

        $clientRemittance = ClientRemittance::with(['remittanceExpense','invoice.items.item.assignment','client'])->findorfail($remittanceId);
        //Log::info($clientRemittance);

        $remittancePdf = PDF::loadView('assignment.clientRemittancePdf', ['clientRemittance' => $clientRemittance ]);
        return $remittancePdf->download('IPA_client_remittance#'.$remittanceId.'.pdf');

    }

    // Send Client Invoice
    public function sendClientInvoice($invoiceId, $assignmentId){

        try {

            $clientInvoice = ClientInvoices::findorfail($invoiceId);
            $assignment = Assignment::findorfail($assignmentId);

            $invoiceData = ClientInvoices::with(['client'])->find($invoiceId);

            if (isset($invoiceData->client->EMAIL) && !empty($invoiceData->client->EMAIL)) {

                if($invoiceData->client->invoice_email === 1){
                    Mail::to($invoiceData->client->EMAIL)->cc(['arizzo@inplaceauction.com','ecastagna@inplaceauction.com'])->send(new SendClientInvoice( $clientInvoice ,$assignment ));
                } else {
                    Mail::to('ecastagna@inplaceauction.com')->cc(['arizzo@inplaceauction.com'])->send(new SendClientInvoice( $clientInvoice ,$assignment ));
                }

                $clientInvoice->update(['sent_dt' => Carbon::now()->format('Y-m-d H:i:s'), 'sent' => 1]);

                //$this->updateAssignmentCache($assignmentId);

                return response(['status' => true, 'message' => array('Email sent successfully')], 200);

            } else {
                return response(['status' => false, 'message' => array('Missing the request by email address')], 200);
            }

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

    // Send Contractor Authorization
    public function sendContractorAuthorization($authorizedId, $assignmentId){

       try{

           $contractorAuthorization = ContractorAuth::with(['contractor'])->findorfail($authorizedId);
           $assignment = Assignment::findorfail($assignmentId);

           if(isset($contractorAuthorization->send_to_email) && !empty($contractorAuthorization->send_to_email)) {

               if($contractorAuthorization->contractor->invoice_email === 1) {

                   Mail::to($contractorAuthorization->send_to_email)->cc(['arizzo@inplaceauction.com', 'ecastagna@inplaceauction.com'])->send(new SendContractorAuthorization($contractorAuthorization, $assignment));

               } else {

                   Mail::to('ecastagna@inplaceauction.com')->cc(['arizzo@inplaceauction.com'])->send(new SendContractorAuthorization($contractorAuthorization, $assignment));
               }

               $contractorAuthorization->update(['sent_date' => Carbon::now()->format('Y-m-d H:i:s'), 'email_sent' => 1]);

               //$this->updateAssignmentCache($assignmentId);

               return response(['status' => true, 'errors' => array('Email sent successfully')], 200);

           } else {

               return response(['status' => false, 'errors' => array('Missing the request by email address')], 200);

           }


       }  catch (Exception $e) {

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

    // Authorize Contractor
    public function authorizeContractor( Request $request){


        $validator = \Validator::make(
            array(
                'contractor_id'          => $request->input('contractor_id'),
                'items'                  => $request->input('items'),
                'send_email'             => $request->input('send_email'),
                'type_of_pickup'         => $request->input('type_of_pickup'),
                'special_instruction'    => $request->input('special_instruction'),
                'terms'                  => $request->input('terms'),
                'additional_information' => $request->input('additional_information'),
                'method'                 => $request->input('method')

            ),
            array(
                'contractor_id'          => 'required|int',
                'items'                  => 'required|array',
                'send_email'             => 'required|email',
                'type_of_pickup'         => 'required',
                'special_instruction'    => 'nullable',
                'terms'                  => 'nullable',
                'additional_information' => 'nullable',
                'method'                 => 'nullable'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $contractorId = $request->input('contractor_id');
                $items = $request->input('items');
                $send_email = $request->input('send_email');
                $type_of_pickup = $request->input('type_of_pickup');
                $special_instructions = $request->input('special_instruction');
                $terms = $request->input('terms');
                $additional_information = $request->input('additional_information');
                $method = $request->input('method');


                // Lets Authorize Contractor

                $authorizeContractor = new ContractorAuth();
                $authorizeContractor->contractor_id = $contractorId;
                $authorizeContractor->create_dt =  Carbon::now()->format('Y-m-d H:i:s');
                $authorizeContractor->special_instructions = $special_instructions;
                if($type_of_pickup == 'V') {
                    $authorizeContractor->voluntary = 1;
                }
                if($type_of_pickup == 'I') {
                    $authorizeContractor->involuntary = 1;
                }
                if($method == 'PC') {
                    $authorizeContractor->make_contact  = 1;
                }
                if($method == 'VA') {
                    $authorizeContractor->visit_unannounced = 1;
                }
                $authorizeContractor->terms = $terms;
                $authorizeContractor->add_info1 = $additional_information;
                $authorizeContractor->send_to_email = $send_email;
                $authorizeContractor->save();

                // Assign Items to Authorizations.

                $assignmentItem = '';

                foreach($items as $item){
                    $authorizeContractorItems = new ContractorAuthItem();
                    $authorizeContractorItems->contractor_auth_id = $authorizeContractor->contractor_auth_id;
                    $authorizeContractorItems->item_id = $item;
                    $authorizeContractorItems->save();
                    $assignmentItem = $item;
                }

                // Lets Copy the file in the Contractor Authorization in the Files
                $contractorAuthorizations =  ContractorAuth::with(['authItems.item', 'contractor'])->findorfail($authorizeContractor->contractor_auth_id);

                if(isset($contractorAuthorizations) && !empty($contractorAuthorizations)){

                    if(isset($contractorAuthorizations->authItems) && !empty($contractorAuthorizations->authItems)) {
                        $assignmentId = $contractorAuthorizations->authItems[0]->item->ASSIGNMENT_ID;

                        if(!empty($assignmentId)) {

                            $assignment = Assignment::findorfail($assignmentId);
                            $authorizationPdf = PDF::loadView('assignment.contractorAuthPdf', ['authorization' => $contractorAuthorizations, 'assignment' => $assignment]);

                            $fileOriginalName = 'ContractorAuthorization_'.$authorizeContractor->contractor_auth_id.'.pdf';
                            Storage::disk('gcs')->put('assignment/'.$assignment->assignment_id.'/'.$fileOriginalName, $authorizationPdf->output());

                            $assignmentHasFiles = new AssignmentHasFiles();
                            $assignmentHasFiles->assignment_id = $assignment->assignment_id;
                            $assignmentHasFiles->filename = 'assignment/'.$assignment->assignment_id.'/'.$fileOriginalName;
                            $assignmentHasFiles->fileType = 'pdf';
                            $assignmentHasFiles->logs = 'Contractor Authorization '. $authorizeContractor->contractor_auth_id;
                            $assignmentHasFiles->status = true;
                            $assignmentHasFiles->save();

                        }
                    }
                }

                if(isset($assignmentItem) && !empty($assignmentItem)) {
                    $itemData = Items::findorfail($assignmentItem);
                    //$this->updateAssignmentCache($itemData->ASSIGNMENT_ID);
                }


                return response(['status' => true, 'message' => array('Contractor Authorized successfully')], 200);


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

    // Mark Customer Invoice as Paid
    public function customerInvoicePaid( Request $request ){


        $validator = \Validator::make(
            array(
                'invoice_id'  => $request->input('invoice_id'),
                'paid_date'   => $request->input('paid_date'),
                'amount'      => $request->input('amount'),
                'type'        => $request->input('type'),
                'memo'        => $request->input('memo'),


            ),
            array(
                'invoice_id'  => 'required|int',
                'paid_date'   => 'required',
                'amount'      => 'required|int',
                'type'        => 'required',
                'memo'        => 'nullable'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $invoiceId = $request->input('invoice_id');
                $paidDate = $request->input('paid_date');
                $amount = $request->input('amount');
                $type = $request->input('type');
                $memo = $request->input('memo');

                $invoice = Invoice::with(['items.item.assignment.client'])->findorfail($invoiceId);
                $invoice->update(['paid' => 1, 'paid_dt' => Carbon::createFromFormat('j F, Y', $paidDate)->format('Y-m-d H:i:s'), 'paid_amount' => $amount, 'check_num' => $type, 'note' => $memo]);
                //Log::info($invoice);

                // Update Cache
                $assignmentItem = '';

                // We have to update the ITEM SOLD Flag As well.
                if(isset($invoice->items) && !empty($invoice->items)){
                    foreach($invoice->items as $it){
                        Items::where('ITEM_ID', $it['item_id'] )->update(['SOLD_FLAG'=>1]);
                        $assignmentItem = $it['item_id'];
                    }
                }

                if(isset($assignmentItem) && !empty($assignmentItem)) {
                    $itemData = Items::findorfail($assignmentItem);
                    //$this->updateAssignmentCache($itemData->ASSIGNMENT_ID);
                }


                return response(['status' => true, 'message' => array('Invoice was successfully marker paid.')], 200);



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

    // Mark Client Invoices as Paid
    public function clientInvoicePaid( Request $request) {


        $validator = \Validator::make(
            array(
                'invoice_id'  => $request->input('invoice_id'),
                'paid_date'   => $request->input('paid_date'),
                'amount'      => $request->input('amount'),
                'type'        => $request->input('type'),
                'memo'        => $request->input('memo'),


            ),
            array(
                'invoice_id'  => 'required|int',
                'paid_date'   => 'required',
                'amount'      => 'required|int',
                'type'        => 'required',
                'memo'        => 'nullable'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $invoiceId = $request->input('invoice_id');
                $paidDate = $request->input('paid_date');
                $amount = $request->input('amount');
                $type = $request->input('type');
                $memo = $request->input('memo');

                $invoice = ClientInvoices::with(['lines.expense'])->findorfail($invoiceId);

                //Log::info($invoice);

                $invoice->update(['paid' => 1, 'paid_dt1' => Carbon::createFromFormat('j F, Y', $paidDate)->format('Y-m-d H:i:s'), 'paid_amount1' => $amount, 'check_num1' => $type, 'note1' => $memo]);


                return response(['status' => true, 'message' => array('Invoice was successfully marker paid.')], 200);



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

    // Mark Client as Remitted
    public function customerAmountRemitted( Request $request) {

        $validator = \Validator::make(
            array(
                'invoice_auth_id'  => $request->input('remittance_invoice_id'),
                'paid_date'        => $request->input('remittance_date_paid'),
                'amount'           => $request->input('remittance_amount_paid'),
                'type'             => $request->input('remittance_type_paid'),


            ),
            array(
                'invoice_auth_id'  => 'required|int',
                'paid_date'        => 'required',
                'amount'           => 'required|int',
                'type'             => 'required',
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                //Log::info($request);
                // Variables
                $invoiceId = $request->input('remittance_invoice_id');
                $paidDate = $request->input('remittance_date_paid');
                $amount = $request->input('remittance_amount_paid');
                $type = $request->input('remittance_type_paid');
                $expenseArr = $request->input('expenseCommission');

                if( isset($expenseArr) && !empty($expenseArr) ){

                    foreach( $expenseArr as $e ){

                        $validator = \Validator::make(
                            array(
                                'expenseAmount'  => $e['expenseAmount']
                            ),
                            array(
                                'expenseAmount'  => 'required|int',
                            )
                        );

                        if ($validator->fails()) {
                            return response(['status' => false, 'errors' => $validator->messages()], 200);
                        }

                    }
                }

                $invoice = Invoice::with(['items.item.assignment.client'])->findorfail($invoiceId);

                // Generate IP Remittance Number
                $lastEntry = ClientRemittance::max('CLIENT_REMITTANCE_NUMBER');
                $lastEntryYear = substr($lastEntry, 0,4);
                $lastEntryMonth = substr($lastEntry, 4,2);
                $ipaRemittanceNumber = Carbon::now()->format('Y').Carbon::now()->format('m').'0001';

                if($lastEntryYear === Carbon::now()->format('Y')){
                    if($lastEntryMonth === Carbon::now()->format('m')) {
                        $ipaRemittanceNumber = $lastEntry + 1;
                    }
                }

                $clientID = $invoice->items[0]->item->assignment->client->client_id; //lets get Client ID.
                $assignmentID = $invoice->items[0]->item->ASSIGNMENT_ID; // let get the assignment ID as well

                DB::beginTransaction();

                $clientRemittance = new ClientRemittance();
                $clientRemittance->CLIENT_REMITTANCE_NUMBER = $ipaRemittanceNumber;
                $clientRemittance->CLIENT_ID = $clientID;
                $clientRemittance->INVOICE_AUTH_ID = $invoiceId;
                $clientRemittance->GENERATED = 1;
                $clientRemittance->GENERATED_DATE = Carbon::now()->format('Y-m-d H:i:s');
                $clientRemittance->SENT = 1;
                $clientRemittance->SENT_DATE = Carbon::createFromFormat('j F, Y', $paidDate)->format('Y-m-d H:i:s');
                $clientRemittance->REMITTANCE_DATE = Carbon::createFromFormat('j F, Y', $paidDate)->format('Y-m-d H:i:s');
                $clientRemittance->REMITTANCE_AMT = $amount;
                $clientRemittance->CHECKWIRENUM = $type;
                $clientRemittance->save();

                // Lets save the expense as well.

                if( isset($expenseArr) && !empty($expenseArr) ) {

                    foreach( $expenseArr as $e ){

                        $clientRemittanceExpense =  new ClientRemittanceHasExpense();
                        $clientRemittanceExpense->client_remittance_id = $clientRemittance->CLIENT_REMITTANCE_ID;
                        $clientRemittanceExpense->expenseType = $e['expenseType'];
                        $clientRemittanceExpense->expenseAmount = $e['expenseAmount'];
                        $clientRemittanceExpense->logs = $e['expenseType'];
                        $clientRemittanceExpense->save();

                    }
                }

                DB::commit();

                if(!empty($assignmentID)){

                    // Lets Add the File in the Assignment Table as well.
                    $clientRemittance = ClientRemittance::with(['remittanceExpense','invoice.items.item.assignment','client'])->findorfail($clientRemittance->CLIENT_REMITTANCE_ID);
                    $remittancePdf = PDF::loadView('assignment.clientRemittancePdf', ['clientRemittance' => $clientRemittance ]);

                    $fileOriginalName = 'ClientRemittance_'.$clientRemittance->CLIENT_REMITTANCE_NUMBER.'.pdf';
                    Storage::disk('gcs')->put('assignment/'.$assignmentID.'/'.$fileOriginalName, $remittancePdf->output());

                    $assignmentHasFiles = new AssignmentHasFiles();
                    $assignmentHasFiles->assignment_id = $assignmentID;
                    $assignmentHasFiles->filename = 'assignment/'.$assignmentID.'/'.$fileOriginalName;
                    $assignmentHasFiles->fileType = 'pdf';
                    $assignmentHasFiles->logs = 'Client Remittance '. $clientRemittance->CLIENT_REMITTANCE_NUMBER;
                    $assignmentHasFiles->status = true;
                    $assignmentHasFiles->save();

                    //$this->updateAssignmentCache($assignmentID);
                }


                return response(['status' => true, 'message' => array('Remittance was successfully updated.')], 200);


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

    // Get Closed Assignments Maps
    public function closedAssignments(){

        return view('assignment.closedMap');
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
            Cache::put('assignmentView-'.$id, $assignmentQuery, $this->cacheExpires);
        }

        return true;
    }
 }
