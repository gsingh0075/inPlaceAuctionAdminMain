@extends('layouts.masterHorizontal')

@section('title','Add FMV - InPlace Auction')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
 <style>
     #itemImageDropArea{
         border: 2px dashed #5A8DEE;
         min-height: 300px;
         border-radius: 20px;
     }
 </style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Item</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('showAssignment', $item->ASSIGNMENT_ID) }}">Back to Assignment</a>
                                </li>
                                <li class="breadcrumb-item active">Edit Item
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body container addContainer">
            <!-- Add Clients Form -->
            <section id="floating-label-layouts">
                <div class="row match-height">
                    <div class="col-12" id="item-edit-container">
                        <div class="card">
                            <!--<div class="card-header">
                                <h6>New FMV</h6>
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="itemEdit" action="" method="post"  enctype="multipart/form-data">
                                        <div class="form-body">
                                            <!-- Status Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Item Details - {{ $item->ASSIGNMENT_ID }}-{{ $item->ITEM_NMBR }}</h6>
                                                    <input type="hidden" name="item_id" id="item_id" value="{{ $item->ITEM_ID }}">
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="category_id">Category</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="category_ids[]" id="category_id" multiple="multiple">
                                                                <option value="">Please select</option>
                                                                @php $itemC = array(); @endphp
                                                                @if(isset($item->categories) && !empty($item->categories))
                                                                    @foreach($item->categories as $itemCategory)
                                                                         @php array_push($itemC, $itemCategory->category_id );@endphp
                                                                    @endforeach
                                                                @endif
                                                                @if(isset($categories) && !empty($categories))
                                                                    @foreach($categories as $category)
                                                                            <option value="{{ $category->category_id }}" @if(in_array($category->category_id, $itemC)) selected="selected" @endif>{{ $category->category_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="quantity">Quantity</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="quantity" class="form-control" name="quantity" placeholder="Quantity" value="{{ $item->QUANTITY }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="year">Year</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="year" class="form-control" name="year" placeholder="Year" value="{{$item->ITEM_YEAR}}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="make">Make</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="make" class="form-control" name="make" placeholder="Make" value="{{ $item->ITEM_MAKE }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="model">Model/Description</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="model" class="form-control" name="model" placeholder="Model" value="{{ $item->ITEM_MODEL }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="serial_number">Serial Number</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="serial_number" class="form-control" name="serial_number" placeholder="Serial Number" value="{{ $item->ITEM_SERIAL }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="unit_code">Unit Code</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="unit_code" class="form-control" name="unit_code" placeholder="Unit Code" value="{{ $item->ITEM_UNIT }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="additional_information">Additional Information</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="additional_information" id="additional_information" rows="3" placeholder="Additional Information">{{ $item->ITEM_DESC }}</textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="missing_items">Missing Items</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="missing_items" id="missing_items" rows="3" placeholder="Missing Items">{{ $item->MISSING_ITEMS }}</textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="condition_report_desc">Condition Report Desc</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="condition_report_desc" id="condition_report_desc" rows="3" placeholder="Report Desc">{{ $item->CLIENT_COND_RPT_DESC }}</textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="city">Location - City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="city" class="form-control" name="city" placeholder="City" value="{{ $item->LOC_CITY }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="state_id">Location - State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="state_id" id="state_id">
                                                                @if(isset($states) && !empty($states))
                                                                    @foreach($states as $state)
                                                                        <option value="{{ $state->STATE_ID }}" @if( $state->STATE_ID == $item->LOC_STATE) selected @endif>{{ $state->STATE }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="zip">Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="zip" class="form-control" name="zip" placeholder="zip" value="{{ $item->LOC_ZIP }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="original_sold_date">Original Sold Date</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="original_sold_date" class="form-control pickDate" name="original_sold_date" placeholder="Original Sold Date" value="@if(!empty($item->ITEM_RECOVERY_DT)){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->ITEM_RECOVERY_DT)->format('j F, Y') }}@endif">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="cost">Original Cost</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="cost" class="form-control" name="cost" placeholder="Cost" value="{{ $item->ORIG_COST }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="fmv">Fair Market Value</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="fmv" class="form-control" name="fmv" placeholder="FMV" value="{{ $item->FMV }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="asking_price">Asking Price</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="asking_price" class="form-control" name="asking_price" placeholder="Asking Price" value="{{ $item->ASKING_PRICE }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="condition_code">Condition Code</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select  class="custom-select form-control" name="condition_code" id="condition_code">
                                                                <option value="">Not Selected</option>
                                                                <option value="New" @if($item->CONDITION_CODE == 'New') selected @endif>New</option>
                                                                <option value="Good" @if($item->CONDITION_CODE == 'Good') selected @endif>Good</option>
                                                                <option value="Average" @if($item->CONDITION_CODE == 'Average') selected @endif>Average</option>
                                                                <option value="Poor" @if($item->CONDITION_CODE == 'Poor') selected @endif>Poor</option>
                                                                <option value="Scrap" @if($item->CONDITION_CODE == 'Scrap') selected @endif>Scrap</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="recovered_date">Item Recovered Date</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="recovered_date" class="form-control pickDate" name="recovered_date" placeholder="Recovered Date" value="@if(!empty($item->ITEM_RECOVERY_DT)){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->ITEM_RECOVERY_DT)->format('j F, Y') }}@endif">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="in_possession">Do We Have Possession?</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="in_possession" id="in_possession">
                                                                <option value="">Please select</option>
                                                                <option value="1" @if($item->IN_POSSESSION == 1) selected @endif>Yes</option>
                                                                <option value="0" @if($item->IN_POSSESSION == 0) selected @endif>No</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="in_possession">Storage Location</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="storage_location" class="form-control" name="storage_location" placeholder="Storage Location" value="{{ $item->storage_location }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="in_possession">Storage Contact Person Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="storage_contact_name" class="form-control" name="storage_contact_name" placeholder="Name" value="{{ $item->storage_contact_name }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="in_possession">Storage Contact Person Phone No.</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="storage_contact_number" class="form-control" name="storage_contact_number" placeholder="Phone No" value="{{ $item->storage_contact_number }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="original_sold_date">Sale Date</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="inplace_sale_date" class="form-control pickDate" name="inplace_sale_date" placeholder="Sale Date" value="@if(!empty($item->inplace_sale_date)){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->inplace_sale_date)->format('j F, Y') }}@endif">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="in_possession">Sold</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="sold_flag" id="sold_flag">
                                                                <option value="">Please select</option>
                                                                <option value="1" @if($item->SOLD_FLAG == 1) selected @endif>Yes</option>
                                                                <option value="0" @if($item->SOLD_FLAG == 0) selected @endif>No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Item Details -->
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <input type="button" class="btn btn-primary mr-1 mb-1" id="updateItem" value="Update Item">
                                              </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Edit Form -->
            <!-- Item Gallery Area -->
            <section id="item-gallery">
                <div class="row">
                    <div class="col-12">
                        <!-- user profile right side content gallery start -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-1">Gallery
                                    <span style="float: right;">
                                              <button type="button"
                                                      class="btn btn-primary mr-1 mb-1"
                                                      id="addImages"
                                                      data-toggle="modal"
                                                      data-target="#itemAddImagesModal">Add Images
                                              </button>
                                          @if( (isset($item->images)) && (count($item->images)>0) )
                                            <button type="button"
                                                    class="btn btn-primary mr-1 mb-1"
                                                    id="generatePictureReportBtn"
                                                    data-toggle="modal"
                                                    data-target="#generatePictureReport">Generate Picture Report
                                              </button>
                                          @endif
                                         </span>
                                </h4>
                            </div>
                            <div class="card-content load-Image">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Item Gallery Area -->

            <!-- Image Form -->
            <div class="modal fade text-left" id="itemAddImagesModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="itemAddImagesModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Images</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <form action="{{ route('addItemImages') }}"  method="post"  enctype="multipart/form-data" id="addItemImages">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="dropzone" id="itemImage">
                                            <div class="dz-message">Drop Images Here To Upload</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="button" id="addImageFormModal" class="btn btn-primary ml-1">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Add</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Image form -->

            <!-- Files List -->
            <section id="item-reports-containers">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Condition Reports</h4>
                                <span style="float: right;">
                                     <button type="button" class="btn btn-primary mr-1 mb-1" id="addFiles" data-toggle="modal" data-target="#itemAddReportsModal">Add Reports</button>
                                </span>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table dataTable zero-configuration" id="itemConditionReportTable">
                                            <thead>
                                            <tr>
                                                <th>Logs</th>
                                                <th>Type</th>
                                                <th>Upload Date</th>
                                                <th>Generated Date</th>
                                                <!--<th>Visibility</th>-->
                                                <th>View</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($item->reports) > 0)
                                                @foreach( $item->reports as $file )
                                                    <tr>
                                                        <td>{{ $file->logs }}</td>
                                                        <td>{{ $file->fileType }} </td>
                                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $file->updated_at)->format('j F, Y') }}</td>
                                                        <!--<td>@if(!empty( $file->generated_date)){{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $file->generated_date)->format('j F, Y') }}@else NO DATE @endif &nbsp; <button type="button" class="btn btn-primary mr-1 mb-1" data-toggle="modal" data-target="#itemAddReportDateModal" data-id="{{$file->id}}" data-item="{{$file->logs}}">Update</button></td>-->
                                                        <td>
                                                            @if($file->status === 1)
                                                               <a href="javascript:void(0)" data-url="{{ route('visibilityReport') }}" class="visibilityReport" data-id="{{ $file->id }}" data-status="0">Hide on Client Portal</a>
                                                            @else
                                                                <a href="javascript:void(0)" data-url="{{ route('visibilityReport') }}" class="visibilityReport" data-id="{{ $file->id }}" data-status="1">Show on Client Portal</a>
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ $file->fileSignedUrl }}" target="_blank">View</a></td>
                                                        <td><a href="javascript:void(0)" class="deleteConditionReport" data-attr-link="{{ route('deleteReport', $file->id) }}">Delete</a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="6" class="text-center">No Files</td></tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Files list -->
            <!-- Item Condition ReportDate Update -->
            <div class="modal fade text-left" id="itemAddReportDateModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="itemAddReportDateModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Date <span id="item_report_date_desc"></span></h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <form action="" method="post" id="addReportsDate">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <input type="hidden" name="item_report_condition_id" id="item_report_condition_id" value="">
                                        <input type="text" id="item_report_condition_date" class="form-control pickDate" name="item_report_condition_date" placeholder="Report Date" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="button" id="addItemReportDateFormModal" data-action="{{ route('updateConditionReportVisibilityDate') }}" class="btn btn-primary ml-1">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Update</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
            <!-- Item Form -->
            <div class="modal fade text-left" id="itemAddReportsModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="itemAddFilesModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add Reports</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <form action="{{ route('addReports') }}"  method="post"  enctype="multipart/form-data" id="addReportsFmv">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="dropzone" id="ItemReports">
                                            <div class="dz-message">Drop Reports Here To Upload</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="button" id="addItemReportFormModal" class="btn btn-primary ml-1">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Add</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add Form Files End -->
        </div>
    </div>
@push('page-vendor-js')
<script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
@endpush
@push('page-js')
<script>

    Dropzone.autoDiscover = false;
    var body = $('body');
    var itemForm = $('#itemEdit'); // Form
    var itemId = $('#item_id').val();
    var loadImageContainer = $('.load-Image');
    var itemContainer = $('#item-edit-container'); // Main Container
    var deleteItemImage = $('.deleteItemImage');
    var visibilityReport = $('.visibilityReport');
    var itemAddReportDateModal = $('#itemAddReportDateModal');
    var itemReportConditionId = $('#item_report_condition_id');
    var itemReportConditionDate = $('#item_report_condition_date');
    var itemReportDateDesc = $('#item_report_date_desc');
    var addItemReportDateFormModal = $('#addItemReportDateFormModal');


    // On Item condition report show Modal
    itemAddReportDateModal.on('show.bs.modal', function (e) {
        let btn = $(e.relatedTarget);
        let id = btn.data('id');
        let itemDesc = btn.data('item');
        console.log(id);
        console.log(itemDesc);
        itemReportConditionId.val(id);
        itemReportDateDesc.html(itemDesc)
    });

    // On save Item condition report
    addItemReportDateFormModal.click(function () {

        console.log('Button clicked');
        var action = $(this).attr('data-action');

        $.ajax({
            url: action,
            type: "POST",
            dataType: "json",
            data: {
                'item_condition_report_id': itemReportConditionId.val(),
                'item_condition_report_date': itemReportConditionDate.val(),
            },
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: "Good job!",
                        text: "Date is updated successfully",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            window.location.reload();
                        }
                    });
                } else {
                    $.each(response.errors, function (key, value) {
                        toastr.error(value)
                    });
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
                toastr.error(text);
            }
        });

    });

        loadItemImages();

        $('#category_id').select2({
            placeholder: "Category",
        });
        $('#state_id').select2({
            placeholder: "Select2"
        });

        $('.pickDate').pickadate(); // Date Picker

        $('.pickDateConditionReport').pickadate({
            container:'#itemConditionReportTable'
        }); // Date Picker


        body.on('click','.removeImageReport',function(){

            var itemCopy = [];
            var selVal = parseInt($(this).attr('data-id'));
            console.log(selVal);
            if( itemConditionReport.includes(selVal) === true ){
                console.log('Came In');
                const index = itemConditionReport.indexOf(selVal);
                if (index > -1) {
                    itemConditionReport.splice(index, 1);
                }
                for (let i = 0; i < itemConditionReport.length; i++) {
                    if( itemConditionReport[i] !== undefined ) {
                        itemCopy.push(itemConditionReport[i])
                    }
                }

                itemConditionReport = itemCopy;
                console.log(itemConditionReport);
                $('#imageContainer_'+selVal).remove();
            } else {
                toastr.error('Image does not exists');
            }

        });

        // Visibility Click
        visibilityReport.click(function(){

            var reportId = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var visibilityUrl = $(this).attr('data-url');

            Swal.fire({
                title: 'Are you sure?',
                text: "Report Visibility will be affected on the client portal",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    blockExt($('.content-wrapper'), $('#waitingMessage'));
                    $.ajax({
                        url: visibilityUrl,
                        type: "POST",
                        dataType: "json",
                        data : {
                            status: parseInt(status),
                            report_id : parseInt(reportId)
                        },
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    title: "Updated!",
                                    text: "Report visibility was successfully updated!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                                //unBlockExt($('.content-wrapper'));
                            } else {
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                                unBlockExt($('.content-wrapper'));
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            unBlockExt($('.content-wrapper'));
                        }
                    });
                }
            });

        });

        // Delete Item Image
        //deleteItemImage.click(function(){
        body.on('click', '.deleteItemImage' ,  function(e){

            var deleteLink = $(this).attr('data-attr-link');
            //console.log('Delete was clicked for the picture');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {

                console.log(result);

                if (result.value) {
                    //blockExt($('.content-wrapper'), $('#waitingMessage'));
                    $.ajax({
                        url: deleteLink,
                        type: "GET",
                        dataType: "json",
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Image was successfully deleted!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        //window.location.reload();
                                        loadItemImages();
                                    }
                                });
                                //unBlockExt($('.content-wrapper'));
                            } else {
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                                //unBlockExt($('.content-wrapper'));
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            //unBlockExt($('.content-wrapper'));
                        }
                    });
                }
            })
        });

        // Update Item
        $('#updateItem').click(function(){ // Even for Form Submit.

                blockExt($('.content-wrapper'), $('#waitingMessage'));
                $.ajax({
                    url: "{{route('updateItemAssignment')}}",
                    type: "POST",
                    dataType: "json",
                    data: itemForm.serialize(),
                    headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            //console.log(response)
                            //unBlockItemContainer();
                            Swal.fire({
                                title: "Good job!",
                                text: "Item updated",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            unBlockExt($('.content-wrapper'));
                            //unBlockItemContainer();
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        //unBlockItemContainer();
                    }
                });
        });

        // Upload Images
        $('#addImageFormModal').click(function(){
            myImageDropzone.processQueue();
        });

        $('#itemImage').dropzone({
            url: "{{route('addItemImages')}}",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 100,
            maxFiles: 100,
            addRemoveLinks: true,
            acceptedFiles: ".jpeg,.jpg,.png,.gif",
            headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
            init : function () {
                myImageDropzone = this;
            },
            sending: function(file, xhr, formData){
                //blockItemContainer();
                //blockExt($('.content-wrapper'), $('#waitingMessage'));
                formData.append('item_id', $('#item_id').val());
            },
            success: function(file, response){
                if(response.status){
                    Swal.fire({
                        title: "Good job!",
                        text: "Images added!",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            //window.location.reload();
                            $('#itemAddImagesModal').modal('hide');
                            loadItemImages();
                        }
                    });
                } else {
                    $.each(response.errors, function (key, value) {
                        //console.log(value)
                        toastr.error(value);
                    });
                    myDropzone.removeFile(file);
                    //unBlockExt($('.content-wrapper'));
                }
            }
        });



       // Add Reports
        $('#addItemReportFormModal').click(function(){
            myDropzone.processQueue();
        });

        $('#ItemReports').dropzone({
            url: "{{route('addReports')}}",
            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 100,
            maxFiles: 100,
            addRemoveLinks: true,
            headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
            init : function () {
                myDropzone = this;
            },
            sending: function(file, xhr, formData){
                //blockItemContainer();
                blockExt($('.content-wrapper'), $('#waitingMessage'));
                formData.append('item_id', $('#item_id').val());
            },
            success: function(file, response){
                if(response.status){
                    //console.log(response)
                    //unBlockItemContainer();
                    Swal.fire({
                        title: "Good job!",
                        text: "Reports added!",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            window.location.reload();
                        }
                    });
                } else {
                    $.each(response.errors, function (key, value) {
                        //console.log(value)
                        toastr.error(value);
                    });
                    myDropzone.removeFile(file);
                    //unBlockItemContainer();
                    unBlockExt($('.content-wrapper'));
                }
            }
        });

        // Delete File.
        $('.deleteConditionReport').click(function(e){ // delete File click

            var deleteLink = $(this).attr('data-attr-link');

            blockExt($('.content-wrapper'), $('#waitingMessage'));

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-danger ml-1',
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {

                    $.ajax({
                        url: deleteLink,
                        type: "GET",
                        dataType: "json",
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Condition report was successfully deleted!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                                unBlockExt($('.content-wrapper'));
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            unBlockExt($('.content-wrapper'));
                        }
                    });
                }
            })

        });

        // Load Item Images.
        function loadItemImages(){

            blockExt(loadImageContainer, $('#waitingMessage'));
            $.ajax({
                url: '/item/images/'+itemId,
                type: "GET",
                dataType: "json",
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        loadImageContainer.html(response.html);
                        unBlockExt(loadImageContainer);
                    } else {
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                        unBlockExt(loadImageContainer);
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt(loadImageContainer);
                }
            });
        }

</script>
@endpush
@endsection
