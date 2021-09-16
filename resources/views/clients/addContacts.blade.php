@extends('layouts.masterHorizontal')

@section('title','Add Contact Clients - InPlace Auction')

@push('page-style')
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">{{ $client->COMPANY }}</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item active">Add Contact
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('showClient', $client->CLIENT_ID) }}">Back to Client</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body container">
            <!-- Add Clients Form -->
            <section id="add-contact-client-container">
                <div class="row match-height">
                    <div class="col-12 addContainer">
                        <div class="card">
                            <!--<div class="card-header">
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="clientAddContact" action="{{ route('addClientContactAjax') }}" method="post">
                                        <input type="hidden" name="client_id" value="{{ $client->CLIENT_ID }}">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12 py-1">
                                                    <h6 class="py-50">Basic Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>First Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="first_name" class="form-control" name="first_name" placeholder="First Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Last Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Nick Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="nick_name" class="form-control" name="nick_name" placeholder="Nick Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Email</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="email" id="email" class="form-control" name="email" placeholder="Email">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Mobile</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="number" id="phone" class="form-control" name="phone" placeholder="Mobile">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Cell Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="number" id="mobile" class="form-control" name="mobile" placeholder="Cell Phone">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 py-1">
                                                    <h6 class="py-50">Account Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>UserName</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="username" class="form-control" name="username" placeholder="Username">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Marketing Emails</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="marketingEmails" id="marketingEmails">
                                                                <option value="NONE">Do Not Send Marketing Emails</option>
                                                                <option value="WEEKLY">Weekly</option>
                                                                <option value="2XMONTH" selected="">Twice Monthly</option>
                                                                <option value="MONTHLY">Monthly</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Password</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Confirm Password</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Confirm Password">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="button" id="createClientContact" class="btn btn-primary mr-1 mb-1">Create Contact</button>
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
    @push('page-js')
        <script>
            $(document).ready(function(){
                var addClientContactContainer = $('#add-contact-client-container');
                var clientContactForm = $('#clientAddContact'); // Form

                /************************************* Block Unblock Container *************************************/

                function blockClientContactAddContainer(){

                    addClientContactContainer.block({
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
                function unBlockClientContactAddContainer(){
                    addClientContactContainer.unblock();
                }

                /********************************** Create Client Submission *************************************************/

                $('#createClientContact').click(function() { // Even for Form Submit.

                    blockClientContactAddContainer();
                    $.ajax({
                        url: "{{route('addClientContactAjax')}}",
                        type: "POST",
                        dataType: "json",
                        data: clientContactForm.serialize(),
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                //console.log(response)
                                unBlockClientContactAddContainer();
                                Swal.fire({
                                    title: "Good job!",
                                    text: "Client contact added!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.href = '/client/'+response.clientId;
                                    }
                                });

                            } else {
                                unBlockClientContactAddContainer();
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            unBlockClientContactAddContainer();
                        }
                    });
                });

            });
        </script>
    @endpush
@endsection
