<?php

namespace App\Http\Controllers;

use App\Helpers\GeocodeHelper;
use App\Models\Assignment;
use App\Models\Category;
use App\Models\ContractorAuth;
use App\Models\ContractorCategories;
use App\Models\Contractors;
use App\Models\State;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContractorController extends Controller
{

    // Authorization is required.
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // Get All the Contractors
    public function get() {

        $contractors = Contractors::orderBy('cdate','desc')->get();
        return view('contractors.list',['contractors' => $contractors]);
    }

    // Add New Contractor Form
    public function addContractorForm()
    {

        $states = State::orderBy('STATE','asc')->get();
        $profileTypes = array(
            'indoor_storage' => 'Has Indoor Storage',
            'loading_dock' => 'Has Loading Dock',
            'fenced_in_yard' => 'Has Fenced In Yard',
            'has_forklift' => 'Has a Fork Lift',
            'insurance_flag' => 'Is Insured',
            'defer_payment' => 'Will Defer Payment',
            'pickup_up_store' => 'Will Pickup and Store',
            'store_only' => 'Will Store only'
        );

        $contractor_type = array(
            'is_equipment_contractor' => 'Equipment Contractor',
            'is_appraisal_contractor' => 'Appraisal Contractor',
            'is_inspection_contractor' => 'Inspection Contractor'
        );
        $categories = Category::orderBy('category_name','asc')->where('active',1)->get();


        return view('contractors.add', ['states' => $states, 'profile' => $profileTypes, 'categories' => $categories,'contractor_type' => $contractor_type]);
    }

    // Add New Contractor
    public function addNewContractor( Request $request ){


        $validator = \Validator::make(
            array(
                'first_name'  => $request->input('first_name'),
                'last_name'   => $request->input('last_name'),
                'phone'       => $request->input('phone'),
                'cell'        => $request->input('cell'),
                'fax'         => $request->input('fax'),
                'email'       => $request->input('email'),
                'company'     => $request->input('company'),
                'address'     => $request->input('address'),
                'city'        => $request->input('city'),
                'state'       => $request->input('states'),
                'zip'         => $request->input('zip'),
                'approved'    => $request->input('approved'),
                'active'      => $request->input('active'),
                'notes'       => $request->input('notes'),
                'indoor_storage'   => $request->input('indoor_storage'),
                'loading_dock'     => $request->input('loading_dock'),
                'fenced_in_yard'   => $request->input('fenced_in_yard'),
                'has_forklift'     => $request->input('has_forklift'),
                'insurance_flag'   => $request->input('insurance_flag'),
                'defer_payment'    => $request->input('defer_payment'),
                'pickup_up_store'  => $request->input('pickup_up_store'),
                'store_only'       => $request->input('store_only'),
                'rating_criteria'  => $request->input('rating_criteria'),
                'rating_comments'  => $request->input('rating_comments'),
                'area_interest'    => $request->input('area_interest[]'),
                'main_category'    => $request->input('main_category'),
                'coverage_territory' => $request->input('coverage_territory'),
                'is_equipment_contractor' => $request->input('is_equipment_contractor'),
                'is_appraisal_contractor' => $request->input('is_appraisal_contractor'),
                'is_inspection_contractor' => $request->input('is_inspection_contractor')

            ),
            array(
                'first_name'  => 'required',
                'last_name'   => 'required',
                'phone'       => 'required',
                'cell'        => 'nullable',
                'fax'         => 'nullable',
                'email'       => 'required',
                'company'     => 'required',
                'address'     => 'required',
                'city'        => 'required',
                'state'       => 'required',
                'zip'         => 'required',
                'approved'    => 'required',
                'active'      => 'required',
                'notes'       => 'nullable',
                'indoor_storage' => 'nullable',
                'loading_dock'   => 'nullable',
                'fenced_in_yard' => 'nullable',
                'has_forklift'   => 'nullable',
                'insurance_flag' => 'nullable',
                'defer_payment'  => 'nullable',
                'pickup_up_store' => 'nullable',
                'store_only'      => 'nullable',
                'rating_criteria' => 'nullable',
                'rating_comments' => 'nullable',
                'area_interest'   => 'nullable',
                'main_category'   => 'nullable',
                'coverage_territory' => 'nullable',
                'is_equipment_contractor' => 'nullable',
                'is_appraisal_contractor' => 'nullable',
                'is_inspection_contractor' => 'nullable'

            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                //Log::info($request);

                $first_name = $request->input('first_name');
                $last_name = $request->input('last_name');
                $phone = $request->input('phone');
                $cell = $request->input('cell');
                $fax = $request->input('fax');
                $email = $request->input('email');
                $company =  $request->input('company');
                $address =  $request->input('address');
                $city  = $request->input('city');
                $state = $request->input('states');
                $zip = $request->input('zip');
                $approved =  $request->input('approved');
                $active = $request->input('active');
                $notes =  $request->input('notes');
                $indoor_storage = $request->input('indoor_storage');
                $loading_dock = $request->input('loading_dock');
                $fenced_in_yard = $request->input('fenced_in_yard');
                $has_forklift = $request->input('has_forklift');
                $insurance_flag = $request->input('insurance_flag');
                $defer_payment = $request->input('defer_payment');
                $pickup_up_store = $request->input('pickup_up_store');
                $store_only = $request->input('store_only');
                $rating_criteria = $request->input('rating_criteria');
                $rating_comments = $request->input('rating_comments');
                $area_interest = $request->input('area_interest[]');
                $main_category = $request->input('main_category');
                $coverage_territory = $request->input('coverage_territory');
                $is_equipment_contractor = $request->input('is_equipment_contractor');
                $is_appraisal_contractor = $request->input('is_appraisal_contractor');
                $is_inspection_contractor = $request->input('is_inspection_contractor');

                if(!isset($is_appraisal_contractor)) $is_appraisal_contractor = 0;
                if(!isset($is_equipment_contractor)) $is_equipment_contractor = 0;
                if(!isset($is_inspection_contractor)) $is_inspection_contractor = 0;

                $contractor = new Contractors();
                $contractor->first_name = $first_name;
                $contractor->last_name =  $last_name;
                $contractor->company = $company;
                $contractor->address1 = $address;
                $contractor->city = $city;
                $contractor->state = $state;
                $contractor->zip1 = $zip;
                $contractor->phone = $phone;
                $contractor->fax = $fax;
                $contractor->email = $email;
                $contractor->insurance_flag = $insurance_flag;
                $contractor->active = $active;
                $contractor->use_contractor =  1;
                $contractor->approved = $approved;
                $contractor->notes = $notes;
                $contractor->indoor_storage = $indoor_storage;
                $contractor->loading_dock = $loading_dock;
                $contractor->fenced_in_yard = $fenced_in_yard;
                $contractor->pick_up_and_store =$pickup_up_store;
                $contractor->store_only = $store_only;
                $contractor->has_forklift = $has_forklift;
                $contractor->rating_comments = $rating_comments;
                $contractor->rating_quality = $rating_criteria;
                $contractor->defer_payment_agree =  $defer_payment;
                $contractor->main_category_id = $main_category;
                $contractor->coverage_territory = $coverage_territory;
                $contractor->is_equipment_contractor = $is_equipment_contractor;
                $contractor->is_appraisal_contractor = $is_appraisal_contractor;
                $contractor->is_inspection_contractor = $is_inspection_contractor;
                $contractor->cdate = Carbon::now()->format('Y-m-d H:i:s');
                $contractor->save();

                // Let save contractor extra category

                if(isset($area_interest) && !empty($area_interest)) {

                    foreach( $area_interest as $aIn){
                        $contractorCategory =  new ContractorCategories();
                        $contractorCategory->Category_id = $aIn;
                        $contractorCategory->Contractor_ID = $contractor->contractor_id;
                        $contractorCategory->save();
                    }
                }

                // Lets get Geo as well.

                $contractorAddress = $address.' '.$city.' '.$state.' '.$zip;
                $address= urlencode($contractorAddress);

                $geoCode = new GeocodeHelper();
                $LatLng = $geoCode->geocode($address);

                //Log::info($LatLng);
                $contractorLat = '';
                $contractorLng = '';

                if( (isset($LatLng['latitude']) && !empty($LatLng['longitude'])) && ( isset($LatLng['longitude']) && !empty($LatLng['longitude']) ) ) {
                    $contractorLat = $LatLng['latitude'];
                    $contractorLng = $LatLng['longitude'];
                }
                // Lets Save Lat Long the username Password.
                $contractor->update([
                    'username' => 'CON'. $contractor->contractor_id. 'IPA',
                    'password' => rand(1000, 50000),
                    'lat' => $contractorLat,
                    'lng' => $contractorLng
                ]);

                return response(['status' => true , 'message' => array('Contractor created successfully'), 'contractor' => $contractor ], 200);


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

    // Update Notification Contractor
    public function updateContractorInvoiceNotification( Request $request){

        $validator = \Validator::make(
            array(
                'contractor_id' => $request->get('contractor_id'),
                'notification' => $request->get('notification'),
            ),
            array(
                'contractor_id' => 'required',
                'notification'  => 'required',
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                $contractor_id = $request->get('contractor_id');
                $notification =  $request->get('notification');
                $contractor = Contractors::findorfail($contractor_id);
                $contractor->invoice_email =  $notification;
                $contractor->save();

                return response(['status' => true,
                    'data' => $contractor,  'message' => array('Contractor was updated successfully')], 200);



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
    // Get Contractor Authorizations.
    public function getAuthorizations(){

        $contractorAuth = ContractorAuth::with(['contractor','authItems.item.assignment'])->orderBy('sent_date','desc')->get();
        return view('contractors.authList',['contractorAuth' => $contractorAuth]);

    }

    // Edit Contractor
    public function editContractorForm($id){


        $states = State::orderBy('STATE','asc')->get();
        $profileTypes = array(
            'indoor_storage' => 'Has Indoor Storage',
            'loading_dock' => 'Has Loading Dock',
            'fenced_in_yard' => 'Has Fenced In Yard',
            'has_forklift' => 'Has a Fork Lift',
            'insurance_flag' => 'Is Insured',
            'defer_payment' => 'Will Defer Payment',
            'pickup_up_store' => 'Will Pickup and Store',
            'store_only' => 'Will Store only'
        );

        $contractor_type = array(
            'is_equipment_contractor' => 'Equipment Contractor',
            'is_appraisal_contractor' => 'Appraisal Contractor',
            'is_inspection_contractor' => 'Inspection Contractor'
        );

        $categories = Category::orderBy('category_name','asc')->where('active',1)->get();

        $contractor = Contractors::with(['contractorCategories'])->findorfail($id);

        return view('contractors.edit', ['contractor'=> $contractor, 'states' => $states, 'profile' => $profileTypes, 'categories' => $categories, 'contractor_type' => $contractor_type]);

    }

    // Update Contractor
    public function updateContractor( Request $request){


        $validator = \Validator::make(
            array(
                'contractor_id' => $request->input('contractor_id'),
                'first_name'  => $request->input('first_name'),
                'last_name'   => $request->input('last_name'),
                'phone'       => $request->input('phone'),
                'cell'        => $request->input('cell'),
                'fax'         => $request->input('fax'),
                'email'       => $request->input('email'),
                'company'     => $request->input('company'),
                'address'     => $request->input('address'),
                'city'        => $request->input('city'),
                'state'       => $request->input('states'),
                'zip'         => $request->input('zip'),
                'approved'    => $request->input('approved'),
                'active'      => $request->input('active'),
                'notes'       => $request->input('notes'),
                'indoor_storage'   => $request->input('indoor_storage'),
                'loading_dock'     => $request->input('loading_dock'),
                'fenced_in_yard'   => $request->input('fenced_in_yard'),
                'has_forklift'     => $request->input('has_forklift'),
                'insurance_flag'   => $request->input('insurance_flag'),
                'defer_payment'    => $request->input('defer_payment'),
                'pickup_up_store'  => $request->input('pickup_up_store'),
                'store_only'       => $request->input('store_only'),
                'rating_criteria'  => $request->input('rating_criteria'),
                'rating_comments'  => $request->input('rating_comments'),
                'area_interest'    => $request->input('area_interest'),
                'main_category'    => $request->input('main_category'),
                'coverage_territory' => $request->input('coverage_territory'),
                'is_equipment_contractor' => $request->input('is_equipment_contractor'),
                'is_appraisal_contractor' => $request->input('is_appraisal_contractor'),
                'is_inspection_contractor' => $request->input('is_inspection_contractor')

            ),
            array(
                'contractor_id' => 'required',
                'first_name'  => 'required',
                'last_name'   => 'required',
                'phone'       => 'required',
                'cell'        => 'nullable',
                'fax'         => 'nullable',
                'email'       => 'required',
                'company'     => 'required',
                'address'     => 'required',
                'city'        => 'required',
                'state'       => 'required',
                'zip'         => 'required',
                'approved'    => 'required',
                'active'      => 'required',
                'notes'       => 'nullable',
                'indoor_storage' => 'nullable',
                'loading_dock'   => 'nullable',
                'fenced_in_yard' => 'nullable',
                'has_forklift'   => 'nullable',
                'insurance_flag' => 'nullable',
                'defer_payment'  => 'nullable',
                'pickup_up_store' => 'nullable',
                'store_only'      => 'nullable',
                'rating_criteria' => 'nullable',
                'rating_comments' => 'nullable',
                'area_interest'   => 'nullable',
                'main_category'   => 'nullable',
                'coverage_territory' => 'nullable',
                'is_equipment_contractor' => 'nullable',
                'is_appraisal_contractor' => 'nullable',
                'is_inspection_contractor' => 'nullable'

            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                //Log::info($request);
                $contractor_id = $request->input('contractor_id');
                $first_name = $request->input('first_name');
                $last_name = $request->input('last_name');
                $phone = $request->input('phone');
                $cell = $request->input('cell');
                $fax = $request->input('fax');
                $email = $request->input('email');
                $company =  $request->input('company');
                $address =  $request->input('address');
                $city  = $request->input('city');
                $state = $request->input('states');
                $zip = $request->input('zip');
                $approved =  $request->input('approved');
                $active = $request->input('active');
                $notes =  $request->input('notes');
                $indoor_storage = $request->input('indoor_storage');
                $loading_dock = $request->input('loading_dock');
                $fenced_in_yard = $request->input('fenced_in_yard');
                $has_forklift = $request->input('has_forklift');
                $insurance_flag = $request->input('insurance_flag');
                $defer_payment = $request->input('defer_payment');
                $pickup_up_store = $request->input('pickup_up_store');
                $store_only = $request->input('store_only');
                $rating_criteria = $request->input('rating_criteria');
                $rating_comments = $request->input('rating_comments');
                $area_interest = $request->input('area_interest');
                $main_category = $request->input('main_category');
                $coverage_territory = $request->input('coverage_territory');
                $is_equipment_contractor = $request->input('is_equipment_contractor');
                $is_appraisal_contractor = $request->input('is_appraisal_contractor');
                $is_inspection_contractor = $request->input('is_inspection_contractor');

                //Log::info($area_interest);

                if(!isset($is_appraisal_contractor)) $is_appraisal_contractor = 0;
                if(!isset($is_equipment_contractor)) $is_equipment_contractor = 0;
                if(!isset($is_inspection_contractor)) $is_inspection_contractor = 0;

                $contractor = Contractors::findorfail($contractor_id);
                $contractor->first_name = $first_name;
                $contractor->last_name =  $last_name;
                $contractor->company = $company;
                $contractor->address1 = $address;
                $contractor->city = $city;
                $contractor->state = $state;
                $contractor->zip1 = $zip;
                $contractor->phone = $phone;
                $contractor->cell = $cell;
                $contractor->fax = $fax;
                $contractor->email = $email;
                $contractor->insurance_flag = $insurance_flag;
                $contractor->active = $active;
                $contractor->use_contractor =  1;
                $contractor->approved = $approved;
                $contractor->notes = $notes;
                $contractor->indoor_storage = $indoor_storage;
                $contractor->loading_dock = $loading_dock;
                $contractor->fenced_in_yard = $fenced_in_yard;
                $contractor->pick_up_and_store =$pickup_up_store;
                $contractor->store_only = $store_only;
                $contractor->has_forklift = $has_forklift;
                $contractor->rating_comments = $rating_comments;
                $contractor->rating_quality = $rating_criteria;
                $contractor->defer_payment_agree =  $defer_payment;
                $contractor->main_category_id = $main_category;
                $contractor->coverage_territory = $coverage_territory;
                $contractor->is_equipment_contractor = $is_equipment_contractor;
                $contractor->is_appraisal_contractor = $is_appraisal_contractor;
                $contractor->is_inspection_contractor = $is_inspection_contractor;
                $contractor->save();

                // Let save contractor extra category

                if(isset($area_interest) && !empty($area_interest)) {

                    foreach( $area_interest as $aIn){
                        ContractorCategories::updateOrCreate([
                            'Category_id' => $aIn,
                            'Contractor_ID' => $contractor->contractor_id
                        ]);
                    }
                }

                return response(['status' => true , 'message' => array('Contractor updated successfully'), 'contractor' => $contractor ], 200);


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
    public function findContractors( Request $request ){

        $validator = \Validator::make(
            array(
                'zipcode' => $request->get('zipcode'),
                'lat' => $request->input('lat'),
                'lng' => $request->input('lng')
            ),
            array(
                'zipcode' => 'nullable',
                'lat' => 'required',
                'lng' => 'required'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $zipcode = $request->get('zipcode');
                $dataContractors = array();
                $stateFilter = array();
                $latFilterValue = $request->input('lat');
                $lngFilterValue = $request->input('lng');

                $contractorsQuery = Contractors::query();
                $contractorsQuery->select(DB::raw("*, ( 3959 * acos( cos( radians('$latFilterValue') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('$lngFilterValue') ) + sin( radians('$latFilterValue') ) * sin( radians( lat ) ) ) ) AS distance"))->havingRaw('distance < 200');

                $contractors = $contractorsQuery->get();

                if(isset($contractors) && !empty($contractors)){

                    $i=0;
                    foreach($contractors as $contr){

                            $contractorAddress = $contr->address1.''.$contr->state.''.$contr->city.''.$contr->zip1;

                            if(!empty($contr->lat) && !empty($contr->lng)){
                                $LatLong = array('latitude' => $contr->lat, 'longitude' => $contr->lng , 'address' => $contractorAddress );
                            } else {
                                $LatLong = array('latitude' => '', 'longitude' => '' , 'address' => $contractorAddress);
                            }
                            $dataContractors[$i]['contractor_id'] = $contr->contractor_id;
                            $dataContractors[$i]['name'] = $contr->first_name.' '.$contr->last_name;
                            // Contractor Types
                            if($contr->is_equipment_contractor == 1) $dataContractors[$i]['type']  = 'A';
                            if($contr->is_appraisal_contractor == 1) $dataContractors[$i]['type']  = 'B';
                            if($contr->is_inspection_contractor == 1) $dataContractors[$i]['type']  = 'C';

                            $dataContractors[$i]['address_code'] = $LatLong;
                            $i++;
                        }

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

    //View Contractor
    // Contractor Marker Point
    public function viewContractorMarker( Request $request ){

        $validator = \Validator::make(
            array(
                'contractor_id' => $request->get('contractor_id'),
                'lat' => $request->get('lat'),
                'lng' => $request->get('lng')
            ),
            array(
                'contractor_id' => 'required',
                'lat' => 'required',
                'lng' => 'required'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $contractor_id = $request->get('contractor_id');
                $lat = $request->get('lat');
                $lng = $request->get('lng');

                $contractor = Contractors::with(['contractorCategories.category'])->findOrFail($contractor_id);

                $data['contractor'] = $contractor;
                $data['lat'] = $lat;
                $data['lng'] = $lng;

                //Log::info($assignment);

                return response()->json(['success' => true, 'html' => View('inspection.contractorMarker', compact('data'))->render()]);


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
