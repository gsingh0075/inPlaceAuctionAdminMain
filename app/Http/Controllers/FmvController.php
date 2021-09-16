<?php

namespace App\Http\Controllers;

use App\Mail\SendFmv;
use App\Models\Clients;
use App\Models\Fmv;
use App\Models\FmvHasFiles;
use App\Models\FmvHasItems;
use App\Models\User;
use App\Notifications\NewFmvNotification;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class FmvController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Get All the FMV
    public function get() {

        $archiveYear =  Carbon::now()->subYears(2)->year;
        //Log::info($archiveYear);

        $fmv = Fmv::with(['user','client','items'])->whereYear('request_date', '>=' ,$archiveYear)->orderBy('request_date','desc')->get();
        //Log::info($fmv);

        return view('fmv.list',['fmv'=> $fmv,'archiveYear' => $archiveYear, 'operator' => '>=']);
    }

    public function getArchiveFMV(){

        $archiveYear =  Carbon::now()->subYears(2)->year;
        //Log::info($archiveYear);
        $fmv = Fmv::with(['user','client','items'])->whereYear('request_date', '<=' ,$archiveYear)->orderBy('request_date','desc')->get();
        //Log::info($fmv);

        return view('fmv.list',['fmv'=> $fmv, 'archiveYear' => $archiveYear, 'operator' => '<=']);

    }

    // Add new FMV
    public function addForm(){

        return view('fmv.add',[
                         'users' => User::orderBy('name')->get(),
                         'clients' => Clients::orderBy('FIRSTNAME', 'asc')->get()
                   ]);

    }

    // Delete FMV
    public function deleteFmv($id){

        $fmvData = Fmv::with(['user','client','items','files'])->findorfail($id);

        $fmvData->items()->delete();
        $fmvData->files()->delete();
        $fmvData->delete();

        return response([ 'status'=>true, 'data' => 'Successfully deleted'], 200);

    }
    // Edit FMV
    public function show($id) {

        $fmv = Fmv::with(['files','items'])->findorfail($id);
        //Log::info($fmv);
        return view('fmv.edit',[
                         'fmv' => $fmv,
                         'users' => User::orderBy('name')->get(),
                         'clients' => Clients::orderBy('FIRSTNAME', 'asc')->get()
                   ]);

    }

    // Show Item FMV
    public function showItem($id) {

        $item = FmvHasItems::findorfail($id);
        return response()->json(['status' => true, 'html' => View('fmv.editItem', compact('item'))->render()]);

    }


    // Generate PDF
    public function generatePDF($id){

        $fmv = Fmv::with(['files','items'])->findorfail($id);

        $fmvPdf = PDF::loadView('fmv.fmvPdf', ['fmv' => $fmv]);
        return $fmvPdf->download($fmv->fmv_id.'.pdf');

    }

    // Send FMV via Email
    public function sendFmv($id){

        $fmv = Fmv::findorfail($id);

        if(isset($fmv->request_by_email) && !empty($fmv->request_by_email)) {

            /*if(isset($fmv->request_by_cc) && !empty($fmv->request_by_cc)){
               Mail::to($fmv->request_by_email)->cc([$fmv->request_by_cc,'ecastagna@inplaceauction.com','arizzo@inplaceauction.com'])->send(new SendFmv($fmv));
            } else {
               Mail::to($fmv->request_by_email)->cc(['ecastagna@inplaceauction.com','arizzo@inplaceauction.com'])->send(new SendFmv($fmv));
            }*/

            Mail::to('ecastagna@inplaceauction.com')->cc(['info@qwikexperts.com','arizzo@inplaceauction.com'])->send(new SendFmv($fmv));
      
            $fmv->update(['sent_date' => Carbon::now()->format('Y-m-d H:i:s')]);
            return response(['status' => true, 'message' => array('Email sent successfully')], 200);
        } else {
            return response(['status' => false, 'message' => array('Missing the request by email address')], 200);
        }

    }

    // delete File
    public function deleteFile($id) {

        try{

            $fmvFile = FmvHasFiles::findorfail($id);
            Storage::disk('gcs')->delete($fmvFile->filename);
            $fmvFile->delete();

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

    // Update FMV Item
    public function updateItem( Request $request) {


        $validator = \Validator::make(
            array(
                'fmv_item_id' => $request->get('fmv_item_id'),
                'orig_amt' => $request->get('orig_amt'),
                'low_fmv_estimate' => $request->get('low_fmv_estimate'),
                'mid_fmv_estimate' => $request->get('mid_fmv_estimate'),
                'high_fmv_estimate' => $request->get('high_fmv_estimate'),
                'cost_of_recovery_low' => $request->get('cost_of_recovery_low'),
                'cost_of_recovery_high' => $request->get('cost_of_recovery_high'),
                'item_description' => $request->get('item_description'),
                'equip_year' => $request->get('equip_year'),
                'make' => $request->get('make'),
                'model' => $request->get('model'),
                'serial_nmber' => $request->get('ser_nmbr'),
                'equip_address' => $request->get('equip_address'),
                'equip_city' => $request->get('equip_city'),
                'equip_state' => $request->get('equip_state'),
                'equip_zip' => $request->get('equip_zip'),
            ),
            array(
                'fmv_item_id' => 'required',
                'orig_amt' => 'required|int',
                'low_fmv_estimate' => 'required|int',
                'mid_fmv_estimate' => 'required|int',
                'high_fmv_estimate' => 'required|int',
                'cost_of_recovery_low' => 'required|int',
                'cost_of_recovery_high' => 'required|int',
                'item_description' => 'nullable',
                'equip_year' => 'nullable',
                'make' => 'nullable',
                'model' => 'nullable',
                'ser_nmbr' => 'nullable',
                'equip_address' => 'nullable',
                'equip_city' => 'nullable',
                'equip_state' => 'nullable',
                'equip_zip' => 'nullable',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $fmv_item_id = $request->get('fmv_item_id');
                $original_amount = $request->get('orig_amt');
                $low_fmv_estimate = $request->get('low_fmv_estimate');
                $mid_fmv_estimate = $request->get('mid_fmv_estimate');
                $high_fmv_estimate = $request->get('high_fmv_estimate');
                $cost_of_recovery_low = $request->get('cost_of_recovery_low');
                $cost_of_recovery_high = $request->get('cost_of_recovery_high');
                $item_description = $request->get('item_description');
                $equip_year = $request->get('equip_year');
                $make = $request->get('make');
                $model = $request->get('model');
                $serial_number = $request->get('ser_nmbr');
                $equip_address = $request->get('equip_address');
                $equip_city = $request->get('equip_city');
                $equip_state = $request->get('equip_state');
                $equip_zip = $request->get('equip_zip');

                $fmvItem = FmvHasItems::findorfail($fmv_item_id);
                $fmvItem->orig_amt = $original_amount;
                $fmvItem->low_fmv_estimate = $low_fmv_estimate;
                $fmvItem->mid_fmv_estimate = $mid_fmv_estimate;
                $fmvItem->high_fmv_estimate = $high_fmv_estimate;
                $fmvItem->cost_of_recovery_low  = $cost_of_recovery_low;
                $fmvItem->cost_of_recovery_high = $cost_of_recovery_high;
                $fmvItem->item_description = $item_description;
                $fmvItem->equip_year = $equip_year;
                $fmvItem->make = $make;
                $fmvItem->model = $model;
                $fmvItem->ser_nmbr = $serial_number;
                $fmvItem->equip_address = $equip_address;
                $fmvItem->equip_city = $equip_city;
                $fmvItem->equip_state = $equip_state;
                $fmvItem->equip_zip = $equip_zip;
                $fmvItem->save();

                return response(['status' => true,
                    'data' => $fmvItem, 'message' => array('FMV item was updated successfully')], 200);



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

    // Add Fmv Item
    public function addItem( Request $request) {


        $validator = \Validator::make(
            array(
                'fmv_id' => $request->get('fmv_id'),
                'orig_amt' => $request->get('orig_amt'),
                'low_fmv_estimate' => $request->get('low_fmv_estimate'),
                'mid_fmv_estimate' => $request->get('mid_fmv_estimate'),
                'high_fmv_estimate' => $request->get('high_fmv_estimate'),
                'cost_of_recovery_low' => $request->get('cost_of_recovery_low'),
                'cost_of_recovery_high' => $request->get('cost_of_recovery_high'),
                'item_description' => $request->get('item_description'),
                'equip_year' => $request->get('equip_year'),
                'make' => $request->get('make'),
                'model' => $request->get('model'),
                'serial_nmber' => $request->get('ser_nmbr'),
                'equip_address' => $request->get('equip_address'),
                'equip_city' => $request->get('equip_city'),
                'equip_state' => $request->get('equip_state'),
                'equip_zip' => $request->get('equip_zip'),
            ),
            array(
                'fmv_id' => 'required',
                'orig_amt' => 'required|int',
                'low_fmv_estimate' => 'required|int',
                'mid_fmv_estimate' => 'required|int',
                'high_fmv_estimate' => 'required|int',
                'cost_of_recovery_low' => 'required|int',
                'cost_of_recovery_high' => 'required|int',
                'item_description' => 'nullable',
                'equip_year' => 'nullable',
                'make' => 'nullable',
                'model' => 'nullable',
                'ser_nmbr' => 'nullable',
                'equip_address' => 'nullable',
                'equip_city' => 'nullable',
                'equip_state' => 'nullable',
                'equip_zip' => 'nullable',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $fmv_id = $request->get('fmv_id');
                $original_amount = $request->get('orig_amt');
                $low_fmv_estimate = $request->get('low_fmv_estimate');
                $mid_fmv_estimate = $request->get('mid_fmv_estimate');
                $high_fmv_estimate = $request->get('high_fmv_estimate');
                $cost_of_recovery_low = $request->get('cost_of_recovery_low');
                $cost_of_recovery_high = $request->get('cost_of_recovery_high');
                $item_description = $request->get('item_description');
                $equip_year = $request->get('equip_year');
                $make = $request->get('make');
                $model = $request->get('model');
                $serial_number = $request->get('ser_nmbr');
                $equip_address = $request->get('equip_address');
                $equip_city = $request->get('equip_city');
                $equip_state = $request->get('equip_state');
                $equip_zip = $request->get('equip_zip');

                $fmvItem = new FmvHasItems();
                $fmvItem->fmv_id = $fmv_id;
                $fmvItem->orig_amt = $original_amount;
                $fmvItem->low_fmv_estimate = $low_fmv_estimate;
                $fmvItem->mid_fmv_estimate = $mid_fmv_estimate;
                $fmvItem->high_fmv_estimate = $high_fmv_estimate;
                $fmvItem->cost_of_recovery_low  = $cost_of_recovery_low;
                $fmvItem->cost_of_recovery_high = $cost_of_recovery_high;
                $fmvItem->item_description = $item_description;
                $fmvItem->equip_year = $equip_year;
                $fmvItem->make = $make;
                $fmvItem->model = $model;
                $fmvItem->ser_nmbr = $serial_number;
                $fmvItem->equip_address = $equip_address;
                $fmvItem->equip_city = $equip_city;
                $fmvItem->equip_state = $equip_state;
                $fmvItem->equip_zip = $equip_zip;
                $fmvItem->save();

                return response(['status' => true,
                                 'data' => $fmvItem, 'message' => array('FMV item was added successfully')], 200);



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

    // Delete FMV Item
    public function deleteItem($id){

        try{

            $fmvItem = FmvHasItems::findorfail($id);
            $fmvItem->delete();

            return response(['status' => true, 'message' => array('Item successfully removed')], 200);


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

    // Ajax request to Update FMV Data
    public function updateFmv( Request $request)
    {

        $validator = \Validator::make(
            array(
                'fmv_id' => $request->get('fmv_id'),
                'priority' => $request->get('priority'),
                'request_date' => $request->get('request_date'),
                'assessor' => $request->get('evaluator_admin_id'),
                'reason' => $request->get('reason'),
                'return_address' => $request->get('return_address'),
                'client' => $request->get('client_id'),
                'special_instructions' => $request->get('special_instructions'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'cc_email' => $request->get('cc_email'),
                'phone' => $request->get('phone'),
                'lease_number' => $request->get('lease_number'),
                'company_name' => $request->get('company_name'),
                'lease_name' => $request->get('lease_name'),
                'additional_comments' => $request->get('additional_comments'),
                'private_comments' => $request->get('private_comments')
            ),
            array(
                'fmv_id' => 'required',
                'priority' => 'required',
                'request_date' => 'required',
                'assessor' => 'required',
                'reason' => 'required',
                'return_address' => 'nullable',
                'client' => 'required',
                'special_instructions' => 'nullable',
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'email' => 'nullable|email',
                'cc_email' => 'nullable|email',
                'phone' => 'nullable',
                'lease_number' => 'nullable',
                'company_name' => 'nullable',
                'lease_name' => 'nullable',
                'additional_comments' => 'nullable',
                'private_comments' => 'nullable',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $fmv_id = $request->get('fmv_id');
                $priority = $request->get('priority');
                $request_date = $request->get('request_date');
                $assessor = $request->get('evaluator_admin_id');
                $reason = $request->get('reason');
                $return_address = $request->get('return_address');
                $client = $request->get('client_id');
                $special_instructions = $request->get('special_instructions');
                $first_name = $request->get('first_name');
                $last_name = $request->get('last_name');
                $email = $request->get('email');
                $cc_email = $request->get('cc_email');
                $phone = $request->get('phone');
                $lease_number = $request->get('lease_number');
                $company_name = $request->get('company_name');
                $lease_name = $request->get('lease_name');
                $additional_comments = $request->get('additional_comments');
                $private_comments = $request->get('private_comments');
                $fmvFiles = $request->file('file'); // Attached files

                $fmv = Fmv::findorfail($fmv_id);
                $fmv->priority = $priority;
                //$fmv->cdate = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->mdate = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->comments = $additional_comments;
                $fmv->lease_number = $lease_number;
                $fmv->request_date = Carbon::createFromFormat('j F, Y', $request_date)->format('Y-m-d H:i:s');
                $fmv->request_by_lastname = $last_name;
                $fmv->request_by_firstname = $first_name;
                $fmv->request_by_email = $email;
                $fmv->request_by_phone = $phone;
                $fmv->request_by_cc = $cc_email;
                $fmv->admin_id = $assessor;
                $fmv->debtor_full_name = $lease_name;
                $fmv->debtor_company = $company_name;
                $fmv->reason_for_fmv = $reason;
                $fmv->client_id = $client;
                $fmv->return_address = $return_address;
                $fmv->private_comments = $private_comments;
                $fmv->special_instructions = $special_instructions;
                //$fmv->placed_by = Auth::user()->name;
                //$fmv->placed_by_admin_id = Auth::id();
                //$fmv->generated_date = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->save();

                if (isset($fmvFiles) && !empty($fmvFiles)) {


                    foreach ($fmvFiles as $uploadFile) {

                        $fileOriginalName = trim($uploadFile->getClientOriginalName());
                        $fileOriginalName = str_replace(" ", "_", $fileOriginalName);
                        $fileInfo  = Storage::disk('gcs')->put('fmv/'.$fmv->fmv_id, $uploadFile);
                        Storage::disk('gcs')->setVisibility($fileInfo, 'public');
                        $fmvHasFiles = new FmvHasFiles();
                        $fmvHasFiles->fmv_id = $fmv->fmv_id;
                        $fmvHasFiles->filename = $fileInfo;
                        $fmvHasFiles->fileType = $uploadFile->getClientOriginalExtension();
                        $fmvHasFiles->logs = $fileOriginalName;
                        $fmvHasFiles->status = true;
                        $fmvHasFiles->save();

                    }

                }

                return response(['status' => true,
                    'data' => $fmv, 'message' => array('FMV was Updated successfully')], 200);


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

    // Function Add Files
    public function addFiles(Request $request){


        $validator = \Validator::make(
            array(
                'fmv_id' => $request->get('fmv_id'),
                'file' => $request->file('file')
            ),
            array(
                'fmv_id' => 'required',
                'file' => 'required'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try {

                $fmvId = $request->get('fmv_id');
                $fmvFiles = $request->file('file'); // Attached files

                foreach ($fmvFiles as $uploadFile) {

                    $fileOriginalName = trim($uploadFile->getClientOriginalName());
                    $fileOriginalName = str_replace(" ", "_", $fileOriginalName);
                    $fileInfo  = Storage::disk('gcs')->put('fmv/'.$fmvId, $uploadFile);
                    Storage::disk('gcs')->setVisibility($fileInfo, 'public');
                    $fmvHasFiles = new FmvHasFiles();
                    $fmvHasFiles->fmv_id = $fmvId;
                    $fmvHasFiles->filename = $fileInfo;
                    $fmvHasFiles->fileType = $uploadFile->getClientOriginalExtension();
                    $fmvHasFiles->logs = $fileOriginalName;
                    $fmvHasFiles->status = true;
                    $fmvHasFiles->save();

                }
                return response(['status' => true, 'message' => array('File was added successfully')], 200);


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


    // Ajax request to Add FMV Data
    public function addFmv( Request $request){

        //Log::info($request);
        $validator = \Validator::make(
            array(
                'priority'  => $request->get('priority'),
                'request_date' => $request->get('request_date'),
                'assessor' => $request->get('evaluator_admin_id'),
                'reason'  => $request->get('reason'),
                //'return_address' => $request->get('return_address'),
                'client' => $request->get('client_id'),
                'special_instructions' => $request->get('special_instructions'),
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email'  => $request->get('email'),
                'cc_email' => $request->get('cc_email'),
                'phone' => $request->get('phone'),
                'lease_number' => $request->get('lease_number'),
                'company_name' => $request->get('company_name'),
                'lease_name'  => $request->get('lease_name'),
                'additional_comments' => $request->get('additional_comments'),
                'private_comments' => $request->get('private_comments')
            ),
            array(
                'priority'  => 'required',
                'request_date' => 'required',
                'assessor' => 'required',
                'reason' => 'required',
                //'return_address' => 'nullable',
                'client' => 'required',
                'special_instructions' => 'nullable',
                'first_name' => 'nullable',
                'last_name' => 'nullable',
                'email' => 'nullable|email',
                'cc_email' => 'nullable|email',
                'phone' => 'nullable',
                'lease_number' => 'nullable',
                'company_name' => 'nullable',
                'lease_name'  => 'nullable',
                'additional_comments' => 'nullable',
                'private_comments' => 'nullable',
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                $priority = $request->get('priority');
                $request_date = $request->get('request_date');
                $assessor = $request->get('evaluator_admin_id');
                $reason = $request->get('reason');
                //$return_address = $request->get('return_address');
                $client = $request->get('client_id');
                $special_instructions = $request->get('special_instructions');
                $first_name = $request->get('first_name');
                $last_name = $request->get('last_name');
                $email = $request->get('email');
                $cc_email = $request->get('cc_email');
                $phone = $request->get('phone');
                $lease_number = $request->get('lease_number');
                $company_name = $request->get('company_name');
                $lease_name = $request->get('lease_name');
                $additional_comments = $request->get('additional_comments');
                $private_comments = $request->get('private_comments');
                $fmvFiles = $request->file('file'); // Attached files

                $fmv = new Fmv();
                $fmv->priority = $priority;
                $fmv->cdate = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->mdate = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->comments = $additional_comments;
                $fmv->lease_number = $lease_number;
                $fmv->request_date = Carbon::createFromFormat('j F, Y', $request_date)->format('Y-m-d H:i:s');
                $fmv->request_by_lastname = $last_name;
                $fmv->request_by_firstname = $first_name;
                $fmv->request_by_email = $email;
                $fmv->request_by_phone = $phone;
                $fmv->request_by_cc =  $cc_email;
                $fmv->admin_id = $assessor;
                $fmv->debtor_full_name = $lease_name;
                $fmv->debtor_company = $company_name;
                $fmv->reason_for_fmv = $reason;
                $fmv->client_id = $client;
                //$fmv->return_address = $return_address;
                $fmv->private_comments = $private_comments;
                $fmv->placed_by = Auth::user()->name;
                $fmv->placed_by_admin_id = Auth::id();
                $fmv->generated_date = Carbon::now()->format('Y-m-d H:i:s');
                $fmv->special_instructions = $special_instructions;
                $fmv->save();

                /* Lets Send Notification as well to other new FMV added */
                $admins = User::all();
                $client = Clients::where('CLIENT_ID',$client)->first();
                Notification::send($admins, new NewFmvNotification($fmv,$client, Auth::user()->name, 'New FMV added'));

                if(isset($fmvFiles) && !empty($fmvFiles)){

                    foreach($fmvFiles as $uploadFile){

                        $fileOriginalName = trim($uploadFile->getClientOriginalName());
                        $fileOriginalName = str_replace(" ", "_", $fileOriginalName);
                        $fileInfo  = Storage::disk('gcs')->put('fmv/'.$fmv->fmv_id, $uploadFile);
                        $fmvHasFiles = new FmvHasFiles();
                        $fmvHasFiles->fmv_id = $fmv->fmv_id;
                        $fmvHasFiles->filename = $fileInfo;
                        $fmvHasFiles->fileType = $uploadFile->getClientOriginalExtension();
                        $fmvHasFiles->logs = $fileOriginalName;
                        $fmvHasFiles->status = true;
                        $fmvHasFiles->save();

                    }

                }

                return response(['status' => true,
                    'data' => $fmv,  'message' => array('FMV was added successfully'), 'fmv' => $fmv], 200);



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

    // Ajax request to get Item suggestion
    public function itemSuggestion( Request $request){

        $validator = \Validator::make(
            array(
                'term'  => $request->get('term'),
            ),
            array(
                'term'  => 'required',
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try{

                $term = $request->get('term');
                //Lets Find Similar Items.
                $itemsData = FmvHasItems::orWhere('make','LIKE',$term.'%')->orWhere('model','LIKE', $term.'%')->get();
                //Log::info($itemsData);
                $suggestion = array();
                if(isset($itemsData) && !empty($itemsData)){
                    $i=0;foreach($itemsData as $item){
                        $suggestion[$i]['id'] = $item->fmv_item_id;
                        $suggestion[$i]['value'] = $item->make;
                        $suggestion[$i]['label'] = $item->make.' '.$item->equip_year.' '.$item->model.' ('.$item->low_fmv_estimate.'/'.$item->mid_fmv_estimate.'/'.$item->high_fmv_estimate.')';
                        $suggestion[$i]['model'] = $item->model;
                        $suggestion[$i]['year'] = $item->equip_year;
                        $suggestion[$i]['flv'] = number_format($item->low_fmv_estimate, 0,'','');
                        $suggestion[$i]['olv'] = number_format($item->mid_fmv_estimate,0,'','');
                        $suggestion[$i]['fmv'] = number_format($item->high_fmv_estimate,0,'','');
                        $suggestion[$i]['orig'] = number_format($item->orig_amt,0,'','');
                        $suggestion[$i]['recovery_low'] = number_format($item->cost_of_recovery_low,0,'','');
                        $suggestion[$i]['recovery_high'] = number_format($item->cost_of_recovery_high,0,'','');
                    $i++;}
                }

                return response(['status' => true, 'data' => $suggestion ], 200);

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
