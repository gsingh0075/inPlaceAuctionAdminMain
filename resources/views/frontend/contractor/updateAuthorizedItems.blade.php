@extends('layouts.masterFront')
@section('content')
<div class="content-body container">
    <div class="col-12 addContainer mt-1" id="authorized-container">
        <div class="card-content">
            <div class="card-header text-center" style="background: #fff;">
                <img src="{{ asset('app-assets/images/logo/logo_big.jpg') }}" class="img-fluid" style="height: 6rem;" alt="InPlace Auction">
            </div>
            <div class="card-body">
                <form class="form" id="updateAuthorizeItem" action="#" method="post"  enctype="multipart/form-data">

                 <input type="hidden" name="contractor_auth_id" value="{{ $contractorAuthorizations->contractor_auth_id }}" id="contractor_auth_id">
                 @if(isset($contractorAuthorizations->authItems) && !empty($contractorAuthorizations->authItems))
                     @foreach($contractorAuthorizations->authItems as $item)
                            <div class="row">
                                <div class="col-12">
                                    <h5>Pictures for: {{ $item->item_id }} {{ $item->item->ITEM_MAKE }} {{ $item->item->ITEM_MODEL }} {{ $item->item->SERIAL }}</h5>
                                </div>
                                <div class="col-12">
                                    <div id="{{ $item->item_id }}" class="dropzone">
                                        <div class="dz-message">Drop Files Here To Upload</div>
                                    </div>
                                </div>
                            </div>
                     @endforeach
                 @endif

                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary mr-1 mb-1" id="updateAuthorization">Submit</button>
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
    var authorizeForm = $('#updateAuthorizeItem'); // Form
    var authorizeContainer = $('#authorized-container'); // Main Container

    /************** Block Container and Un block ************************/

    function blockContainer(){

        authorizeContainer.block({
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
    function unBlockContainer(){
        authorizeContainer.unblock();
    }

    InitializeItems();

    function InitializeItems() { // You only need to encapsulate this in a function if you are going to recall it later after an ajax post.

        Array.prototype.slice.call(document.querySelectorAll('.dropzone'))
            .forEach(element => {

                var myDropzone = new Dropzone(element, {
                    url: "{{route('createAssignmentFromFmvByClient')}}",
                    autoProcessQueue: false,
                    uploadMultiple: true,
                    parallelUploads: 100,
                    maxFiles: 100,
                    addRemoveLinks: true,
                    headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                    init : function () {
                        //myDropzone = this;

                        document.getElementById('updateAuthorization').addEventListener("click", function (e) {
                            e.preventDefault();
                            myDropzone.processQueue();
                        });

                    },
                    sending: function(file, xhr, formData){
                        blockContainer();

                    },
                    success: function(file, response){
                        if(response.status){
                            //console.log(response)
                            unBlockContainer();
                            Swal.fire({
                                title: "Good job!",
                                text: "Your pictures have been submitted!",
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
                            unBlockContainer();
                        }
                    }
                });
            })
    }

</script>
@endpush
@endsection