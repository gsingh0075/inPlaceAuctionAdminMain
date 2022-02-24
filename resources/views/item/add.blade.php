@extends('layouts.masterHorizontal')

@section('title','Add FMV - InPlace Auction')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
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
                                <li class="breadcrumb-item"><a href="{{ route('showAssignment', $assignmentId) }}">Back to Assignment</a>
                                </li>
                                <li class="breadcrumb-item active">Add Item
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
                    <div class="col-12" id="item-add-container">
                        <div class="card">
                            <!--<div class="card-header">
                                <h6>New FMV</h6>
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="itemAdd" action="" method="post"  enctype="multipart/form-data">
                                        <div class="form-body">
                                            <!-- Status Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Item Details</h6>
                                                    <input type="hidden" name="assignment_id" id="assignment_id" value="{{ $assignmentId }}">
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>Category</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="category_ids[]" id="category_id" multiple="multiple">
                                                                <option value="">Please select</option>
                                                                @if(isset($categories) && !empty($categories))
                                                                    @foreach($categories as $category)
                                                                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="quantity">Quantity</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="year">Year</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="year" class="form-control" name="year" placeholder="Year">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="make">Make</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="make" class="form-control" name="make" placeholder="Make">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="model">Model/Description</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="model" class="form-control" name="model" placeholder="Model">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="serial_number">Serial Number</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="serial_number" class="form-control" name="serial_number" placeholder="Serial Number">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="unit_code">Unit Code</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="unit_code" class="form-control" name="unit_code" placeholder="Unit Code">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="additional_information">Additional Information</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="additional_information" id="additional_information" rows="3" placeholder="Additional Information" style="color: rgb(48, 65, 86);"></textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="missing_items">Missing Items</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="missing_items" id="missing_items" rows="3" placeholder="Missing Items" style="color: rgb(48, 65, 86);"></textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="city">Location - City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="city" class="form-control" name="city" placeholder="City">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="state_id">Location - State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="state_id" id="state_id">
                                                                @if(isset($states) && !empty($states))
                                                                    @foreach($states as $state)
                                                                        <option value="{{ $state->STATE_ID }}">{{ $state->STATE }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="zip">Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="zip" class="form-control" name="zip" placeholder="zip">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="original_sold_date">Original Sold Date</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="original_sold_date" class="form-control pickDate" name="original_sold_date" placeholder="Original Sold Date">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="cost">Original Cost</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="cost" class="form-control" name="cost" placeholder="Cost">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="fmv">Fair Market Value</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="fmv" class="form-control" name="fmv" placeholder="FMV">
                                                        </div>
                                                        @if($assignment->is_inspection == 0)
                                                        <div class="col-md-4 col-12">
                                                            <label for="asking_price">Asking Price</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="asking_price" class="form-control" name="asking_price" placeholder="Asking Price">
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Item Details -->
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <input type="button" class="btn btn-primary mr-1 mb-1" id="createItem" value="Create Item">
                                                <!--<button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>-->
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
            <!--/ Client Form table -->
        </div>
    </div>
@push('page-vendor-js')
<script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
@endpush
@push('page-js')
<script>
    var itemForm = $('#itemAdd'); // Form
    var itemContainer = $('#item-add-container'); // Main Container

    /************** Block Container and Un block ************************/

    function blockItemContainer(){

        itemContainer.block({
            message: '<span class="semibold"> Loading...</span>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }
    function unBlockItemContainer(){
        itemContainer.unblock();
    }

    $(document).ready(function() {

        $('#category_id').select2({
            placeholder: "Category",
        });
        $('#state_id').select2({
            placeholder: "Select2"
        });

        $('.pickDate').pickadate(); // Date Picker


        /*************************** From Submission *****************************************************/
        $('#createItem').click(function(){ // Even for Form Submit.

                blockItemContainer();
                $.ajax({
                    url: "{{route('saveItemAssignment')}}",
                    type: "POST",
                    dataType: "json",
                    data: itemForm.serialize(),
                    headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            //console.log(response)
                            unBlockItemContainer();
                            Swal.fire({
                                title: "Good job!",
                                text: "Item added to assignment",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                if (result.value) {
                                    window.location = "/assignment/"+response.assignment_id;
                                }
                            });
                        } else {
                            unBlockItemContainer();
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        unBlockItemContainer();
                    }
                });
        });


    });

</script>
@endpush
@endsection
