@extends('layouts.masterHorizontal')

@section('title','List FMV - InPlace Auction')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
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
                                <li class="breadcrumb-item"><a href="{{ route('getFmv') }}">Back to all FMV</a>
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
                                    <form class="form" id="fmvAddData" action="{{ route('addFmvAjax') }}" method="post"  enctype="multipart/form-data">
                                        <div class="form-body">
                                            <!-- Status Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Status Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="priority">Priority</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="priority" id="priority">
                                                                <option value="3">Low Priority</option>
                                                                <option value="2">Standard Priority</option>
                                                                <option value="1">High Priority < 1</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="request_date">Date Requested</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="request_date" class="form-control pickDate" name="request_date" placeholder="Date Requested">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Status Details -->
                                            <!-- Basic Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Basic Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="evaluator_admin_id">Assessor</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="evaluator_admin_id" id="evaluator_admin_id">
                                                                <!-- Dynamically we will load -->
                                                                <option value="">Assessor</option>
                                                                @if(isset($users) && !empty($users))
                                                                    @foreach($users as $user)
                                                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="reason">Reason</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
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
                                                            <label for="client_id">Client</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control required" name="client_id" id="client_id">
                                                                <!-- Dynamically we will load -->
                                                                <option value="">Client</option>
                                                                @if(isset($clients) && !empty($clients))
                                                                    @foreach($clients as $client)
                                                                        <option data-email={{ $client->EMAIL }} data-first="{{ $client->FIRSTNAME }}" data-last="{{ $client->LASTNAME }}" value="{{ $client->CLIENT_ID }}">{{ $client->FIRSTNAME }} {{ $client->LASTNAME }} {{ $client->COMPANY }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="special_instructions">Special Instructions</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="special_instructions" class="form-control" name="special_instructions" placeholder="Special Instructions">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Basic Details -->
                                            <!-- Request By Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Request By Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="first_name">First Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="first_name" class="form-control" name="first_name" placeholder="First Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="last-name">Last Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="last-name" class="form-control" name="last_name" placeholder="Last Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="email">Email</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="email" class="form-control" name="email" placeholder="Email">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="cc_email">CC Email</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="cc_email" class="form-control" name="cc_email" placeholder="CC Email">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="phone">Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="phone" class="form-control" name="phone" placeholder="Phone">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Request By Details -->
                                            <!-- Lease Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Lease Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="lease_number">Lease Number</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="lease_number" class="form-control" name="lease_number" placeholder="Lease Number">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="company_name">Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="company_name" class="form-control" name="company_name" placeholder="Company Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="lease_name">Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="lease_name" class="form-control" name="lease_name" placeholder="Name">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Lease Details -->
                                            <!-- Additional Comments Section -->
                                            <div class="row">
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="additional_comments">Additional Comment</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="additional_comments" id="additional_comments" rows="3" placeholder="Additional Comments"></textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="private_comments">Private Comment</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="private_comments" id="private_comments" rows="3" placeholder="Private Comments"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Addition Comments Section -->
                                            <div class="row">
                                               <div class="col-12">
                                                   <div id="fmvFiles" class="dropzone">
                                                       <div class="dz-message">Drop Files Here To Upload</div>
                                                   </div>
                                               </div>
                                            </div>
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <input type="button" class="btn btn-primary mr-1 mb-1" id="createFMV" value="Create FMV">
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

    Dropzone.autoDiscover = false;
    var fmvForm = $('#fmvAddData'); // Form
    var fmvContainer = $('#fmv-add-container'); // Main Container
    var clientId =   $('#client_id');

    $(document).ready(function() {

        clientId.select2({
            placeholder: "Client",
        });

        clientId.on('select2:select', function (e) {
            let data = e.params.data;
            let firstName = $(this).select2().find(":selected").data("first");
            let lastName = $(this).select2().find(":selected").data("last");
            let email = $(this).select2().find(":selected").data("email");
            $('#first_name').val(firstName);
            $('#last-name').val(lastName);
            $('#email').val(email);
        });

        $('.pickDate').pickadate(); // Date Picker

        // From Submission without Files

        $('#createFMV').click(function(){ // Even for Form Submit.

                if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) { // Check if Files are there.
                    blockExt(fmvContainer, $('#waitingMessage'));
                    $.ajax({
                        url: "{{route('addFmvAjax')}}",
                        type: "POST",
                        dataType: "json",
                        data: fmvForm.serialize(),
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                unBlockExt(fmvContainer);
                                Swal.fire({
                                    title: "Good job!",
                                    text: "FMV added!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location = "{{ route('getFmv') }}"
                                    }
                                });
                            } else {
                                unBlockExt(fmvContainer);
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                           unBlockExt(fmvContainer)
                        }
                    });
                } else {
                    myDropzone.processQueue(); // If files are there.
                }
           });

           // FMV Added with multiple files

           $('#fmvFiles').dropzone({
            url: "{{route('addFmvAjax')}}",
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
                blockExt(fmvContainer, $('#waitingMessage'));
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
                    unBlockExt(fmvContainer);
                    Swal.fire({
                        title: "Good job!",
                        text: "FMV added!",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            window.location = "{{ route('getFmv') }}"
                        }
                    });

                } else {
                    $.each(response.errors, function (key, value) {
                        toastr.error(value);
                    });
                    myDropzone.removeFile(file);
                    unBlockExt(fmvContainer);
                }
            }
        });


    });

</script>
@endpush
@endsection
