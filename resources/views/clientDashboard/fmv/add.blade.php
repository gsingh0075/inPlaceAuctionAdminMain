@extends('clientDashboard.layouts.masterHorizontal')

@section('title','Add FMV - InPlace Auction')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
<style>
    .pac-container span{
        color: #000 !important;
    }
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">FMV</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('getFmvClient') }}">Add FMV</a>
                                </li>
                                <li class="breadcrumb-item active">Add Fmv
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
                    <div class="col-12" id="fmv-add-container">
                        <div class="card">
                            <!--<div class="card-header">
                                <h6>New FMV</h6>
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="fmvAddData" action="{{ route('addFmvClientAjax') }}" method="post"  enctype="multipart/form-data">
                                        <div class="form-body">
                                            <!-- Basic Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <p>InPlaceAuction's Rapid FMV Utility allows you to upload equipment documentation, and input basic lease information, and our staff will readily provide a Fair Market Valuation from your data. Once completed, you can then ask us to set up your quote as an assignment for us!</p>
                                                </div>
                                                <div class="col-12">
                                                    <h6 class="py-50">Basic Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>Reason</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" id="reason" name="reason">
                                                                <!-- Dynamically will load -->
                                                                <option value="">Purpose of Appraisal</option>
                                                                <option value="New Deal">Front End Estimate (New Deal)</option>
                                                                <option value="LLRE">Loan Loss Reserve Estimate</option>
                                                                <option value="Repo" selected="">Potential Repossession</option>
                                                                <option value="EOL">End of Lease Negotiation</option>
                                                                <option value="Internal">Internal</option>
                                                                <option value="Desktop">Desktop Appraisal</option>
                                                                <option value="Collection">Collection Negotiation</option>
                                                                <option value="None">No Reason</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Comments</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea data-length="20" class="form-control char-textarea active" name="comments" id="comments" rows="3" placeholder="Comments" style="color: rgb(48, 65, 86);"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Basic Details -->
                                            <!-- Lease Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Lease Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>Lease Number</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="lease_number" class="form-control" name="lease_number" placeholder="Lease Number">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Lessee's Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="company_name" class="form-control" name="company_name" placeholder="Company Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <!-- Empty for Ajax -->
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" class="form-control" id="autocompleteAddress" placeholder="Type In Address">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Lessee's Address1</label>
                                                        </div>
                                                        <div class="col-md-4 form-group col-12">
                                                            <input type="text" class="form-control" id="street_number" name="company_street" placeholder="Street">
                                                        </div>
                                                        <div class="col-md-4 form-group col-12">
                                                            <input type="text" class="form-control" id="route" name="company_street" placeholder="Address">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Lessee's City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="locality" class="form-control" name="company_city" placeholder="City">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Lessee's State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="administrative_area_level_1" class="form-control" name="company_state" placeholder="State">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Lessee's Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="postal_code" class="form-control" name="company_zip" placeholder="Postal Code">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Lease Details -->
                                            <div class="row">
                                               <div class="col-12">
                                                   <div id="fmvFiles" class="dropzone">
                                                       <div class="dz-message">Drop Files Here To Upload</div>
                                                   </div>
                                               </div>
                                            </div>
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <input type="button" class="btn btn-primary mr-1 mb-1" id="createFMV" value="Submit FMV">
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
<script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDOSZ6FRxGMp9PN_6TDuiY7mfa0CQZlXJg&libraries=places"></script>
@endpush
@push('page-js')
<script>

    Dropzone.autoDiscover = false;
    var fmvForm = $('#fmvAddData'); // Form
    var fmvContainer = $('#fmv-add-container'); // Main Container

    /**************** Google Address Auto Complete ************************/

    let placeSearch;
    let autocomplete;
    const componentForm = {
        street_number : "short_name",
        route: "long_name",
        locality: "long_name",
        administrative_area_level_1: "short_name",
        postal_code: "short_name",
    };
    initAutocomplete();

    function initAutocomplete() {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById("autocompleteAddress"),
            { types: ["geocode"] }
        );
        // Avoid paying for data that you don't need by restricting the set of
        // place fields that are returned to just the address components.
        autocomplete.setFields(["address_component"]);
        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();

        for (const component in componentForm) {
            console.log(component);
            document.getElementById(component).value = "";
            document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        for (const component of place.address_components) {
            const addressType = component.types[0];

            if (componentForm[addressType]) {
                const val = component[componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
    }

    /************** Block Container and Un block ************************/

    function blockFMVContainer(){

        fmvContainer.block({
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
    function unBlockFMVContainer(){
        fmvContainer.unblock();
    }


    $(document).ready(function() {

        /*************************** From Submission without Files *****************************************************/
        $('#createFMV').click(function(){ // Even for Form Submit.

                if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) { // Check if Files are there.
                    blockFMVContainer();
                    $.ajax({
                        url: "{{route('addFmvClientAjax')}}",
                        type: "POST",
                        dataType: "json",
                        data: fmvForm.serialize(),
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                //console.log(response)
                                unBlockFMVContainer();
                                Swal.fire({
                                    title: "Good job!",
                                    text: "FMV added!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location = "{{ route('getFmvClient') }}"
                                    }
                                });
                            } else {
                                unBlockFMVContainer();
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            unBlockFMVContainer();
                        }
                    });
                } else {
                    myDropzone.processQueue(); // If files are there.
                }
           });

           /******************************* FMV Added with multiple files ******************************/

           $('#fmvFiles').dropzone({
            url: "{{route('addFmvClientAjax')}}",
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
                blockFMVContainer();
                fmvForm.find("input").each(function(){
                    formData.append($(this).attr("name"), $(this).val());
                });
                fmvForm.find("select").each(function(){
                    formData.append($(this).attr("name"), $(this).val());
                });
                fmvForm.find("textarea").each(function(){
                    formData.append($(this).attr("name"), $(this).val());
                });

            },
            success: function(file, response){
                if(response.status){
                   //console.log(response)
                    unBlockFMVContainer();
                    Swal.fire({
                        title: "Good job!",
                        text: "FMV added!",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            window.location = "{{ route('getFmvClient') }}"
                        }
                    });

                } else {
                    $.each(response.errors, function (key, value) {
                        //console.log(value)
                        toastr.error(value);
                    });
                    myDropzone.removeFile(file);
                    unBlockFMVContainer();
                }
            }
        });


    });

</script>
@endpush
@endsection
