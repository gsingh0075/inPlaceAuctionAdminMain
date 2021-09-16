<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Fmv;
use App\Models\FmvHasFiles;
use App\Models\User;
use App\Notifications\NewFmvNotification;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class FmvClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:client');
    }

    /* Get All the FMV For Client */
    public function get() {

        return view('clientDashboard.fmv.list');
    }

    /* Load the FMV Via Ajax Data Tables */
    public function getDatatable( Request $request ){

        //Log::info(auth()->user());
        $clientId = auth()->user()->CLIENT_ID;
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

        $fmv = Fmv::with(['user','client', 'items']);

        if(!empty($columnOrder)) {
            foreach($columnOrder as $colOrder){
                //Lets Explode for the JOIN data.
                $columnName = explode('.',$columnsData[$colOrder['column']]['data']);
                if(count($columnName) == 1) {
                    $fmv->orderBy($columnsData[$colOrder['column']]['data'], $colOrder['dir']);
                } else {
                    //Log::info('No sorting needed');
                }
            }
        }

        if(!empty($searchData['value'])){

            $fmv->orWhere('debtor_company', 'LIKE', $searchData['value'].'%');
            $fmv->orWhere('lease_number', 'LIKE', $searchData['value'].'%');
        }

        $fmv->where('client_id','=', $clientId);
        //Log::info($fmv->toSql());
        $fmv = $fmv->paginate($recordsPerPage, ['*'], 'page', $pageNo);
        //Log::info($fmv);
        return response( ['status' => true,
            'draw' => $draw ,
            'recordsTotal' => $fmv->total(),
            'recordsFiltered' => $fmv->total(),
            'data' => $fmv->items() ] , 200);
    }

    /* Generate PDF */
    public function generateFmvClientPDF($id){

        $fmv = Fmv::with(['files','items'])->findorfail($id);

        $fmvPdf = PDF::loadView('fmv.fmvPdf', ['fmv' => $fmv]);
        return $fmvPdf->download($fmv->fmv_id.'.pdf');

    }

    /* Add new FMV By Client */
    public function addForm(){

        return view('clientDashboard.fmv.add',[]);

    }

    /* Ajax request to Add FMV Data */
    public function addFmv( Request $request){

        //Log::info($request);

        $validator = \Validator::make(
            array(
                'reason'  => $request->get('reason'),
                'comments' => $request->get('comments'),
                'lease_number' => $request->get('lease_number'),
                'company_name' => $request->get('company_name'),
            ),
            array(
                'reason' => 'required',
                'comments' => 'nullable',
                'lease_number' => 'required',
                'company_name' => 'required',
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                $reason = $request->get('reason');
                $additional_comments = $request->get('comments');
                $lease_number = $request->get('lease_number');
                $company_name = $request->get('company_name');
                $fmvFiles = $request->file('file'); // Attached files\

                $clientId = auth()->user()->CLIENT_ID;

                $clientInfo = Clients::findorfail($clientId);

                $fmv = new Fmv();
                $fmv->priority = 1;
                $fmv->cdate = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->mdate = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->comments = $additional_comments;
                $fmv->lease_number = $lease_number;
                $fmv->request_date = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->request_by_lastname = $clientInfo->LASTNAME;
                $fmv->request_by_firstname = $clientInfo->FIRSTNAME;
                $fmv->request_by_email = $clientInfo->EMAIL;
                $fmv->request_by_phone = $clientInfo->PHONE;
                $fmv->debtor_company = $company_name;
                $fmv->reason_for_fmv = $reason;
                $fmv->client_id = $clientId;
                $fmv->placed_by = $clientInfo->FIRSTNAME.' '.$clientInfo->LASTNAME;
                $fmv->source = 'CLIENT';
                //$fmv->return_address = $return_address;
                $fmv->save();

                /* Lets Send Notification as well to other new FMV added */
                $admins = User::all();
                $client = Clients::where('CLIENT_ID',$clientId)->first();
                Notification::send($admins, new NewFmvNotification($fmv,$client, Auth::user()->FIRSTNAME.' '.Auth::user()->LASTNAME , 'New FMV added'));

                if(isset($fmvFiles) && !empty($fmvFiles)){

                    foreach($fmvFiles as $uploadFile){

                        $fileOriginalName = trim($uploadFile->getClientOriginalName());
                        $fileOriginalName = str_replace(" ", "_", $fileOriginalName);
                        $fileInfo  = Storage::disk('gcs')->put('fmv/'.$fmv->fmv_id, $uploadFile);
                        $fmvHasFiles = new FmvHasFiles();
                        $fmvHasFiles->fmv_id = $fmv->fmv_id;
                        $fmvHasFiles->filename = $fileInfo;
                        $fmvHasFiles->fileType = $uploadFile->getClientOriginalExtension();
                        $fmvHasFiles->logs = 'File uploaded';
                        $fmvHasFiles->status = true;
                        $fmvHasFiles->save();

                    }

                }

                return response(['status' => true,
                    'data' => $fmv,  'message' => array('FMV was added successfully')], 200);



            } catch (Exception $e ) {

                if ($e instanceof ModelNotFoundException) {
                    Log::error('Model Exception : ' . $e->getMessage());
                    return response([ 'status' => false, 'errors' => array('error' => 'No Entry matched for Model ' . str_replace('App\v2\\', '', $e->getModel()), 'value' => $e->getIds())], 400);

                } elseif ($e instanceof QueryException) {
                    Log::error('Query Exception : ' . $e->getMessage());
                    return response([ 'status' => false, 'errors' => array('error' => 'Data save exception. Please contact administrator')], 500);

                } else {
                    Log::error('Unknown Exception : '. $e->getMessage());
                    return response(['status' => false ,'errors' => array('error' => 'Something went wrong')], 500);

                }
            }
        }

    }


}
