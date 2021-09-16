@extends('layouts.masterFront')
@section('content')
<div class="content-body container">
    <div class="col-12 addContainer mt-1" id="assignment-add-container">
        <div class="card-content">
            <div class="card-header text-center" style="background: #fff;">
                <img src="{{ asset('app-assets/images/logo/logo_big.jpg') }}" class="img-fluid" style="height: 6rem;" alt="InPlace Auction">
            </div>
            <div class="card-body">
                <form class="form" id="AssignmentCreateData" action="{{ route('createAssignmentFromFmvByClient') }}" method="post"  enctype="multipart/form-data">
                    <input type="hidden" name="fmv_id" value="{{ $fmv_id }}" id="fmv_id">
                <div class="row">
                    <div class="col-md-4 col-12">
                        <label>Lessees's Full Name</label>
                    </div>
                    <div class="col-md-8 form-group col-12">
                        <input type="text" id="ls_full_name" class="form-control" name="ls_full_name" placeholder="Full Name" value="">
                    </div>
                    <div class="col-md-4 col-12">
                        <label>Lessees's Company Name</label>
                    </div>
                    <div class="col-md-8 form-group col-12">
                        <input type="text" id="ls_company" class="form-control" name="ls_company" placeholder="Company Name" value="">
                    </div>
                    <div class="col-md-4 col-12">
                        <label>Lessees's Address</label>
                    </div>
                    <div class="col-md-8 form-group col-12">
                        <input type="text" id="ls_address1" class="form-control" name="ls_address1" placeholder="Address" value="">
                    </div>
                    <div class="col-md-4 col-12">
                        <label>Lessees's City</label>
                    </div>
                    <div class="col-md-8 form-group col-12">
                        <input type="text" id="ls_city" class="form-control" name="ls_city" placeholder="City" value="">
                    </div>
                    <div class="col-md-4 col-12">
                        <label>Lessees's State</label>
                    </div>
                    <div class="col-md-8 form-group col-12">
                        @if(isset($states) && !empty($states))
                            <select class="custom-select form-control" name="ls_state" id="ls_state">
                            @foreach($states as $state)
                                 <option value="{{$state->STATE_ID}}">{{ $state->STATE }}</option>
                            @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="col-md-4 col-12">
                        <label>Lessees's Zip</label>
                    </div>
                    <div class="col-md-8 form-group col-12">
                        <input type="text" id="ls_zip" class="form-control" name="ls_zip" placeholder="Zip" value="">
                    </div>
                    <div class="col-md-4 col-12">
                        <label>Lessees's Business Phone</label>
                    </div>
                    <div class="col-md-8 form-group col-12">
                        <input type="text" id="ls_buss_phone" class="form-control" name="ls_buss_phone" placeholder="Phone" value="">
                    </div>
                    <div class="col-md-4 col-12">
                        <label>Notes</label>
                    </div>
                    <div class="col-md-8 form-group col-12">
                        <textarea data-length="20" class="form-control char-textarea active" name="client_note" id="client_note" rows="3" placeholder="Client notes" style="color: rgb(48, 65, 86);"></textarea>
                    </div>
                </div>
                 <div class="row">
                        <div class="col-12">
                            <div id="assignmentFiles" class="dropzone">
                                <div class="dz-message">Drop Files Here To Upload</div>
                            </div>
                        </div>
                 </div>
                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary mr-1 mb-1" id="updateAssignment">Submit Assignment</button>
                        <!--<button type="reset" class="btn btn-light-secondary mr-1 mb-1">Reset</button>-->
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@push('page-vendor-js')
    <script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
@endpush
@push('page-js')
<script>

    Dropzone.autoDiscover = false;
    var assignmentForm = $('#AssignmentCreateData'); // Form
    var assignmentContainer = $('#assignment-add-container'); // Main Container

    /************** Block Container and Un block ************************/

    function blockAssignmentContainer(){

        assignmentContainer.block({
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
    function unAssignmentContainer(){
        assignmentContainer.unblock();
    }

    $('#updateAssignment').click(function(){ // Even for Form Submit.

        if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) { // Check if Files are there.
            blockAssignmentContainer();
            $.ajax({
                url: "{{route('createAssignmentFromFmvByClient')}}",
                type: "POST",
                dataType: "json",
                data: assignmentForm.serialize(),
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        //console.log(response)
                        unAssignmentContainer();
                        Swal.fire({
                            title: "Good job!",
                            text: "Your Assignment has been successfully submitted!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload()
                            }
                        });
                    } else {
                        unAssignmentContainer();
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unAssignmentContainer();
                }
            });
        } else {
            myDropzone.processQueue(); // If files are there.
        }
    });

    /*** DropZone *****/
    $('#assignmentFiles').dropzone({
        url: "{{route('createAssignmentFromFmvByClient')}}",
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
            blockAssignmentContainer();
            assignmentForm.find("input").each(function(){
                formData.append($(this).attr("name"), $(this).val());
            });
            assignmentForm.find("select").each(function(){
                formData.append($(this).attr("name"), $(this).val());
            });
            assignmentForm.find("textarea").each(function(){
                formData.append($(this).attr("name"), $(this).val());
            });

        },
        success: function(file, response){
            if(response.status){
                //console.log(response)
                unAssignmentContainer();
                Swal.fire({
                    title: "Good job!",
                    text: "Your Assignment has been successfully submitted!",
                    type: "success",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        window.location.reload()
                    }
                });

            } else {
                $.each(response.errors, function (key, value) {
                    //console.log(value)
                    toastr.error(value);
                });
                myDropzone.removeFile(file);
                unAssignmentContainer();
            }
        }
    });

</script>
@endpush
@endsection