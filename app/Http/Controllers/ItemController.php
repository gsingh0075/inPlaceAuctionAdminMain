<?php

namespace App\Http\Controllers;

use App\Helpers\GeocodeHelper;
use App\Models\Assignment;
use App\Models\AssignmentHasFiles;
use App\Models\Bid;
use App\Models\Category;
use App\Models\ClientInvoiceLines;
use App\Models\ClientInvoices;
use App\Models\Customer;
use App\Models\ExpenseType;
use App\Models\FmvHasFiles;
use App\Models\Invoice;
use App\Models\InvoiceHasItems;
use App\Models\ItemHasCategories;
use App\Models\ItemHasConditionReports;
use App\Models\ItemHasExpense;
use App\Models\ItemImages;
use App\Models\Items;
use App\Models\State;
use Carbon\Carbon;
use Exception;
use Google\Auth\Cache\Item;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade as PDF;
use Intervention\Image\Facades\Image;

class ItemController extends Controller
{

    protected $cacheExpires;

    public function __construct()
    {
        $this->middleware('auth')->except(['viewItemImage']);
        $this->cacheExpires = Carbon::now()->addMinutes(30);

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
    // Add Item to Assignment
    public function addForm($id) {

        $assignmentId = $id;
        Assignment::findOrFail($assignmentId);
        $categories = Category::where('active', '=', 1)->orderBy('category_name','asc')->get();
        //Log::info(State::get());
        return view('item.add',[
            'categories' => $categories,
            'assignmentId' => $assignmentId,
            'states' => State::get()
        ]);

    }

    // Edit Item
    public function editForm($id){

       $ItemId = $id;
       //$Item = Items::with(['categories.category','reports','images'])->findOrFail($ItemId);
       $Item = Items::with(['categories.category','reports'])->findOrFail($ItemId);
       $categories = Category::where('active', '=', 1)->orderBy('category_name','asc')->get();
       //Log::info($Item);
        return view('item.edit',[
            'categories' => $categories,
            'item' => $Item,
            'states' => State::get()
        ]);

    }

    // Load Item Images
    public function getItemImages($itemId){

        try {

            $itemImages = ItemImages::where('item_id',$itemId)->get();
            return response()->json(['status' => true, 'html' => View('item.editImages', compact('itemImages'))->render()]);

        }
        catch (Exception $e) {

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

    // Save Item
    public function saveItem( Request $request){

        $validator = \Validator::make(
            array(
                'assignment_id' => $request->get('assignment_id'),
                'category_ids.*'  => $request->get('category_ids.*'),
                'quantity' => $request->get('quantity'),
                'year' => $request->get('year'),
                'make'  => $request->get('make'),
                'model' => $request->get('model'),
                'serial_number' => $request->get('serial_number'),
                'unit_code' => $request->get('unit_code'),
                'additional_information' => $request->get('additional_information'),
                'missing_items'  => $request->get('missing_items'),
                'city'  => $request->get('city'),
                'state_id' => $request->get('state_id'),
                'zip' => $request->get('zip'),
                'cost' =>$request->get('cost'),
                'fmv' =>$request->get('fmv'),
                'asking_price' => $request->get('asking_price'),
                'original_sold_date' => $request->get('original_sold_date')
            ),
            array(
                'assignment_id' => 'required|int',
                'category_ids.*'  => 'required|int',
                'quantity' => 'required|int',
                'year' => 'required|int',
                'make' => 'required',
                'model' => 'required',
                'serial_number' => 'required',
                'unit_code' => 'nullable',
                'additional_information' => 'nullable',
                'missing_items' => 'nullable',
                'city' =>'required',
                'state_id' => 'required',
                'zip' => 'required',
                'cost' => 'required',
                'fmv' => 'required',
                'asking_price' => 'nullable',
                'original_sold_date' => 'nullable'
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                $assignment_id = $request->get('assignment_id');
                $category_id = $request->get('category_ids');
                $quantity = $request->get('quantity');
                $year =  $request->get('year');
                $make = $request->get('make');
                $model = $request->get('model');
                $serial_number = $request->get('serial_number');
                $unit_code = $request->get('unit_code');
                $additional_information = $request->get('additional_information');
                $missing_items = $request->get('missing_items');
                $city = $request->get('city');
                $state_id = $request->get('state_id');
                $zip = $request->get('zip');
                $cost = $request->get('cost');
                $fmv = $request->get('fmv');
                $original_sold_date = $request->get('original_sold_date');
                $asking_price = $request->get('asking_price');

                //Log::info($category_id);

                Assignment::findOrFail($assignment_id); // Make sure assignment Exits.

                // Lets Get Lat and Lng for Item as well.
                $state = State::findorfail($state_id);
                $itemAddress = $city.''.$state->STATE.''.$zip;
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

                // Lets get the Item Number.
                $itemNumberCal = Items::where('ASSIGNMENT_ID',$assignment_id)->get();

                $item = new Items();
                $item->ITEM_NMBR = count($itemNumberCal)+1;
                $item->ASSIGNMENT_ID = $assignment_id;
                $item->QUANTITY = $quantity;
                $item->ITEM_YEAR = $year;
                $item->ITEM_MAKE = $make;
                $item->ITEM_MODEL = $model;
                $item->ITEM_SERIAL = $serial_number;
                $item->ITEM_UNIT = $unit_code;
                $item->ITEM_DESC = $additional_information;
                $item->MISSING_ITEMS = $missing_items;
                $item->LOC_CITY = $city;
                $item->LOC_STATE = $state_id;
                $item->LOC_ZIP = $zip;
                $item->ORIG_COST = $cost;
                $item->lat = $itemLat;
                $item->Lng = $itemLng;
                if(!empty($original_sold_date)){
                    $item->ORIG_SOLD_DT = Carbon::createFromFormat('j F, Y', $original_sold_date)->format('Y-m-d H:i:s');
                }
                $item->ASKING_PRICE = $asking_price;
                $item->DT_STMP = Carbon::now()->format('Y-m-d H:i:s');
                $item->LST_UPDT = Carbon::now()->format('Y-m-d H:i:s');
                $item->FMV = $fmv;
                $item->save();

                //Store the Category IDs as well.
                if(isset($category_id) && !empty($category_id)){
                    foreach($category_id as $category){
                        $itemHasCategory = new ItemHasCategories();
                        $itemHasCategory->category_id = $category;
                        $itemHasCategory->item_id = $item->ITEM_ID;
                        $itemHasCategory->save();
                    }
                }


                return response(['status' => true , 'assignment_id' => $assignment_id, 'message' => array('Item was successfully added')], 200);

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

    // Updated Item
    public function updateItem( Request $request){

        //Log::info($request);
        $validator = \Validator::make(
            array(
                'item_id' => $request->get('item_id'),
                'category_ids.*'  => $request->get('category_ids.*'),
                'quantity' => $request->get('quantity'),
                'year' => $request->get('year'),
                'make'  => $request->get('make'),
                'model' => $request->get('model'),
                'serial_number' => $request->get('serial_number'),
                'unit_code' => $request->get('unit_code'),
                'additional_information' => $request->get('additional_information'),
                'missing_items'  => $request->get('missing_items'),
                'condition_report_desc'  => $request->get('condition_report_desc'),
                'city'  => $request->get('city'),
                'state_id' => $request->get('state_id'),
                'zip' => $request->get('zip'),
                'cost' =>$request->get('cost'),
                'fmv' =>$request->get('fmv'),
                'asking_price' => $request->get('asking_price'),
                'original_sold_date' => $request->get('original_sold_date'),
                'condition_code' => $request->get('condition_code'),
                'recovered_date' => $request->get('recovered_date'),
                'in_possession' => $request->get('in_possession')
            ),
            array(
                'item_id' => 'required',
                'category_ids.*'  => 'required|int',
                'quantity' => 'required|int',
                'year' => 'required|int',
                'make' => 'required',
                'model' => 'required',
                'serial_number' => 'required',
                'unit_code' => 'nullable',
                'additional_information' => 'nullable',
                'missing_items' => 'nullable',
                'condition_report_desc' => 'nullable',
                'city' =>'required',
                'state_id' => 'required',
                'zip' => 'required',
                'cost' => 'required',
                'fmv' => 'required',
                'asking_price' => 'nullable',
                'original_sold_date' => 'nullable',
                'condition_code' => 'nullable',
                'recovered_date' => 'nullable',
                'in_possession' => 'nullable'
            )
        );

        if ($validator->fails()) { return response( ['status' => false,  'errors' => $validator->messages()] , 200);
        } else {

            try {

                $itemId = $request->get('item_id');
                $category_id = $request->get('category_ids');
                $quantity = $request->get('quantity');
                $year =  $request->get('year');
                $make = $request->get('make');
                $model = $request->get('model');
                $serial_number = $request->get('serial_number');
                $unit_code = $request->get('unit_code');
                $additional_information = $request->get('additional_information');
                $missing_items = $request->get('missing_items');
                $condition_report_desc = $request->get('condition_report_desc');
                $city = $request->get('city');
                $state_id = $request->get('state_id');
                $zip = $request->get('zip');
                $cost = $request->get('cost');
                $fmv = $request->get('fmv');
                $original_sold_date = $request->get('original_sold_date');
                $asking_price = $request->get('asking_price');
                $condition_code = $request->get('condition_code');
                $recovered_date = $request->get('recovered_date');
                $in_possession = $request->get('in_possession');

                //Log::info($category_id);

                // Lets Get Lat and Lng for Item as well.
                $state = State::findorfail($state_id);
                $itemAddress = $city.''.$state->STATE.''.$zip;
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

                $item = Items::findOrFail($itemId);
                //$item->ITEM_NMBR = 1;
                $item->QUANTITY = $quantity;
                $item->ITEM_YEAR = $year;
                $item->ITEM_MAKE = $make;
                $item->ITEM_MODEL = $model;
                $item->ITEM_SERIAL = $serial_number;
                $item->ITEM_UNIT = $unit_code;
                $item->ITEM_DESC = $additional_information;
                $item->MISSING_ITEMS = $missing_items;
                $item->CLIENT_COND_RPT_DESC = $condition_report_desc;
                $item->LOC_CITY = $city;
                $item->LOC_STATE = $state_id;
                $item->LOC_ZIP = $zip;
                $item->ORIG_COST = $cost;
                $item->lat = $itemLat;
                $item->Lng = $itemLng;
                if(!empty($original_sold_date)){
                    $item->ORIG_SOLD_DT = Carbon::createFromFormat('j F, Y', $original_sold_date)->format('Y-m-d H:i:s');
                }
                $item->ASKING_PRICE = $asking_price;
                $item->DT_STMP = Carbon::now()->format('Y-m-d H:i:s');
                $item->LST_UPDT = Carbon::now()->format('Y-m-d H:i:s');
                $item->FMV = $fmv;
                if(!empty($recovered_date)){
                    $item->ITEM_RECOVERY_DT = Carbon::createFromFormat('j F, Y', $recovered_date)->format('Y-m-d H:i:s');
                }
                $item->CONDITION_CODE = $condition_code;
                $item->IN_POSSESSION = $in_possession;
                $item->save();

                //Store the Category IDs as well.
                if(isset($category_id) && !empty($category_id)){
                    // Delete the existing one
                    $existingCategories = ItemHasCategories::where('item_id',$item->ITEM_ID)->get();
                    //Log::info($existingCategories);
                    if(!empty($existingCategories)){
                        foreach($existingCategories as $exist){
                            $exit = ItemHasCategories::findOrFail($exist->category_item_xref_id);
                            $exit->delete();
                        }
                    }
                    foreach($category_id as $category){
                        $itemHasCategory = new ItemHasCategories();
                        $itemHasCategory->category_id = $category;
                        $itemHasCategory->item_id = $item->ITEM_ID;
                        $itemHasCategory->save();
                    }
                } else {
                    //Log::info('Came in no category');
                    $existingCategories = ItemHasCategories::where('item_id',$item->ITEM_ID)->get();
                    //Log::info($existingCategories);
                    if(!empty($existingCategories)){
                        foreach($existingCategories as $exist){
                            $exit = ItemHasCategories::findOrFail($exist->category_item_xref_id);
                            $exit->delete();
                        }
                    }
                }

                return response(['status' => true , 'message' => array('Item was updated')], 200);

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

    // Add Images
    public function addImages(Request $request){

        //Log::info($request);

        $validator = \Validator::make(
            array(
                'item_id' => $request->get('item_id'),
                'file' => $request->file('file')
            ),
            array(
                'item_id' => 'required',
                'file' => 'required'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try {

                $itemId = $request->get('item_id');
                $itemImages = $request->file('file'); // Attached files
                Items::findorfail($itemId);

                foreach ($itemImages as $uploadFile) {

                    $fileOriginalName = trim($uploadFile->getClientOriginalName());
                    $fileOriginalName = str_replace(" ", "_", $fileOriginalName);
                    $currentTimeStamp = Carbon::now()->timestamp;
                    $fileExtension = $uploadFile->getClientOriginalExtension();

                    // Generate Thumbnail as well.
                    $thumbnailImageDetails = Image::make($uploadFile->getRealPath());
                    $thumbnailImageDetails->resize(200,200, function($constraint){
                                          $constraint->aspectRatio();
                    })->save( storage_path('app/public/'.'sm_'.$currentTimeStamp.'_'.$fileOriginalName));

                    //IN CASE OF NEEDED STRING THE FILES AS WELL.
                    //Storage::disk('localItemReportPictures')->put('/bg_'.$currentTimeStamp.'_'.$fileOriginalName, file_get_contents($uploadFile->getRealPath()));

                    // Get Thumbnail File
                    $thumbnailInfo = Storage::disk('localItemReportPictures')->get('sm_'.$currentTimeStamp.'_'.$fileOriginalName);

                    // Names for the Images. OurUnique Name
                    $thumbnailImageName = 'sm_'.$itemId.'_'.$currentTimeStamp.'_'.uniqid().'.'.$fileExtension;
                    $mainImageName = $itemId.'_'.$currentTimeStamp.'_'.uniqid().'.'.$fileExtension;

                    // Save the Image
                    Storage::disk('gcsItems')->put('/'.$thumbnailImageName, $thumbnailInfo);
                    Storage::disk('gcsItems')->put('/'.$mainImageName, file_get_contents($uploadFile->getRealPath()));


                    $itemImages = new ItemImages();
                    $itemImages->item_id = $itemId;
                    $itemImages->big_image_file = $mainImageName;
                    $itemImages->small_image_file = $thumbnailImageName;
                    $itemImages->display_image = 1;
                    $itemImages->active = 1;
                    $itemImages->save();

                     // Delete the Thumbnail File it got uploaded. Thank you.
                    Storage::disk('localItemReportPictures')->delete( 'sm_'.$currentTimeStamp.'_'.$fileOriginalName );

                }
                return response(['status' => true, 'message' => array('Images was added successfully')], 200);


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

    public function updateConditionReportVisibilityDate(Request $request){

        $validator = \Validator::make(
            array(
                'item_condition_report_id' => $request->input('item_condition_report_id'),
                'item_condition_report_date' => $request->input('item_condition_report_date')
            ),
            array(
                'item_condition_report_id' => 'required|int',
                'item_condition_report_date' => 'required'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $item_condition_report_id = $request->input('item_condition_report_id');
                $item_condition_report_date = $request->input('item_condition_report_date');

                $itemConditionReport = ItemHasConditionReports::findorfail($item_condition_report_id);
                $itemConditionReport->generated_date = Carbon::createFromFormat('j F, Y', $item_condition_report_date)->format('Y-m-d H:i:s');
                $itemConditionReport->save();

                return response(['status' => true, 'message' => array('Date was updated successfully')], 200);

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
   // Generate Picture Report
    public function generatePictureReport(Request $request){

        $validator = \Validator::make(
            array(
                'item_image_ids' => $request->input('item_image_ids'),
                'item_id' => $request->input('item_id')
            ),
            array(
                'item_image_ids' => 'required|array',
                'item_id' => 'required|int'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try {

                $itemImageIds = $request->input('item_image_ids');
                $item_id = $request->input('item_id');

                foreach($itemImageIds as $itemImage) {
                    ItemImages::findorfail($itemImage);
                }

                //$item = Items::with(['assignment.client.clientInfo','images'])->findorfail($item_id);
                $item = Items::with(['assignment.client.clientInfo'])->findorfail($item_id);

                $toBeAddedToPdf = array();
                $tobeAddedLocalFile = array();
                $unlinkFileNames = array();


                $j=0;foreach($itemImageIds as $im){

                    $itemImageDb = ItemImages::findorfail($im);
                    //array_push($toBeAddedToPdf, $itemImageDb->big_image_file);
                    $toBeAddedToPdf[$j]['imageBigName'] = $itemImageDb->big_image_file;
                    $toBeAddedToPdf[$j]['imageFileName'] = $itemImageDb->small_image_file;
                    $toBeAddedToPdf[$j]['imageId'] = $im;

                $j++;
                }

                //Log::info($toBeAddedToPdf);

                $i=0; foreach($toBeAddedToPdf as $tobe){

                    $contentExits = False;
                    //Log::info($tobe);
                    //$contentThumbnail = Storage::disk('gcsItems')->exists('/'.$tobe['imageFileName']);
                    $contentThumbnail = Storage::disk('gcsItems')->exists('/'.$tobe['imageBigName']);
                    //Log::info($contentThumbnail);
                    if($contentThumbnail){
                        $content = Storage::disk('gcsItems')->get('/'.$tobe['imageBigName']);
                        $contentExits = True;
                    } else {
                        // get Bigger Image.
                        $contentOriginal = Storage::disk('gcsItems')->exists('/'.$tobe['imageBigName']);
                        //Log::info($contentOriginal);
                        if($contentOriginal){
                            $content = Storage::disk('gcsItems')->get('/'.$tobe['imageBigName']);
                        }
                        $contentExits = True;
                    }

                    if($contentExits) {

                        $imageName = 'itemReport_' . $i . '_' . Carbon::now()->timestamp . '.jpg';
                        //array_push($tobeAddedLocalFile, storage_path('app/public/'.$imageName));
                        $tempStorage = storage_path('app/public/' . $imageName);
                        $tobeAddedLocalFile[$i]['imageFileName'] = $tempStorage;
                        $tobeAddedLocalFile[$i]['imageId'] = $tobe['imageId'];
                        array_push($unlinkFileNames, $imageName);
                        Storage::disk('localItemReportPictures')->put($imageName, $content);
                        $this->correctImageOrientation($tempStorage);
                        $i++;

                    }
                }
                //Log::info($tobeAddedLocalFile);

                $imagePDfReport  = PDF::loadView('item.imageReportPdf', ['toBeAddedToPdf' => $tobeAddedLocalFile, 'item' => $item]);
                //$imagePDfReport->setOption('footer-html', 'Test work');

                $fileOriginalName = 'Item_'.$item->ITEM_ID.'_'.Carbon::now()->timestamp.'.pdf';
                Storage::disk('gcs')->put('itemConditionReport/'.$item->ITEM_ID.'/'.$fileOriginalName, $imagePDfReport->output());

                $itemHasConditionReport = new ItemHasConditionReports();
                $itemHasConditionReport->item_id = $item->ITEM_ID;
                $itemHasConditionReport->filename = $fileOriginalName;
                $itemHasConditionReport->fileType = 'pdf';
                $itemHasConditionReport->logs = 'Picture_Report-IPA-Item-'. $item->ITEM_ID;
                $itemHasConditionReport->status = false;
                $itemHasConditionReport->save();

                foreach($unlinkFileNames as $unlinkFile){
                    Storage::disk('localItemReportPictures')->delete($unlinkFile);
                }

                return response(['status' => true, 'message' => array('Picture report was generated successfully')], 200);


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

    function correctImageOrientation($filename) {
        if (function_exists('exif_read_data')) {
            $exif = @exif_read_data($filename);
            if($exif && isset($exif['Orientation'])) {
                $orientation = $exif['Orientation'];
                if($orientation != 1){
                    $img = imagecreatefromjpeg($filename);
                    $deg = 0;
                    switch ($orientation) {
                        case 3:
                            $deg = 180;
                            break;
                        case 6:
                            $deg = 270;
                            break;
                        case 8:
                            $deg = 90;
                            break;
                    }
                    if ($deg) {
                        $img = imagerotate($img, $deg, 0);
                    }
                    // then rewrite the rotated image back to the disk as $filename
                    imagejpeg($img, $filename, 95);
                } // if there is some rotation necessary
            } // if have the exif orientation info
        } // if function exists
    }

    // View Image
    public function viewItemImage( $imageId) {

        try{

            $itemImage = ItemImages::findorfail($imageId);
            return view('item.viewPicture',['itemImage' => $itemImage]);


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

    // Delete Item Picture
    public function deleteItemPicture( $id){

            try{

                $itemImage =  ItemImages::findorfail($id);

                $exitsMainImage = Storage::disk('gcsItems')->exists('/'.$itemImage->big_image_file); // Main Image
                if($exitsMainImage){
                    Storage::disk('gcsItems')->delete('/'.$itemImage->big_image_file);
                }
                $exitsSmallImage =  Storage::disk('gcsItems')->exists('/'.$itemImage->small_image_file); // Small Image.
                if($exitsSmallImage){
                    Storage::disk('gcsItems')->delete('/'.$itemImage->small_image_file);
                }

                Cache::pull('Item-'.$itemImage->image_id);

                $itemImage->delete();

                return response(['status' => true, 'message' => 'Image successfully deleted'], 200);

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
    public function visibilityReport( Request $request ){

        $validator = \Validator::make(
            array(
                'status'    => $request->input('status'),
                'report_id' => $request->input('report_id')
            ),
            array(
                'status' => 'required',
                'report_id' => 'required|int'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try{

                $status = $request->input('status');
                $report_id = $request->input('report_id');

                $itemReport = ItemHasConditionReports::findorfail($report_id);
                $itemReport->update(['status' => $status]);

                return response(['status' => true, 'data' => $itemReport], 200);


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

    // Add Reports.
    public function addReports(Request $request){

        $validator = \Validator::make(
            array(
                'item_id' => $request->get('item_id'),
                'file' => $request->file('file')
            ),
            array(
                'item_id' => 'required',
                'file' => 'required'
            )
        );
        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {
            try {

                $itemId = $request->get('item_id');
                $itemReports = $request->file('file'); // Attached files
                Items::findorfail($itemId);

                foreach ($itemReports as $uploadFile) {

                    $fileOriginalName = trim($uploadFile->getClientOriginalName());
                    $fileOriginalName = str_replace(" ", "_", $fileOriginalName);
                    $fileInfo  = Storage::disk('gcs')->put('itemConditionReport/'.$itemId, $uploadFile);
                    Storage::disk('gcs')->setVisibility($fileInfo, 'public');
                    $itemHasConditionReport = new ItemHasConditionReports();
                    $itemHasConditionReport->item_id = $itemId;
                    $itemHasConditionReport->filename = $fileInfo;
                    $itemHasConditionReport->fileType = $uploadFile->getClientOriginalExtension();
                    $itemHasConditionReport->logs = 'Report Added by '. auth()->user()->name;
                    $itemHasConditionReport->status = true;
                    $itemHasConditionReport->save();

                }
                return response(['status' => true, 'message' => array('Report was added successfully')], 200);


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


    /* delete Reports */
    public function deleteReport($id) {

        try{

            $reportFile = ItemHasConditionReports::findorfail($id);
            Storage::disk('gcs')->delete('itemConditionReport/'.$reportFile->item_id.'/'.$reportFile->filename);
            $reportFile->delete();

            return response(['status' => true, 'message' => array('Report successfully removed')], 200);


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
    // Generate Client Invoice
    public function generateClientInvoice( Request $request){


    }

    // Add Expense to Items
    public function addExpenseToItem( Request $request ){

        $validator = \Validator::make(
            array(
                'item_id' => $request->input('item_id'),
                'client_id' => $request->input('client_id'),
                'amount'      => $request->input('amount'),
                'expenseType' => $request->input('expense_type'),
                'chargeable'  => $request->input('chargeable'),
                'comments'    => $request->input('comments'),
            ),
            array(
                'item_id' => 'required',
                'client_id' => 'required',
                'amount' => 'required',
                'expenseType' => 'required',
                'chargeable' => 'required',
                'comments'   => 'nullable'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $item_id = $request->input('item_id');
                $client_id = $request->input('client_id');
                $amount = $request->input('amount');
                $expenseType = $request->input('expense_type');
                $chargeable = $request->input('chargeable');
                $comments = $request->input('comments');

                $itemExpense = new ItemHasExpense();
                $itemExpense->item_id = $item_id;
                $itemExpense->expense_type = $expenseType;
                $itemExpense->expense_amount = $amount;
                $itemExpense->expense_dt = Carbon::now()->format('Y-m-d H:i:s');
                $itemExpense->description = $comments;
                $itemExpense->chargeable = $chargeable;
                $itemExpense->save();

                // Generate Invoice Number
                $lastEntry = ClientInvoices::max('invoice_number');
                $lastEntryYear = substr($lastEntry, 0,4);
                $lastEntryMonth = substr($lastEntry, 4,2);
                //Log::info($lastEntryYear);
                //Log::info($lastEntryMonth);
                $invoiceNumber = Carbon::now()->format('Y').Carbon::now()->format('m').'0001';
                //Log::info($invoiceNumber);
                if($lastEntryYear === Carbon::now()->format('Y')){
                    if($lastEntryMonth === Carbon::now()->format('m')) {
                        $invoiceNumber = $lastEntry + 1;
                    }
                }

                // Client Invoice
                $clientInvoice = new ClientInvoices();
                $clientInvoice->invoice_number = $invoiceNumber;
                $clientInvoice->create_dt = Carbon::now()->format('Y-m-d H:i:s');
                $clientInvoice->generated_dt = Carbon::now()->format('Y-m-d H:i:s');
                $clientInvoice->generated = 1;
                $clientInvoice->client_id = $client_id;
                $clientInvoice->invoice_amount = $amount;
                $clientInvoice->save();
                // Lets Attach Invoice to Items
                $clientInvoiceLines = new ClientInvoiceLines();
                $clientInvoiceLines->client_invoice_id = $clientInvoice->client_invoice_id;
                $clientInvoiceLines->additional_or_item = 'ITEM';
                $clientInvoiceLines->expense_type = $expenseType;
                $clientInvoiceLines->description = $comments;
                $clientInvoiceLines->expense_amount = $amount;
                $clientInvoiceLines->item_expense_id = $itemExpense->item_expense_id;
                $clientInvoiceLines->save();


                // Updating Cache Data.
                $itemData = Items::findorfail($item_id);

                // Lets Add Invoice File As well.
                $clientInvoiceData  =  ClientInvoices::with(['client','lines.expense'])->findorfail($clientInvoice->client_invoice_id);
                $assignment = Assignment::findorfail($itemData->ASSIGNMENT_ID);

                $InvoicePdf = PDF::loadView('assignment.clientInvoicePdf', ['clientInvoice' => $clientInvoiceData, 'assignment' => $assignment]);

                $fileOriginalName = 'ClientInvoice_'.$clientInvoice->invoice_number.'.pdf';
                Storage::disk('gcs')->put('assignment/'.$assignment->assignment_id.'/'.$fileOriginalName, $InvoicePdf->output());

                $assignmentHasFiles = new AssignmentHasFiles();
                $assignmentHasFiles->assignment_id = $assignment->assignment_id;
                $assignmentHasFiles->filename = 'assignment/'.$assignment->assignment_id.'/'.$fileOriginalName;
                $assignmentHasFiles->fileType = 'pdf';
                $assignmentHasFiles->logs = 'Client Invoice '. $clientInvoice->invoice_number;
                $assignmentHasFiles->status = true;
                $assignmentHasFiles->save();

                //$this->updateAssignmentCache($itemData->ASSIGNMENT_ID);

                return response(['status' => true, 'data' => $clientInvoiceLines->item_expense_id , 'message' => 'Expense successfully accepted' ], 200);


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

    // Add Bids to Items
    public function addBidsToItem( Request $request)
    {

        $validator = \Validator::make(
            array(
                'item_id' => $request->input('item_id'),
                'customer_id' => $request->input('customer_id'),
                'amount'      => $request->input('amount'),
                'comments' => $request->input('bid_comments'),
                'bid_date' => $request->input('bid_date')
            ),
            array(
                'item_id' => 'required',
                'customer_id' => 'required',
                'amount' => 'required',
                'comments' => 'nullable',
                'bid_date' => 'required'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $itemId = $request->input('item_id');
                $customerId = $request->input('customer_id');
                $comments = $request->input('bid_comments');
                $bidDate = $request->input('bid_date');
                $amount = $request->input('amount');

                Log::info($bidDate);

                $itemData = Items::findorfail($itemId);
                Customer::findorfail($customerId);

                $bid = new Bid();
                $bid->CUSTOMER_ID = $customerId;
                $bid->ITEM_ID = $itemId;
                $bid->BID = $amount;
                //$bid->BID_DT = Carbon::now()->format('Y-m-d H:i:s');
                $bid->BID_DT = Carbon::createFromFormat('j F, Y', $bidDate)->format('Y-m-d H:i:s');
                $bid->NEW_BID = 1;
                $bid->BID_COMMENT = $comments;
                $bid->BID_ACCEPTED = 0;
                $bid->save();

                //$this->updateAssignmentCache($itemData->ASSIGNMENT_ID); // Update Cache.

                return response(['status' => true, 'data' => $bid, 'message' => 'Bid successfully saved' ], 200);

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

    // Generate Customer Invoice
    public function generateCustomerInvoice( Request $request){

        $validator = \Validator::make(
            array(
                'items' => $request->input('items'),
                'customer_id' => $request->input('customer_id'),
            ),
            array(
                'items' => 'required|array',
                'customer_id' => 'required|int',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $items = $request->input('items');
                $notes = $request->input('notes');
                $customerId = $request->input('customer_id');
                $invoiceAmount = 0;

                foreach($items as $item){
                    $itemDetails = Items::with('bids')->findOrFail($item);
                    if(isset($itemDetails->bids) && !empty($itemDetails->bids)){
                        foreach($itemDetails->bids as $bid){
                            if($bid->BID_ACCEPTED === 1) {
                                $invoiceAmount += $bid->BID;
                            }
                        }
                    } else {
                        return response(['status' => false, 'errors' => array('error' => 'Item ID' .$item.' does not have valid bids')], 400);
                    }
                }

                $customer = Customer::findorfail($customerId);

                //Log::info(Carbon::now()->format('y'));
                //Invoice Number
                $lastEntry = Invoice::max('invoice_number');
                $lastEntryYear = substr($lastEntry, 0,2);
                $invoiceNumber = Carbon::now()->format('y').Carbon::now()->format('m').'01';
                if($lastEntryYear === Carbon::now()->format('y')){
                    $invoiceNumber = $lastEntry+1;
                }
                $invoiceAuth = new Invoice();
                $invoiceAuth->customer_id = $customer->CUSTOMER_ID;
                $invoiceAuth->create_dt = Carbon::now()->format('y-m-d H:i:s');
                $invoiceAuth->invoice_amount = $invoiceAmount;
                $invoiceAuth->special_instructions = $notes;
                $invoiceAuth->terms = 'Total Due on Receipt';
                $invoiceAuth->send_to_email = $customer->EMAIL;
                $invoiceAuth->invoice_number = $invoiceNumber;
                $invoiceAuth->save();

                $assignmentItem = '';

                foreach($items as $item){
                    $invoiceAuthItem =  new InvoiceHasItems();
                    $invoiceAuthItem->invoice_auth_id = $invoiceAuth->invoice_auth_id;
                    $invoiceAuthItem->item_id = $item;
                    $invoiceAuthItem->save();
                    $assignmentItem = $item;
                }

                // Lets Copy the file in the Assignment as well.
                if(isset($assignmentItem) && !empty($assignmentItem)) {

                    $itemData = Items::findorfail($assignmentItem);

                    $invoiceAuthorization = Invoice::with(['customer', 'items.item.bids'])->findorfail($invoiceAuth->invoice_auth_id);
                    $customerPdf = PDF::loadView('invoice.invoiceAuthPdf', ['authorization' => $invoiceAuthorization]);

                    $fileOriginalName = 'IPA_Customer_Invoice#'.$invoiceAuth->invoice_auth_id.'.pdf';
                    Storage::disk('gcs')->put('assignment/'.$itemData->ASSIGNMENT_ID.'/'.$fileOriginalName, $customerPdf->output());

                    $assignmentHasFiles = new AssignmentHasFiles();
                    $assignmentHasFiles->assignment_id = $itemData->ASSIGNMENT_ID;
                    $assignmentHasFiles->filename = 'assignment/'.$itemData->ASSIGNMENT_ID.'/'.$fileOriginalName;
                    $assignmentHasFiles->fileType = 'pdf';
                    $assignmentHasFiles->logs = 'Customer Invoice '. $invoiceAuth->invoice_auth_id;
                    $assignmentHasFiles->status = true;
                    $assignmentHasFiles->save();

                    //$this->updateAssignmentCache($itemData->ASSIGNMENT_ID);

                }

                return response(['status' => true, 'data' => $invoiceAuth , 'message' => 'Bid successfully accepted' ], 200);



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
    // Update Recovery Date Items
    public function updateRecoveryDate( Request $request){

        $validator = \Validator::make(
            array(
                'items' => $request->input('items'),
            ),
            array(
                'items' => 'required|array',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $items = $request->input('items');

                if(isset($items) && !empty($items)){
                    foreach($items as $itemId => $val){
                        $item = Items::findOrFail($itemId);
                        $item->ITEM_RECOVERY_DT = Carbon::createFromFormat('j F, Y', $val)->format('Y-m-d H:i:s');
                        $item->save();
                    }
                }

                return response(['status' => true , 'message' => 'Item Recovery Date updated' ], 200);

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
    // Accept Bid
    public function acceptBid($bidId) {

        try{

            $bidsDetail =  Bid::findorfail($bidId);

            // lets Update bid
            $bidsDetail->BID_ACCEPTED = 1;
            $bidsDetail->NEW_BID = 0;
            $bidsDetail->save();

            Bid::where('ITEM_ID', $bidsDetail->ITEM_ID)->update(['NEW_BID' => 0]);

            $itemData = Items::findorfail($bidsDetail->ITEM_ID);
            //$this->updateAssignmentCache($itemData->ASSIGNMENT_ID);

            return response(['status' => true, 'data' => $bidsDetail , 'message' => 'Bid successfully accepted' ], 200);


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
    // List all the Bids
    public function listBids(){

        $itemsBids = Bid::with(['customer','item'])
                     ->whereDate('BID_DT','>=', '2015-07-01')
                     ->orderBy('BID_ID', 'desc')
                     ->get();
        return view('item.bidList', ['itemsBids' => $itemsBids]);
    }

    // Items
    public function listItems(){

        $items = Items::with(['categories','assignment.client.clientInfo','bids'])->whereHas('assignment')->get();
        return view('item.itemList', ['items' => $items]);

    }

    // List All the Categories.
    public function listCategories(){

        $categories  = Category::with(['items'])->orderBy('category_name', 'asc')->get();

        return view('category.list',['categories' => $categories]);

    }

    // Add New Category
    public function addNewCategory(Request $request){


        $validator = \Validator::make(
            array(
                'category_name' => $request->input('category_name'),
            ),
            array(
                'category_name' => 'required',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $categoryName = $request->input('category_name');

                $category = new Category();
                $category->category_name =  $categoryName;
                $category->active = 1;
                $category->save();

                return response(['status' => true, 'data' => $category , 'message' => 'Category Successfully Added' ], 200);

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

    // Update Category
    public function updateCategory( Request $request){

        $validator = \Validator::make(
            array(
                'category_id'   => $request->input('category_id'),
                'category_name' => $request->input('category_name'),
                'status'        => $request->input('status')
            ),
            array(
                'category_name' => 'required',
                'category_id'   => 'required|int',
                'status'        => 'nullable'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $category_name = $request->input('category_name');
                $category_id = $request->input('category_id');
                $status = $request->input('status');
                //Log::info($status);

                $category = Category::findorfail($category_id);
                $category->category_name = $category_name;
                if(isset($status)){
                    $category->active = $status;
                }
                $category->save();

                return response(['status' => true, 'data' => $category , 'message' => 'Category Successfully Updated' ], 200);

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

    // List All the Categories.
    public function listExpense(){

        $expense  = ExpenseType::orderBy('name', 'asc')->get();

        return view('expense.list',['expense' => $expense]);

    }

    // Add New Expense
    public function addNewExpense(Request $request){

        $validator = \Validator::make(
            array(
                'expense_name' => $request->input('expense_name'),
            ),
            array(
                'expense_name' => 'required',
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $expenseName = $request->input('expense_name');
                $expenseType = new ExpenseType();
                $expenseType->name =  $expenseName;
                $expenseType->status = 1;
                $expenseType->save();

                return response(['status' => true, 'data' => $expenseType , 'message' => 'Expense Successfully Added' ], 200);

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

    // Update Expense
    public function updateExpense( Request $request){

        $validator = \Validator::make(
            array(
                'expense_id'   => $request->input('expense_id'),
                'expense_name' => $request->input('expense_name'),
                'status'        => $request->input('status')
            ),
            array(
                'expense_name' => 'required',
                'expense_id'   => 'required|int',
                'status'        => 'nullable'
            )
        );

        if ($validator->fails()) {
            return response(['status' => false, 'errors' => $validator->messages()], 200);
        } else {

            try{

                $expense_name = $request->input('expense_name');
                $expense_id = $request->input('expense_id');
                $status = $request->input('status');

                $expenseType = ExpenseType::findorfail($expense_id);
                $expenseType->name = $expense_name;

                if(isset($status)){
                    $expenseType->status = $status;
                }

                $expenseType->save();

                return response(['status' => true, 'data' => $expenseType , 'message' => 'Expense Successfully Updated' ], 200);

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
