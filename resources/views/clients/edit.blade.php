@extends('layouts.masterHorizontal')

@section('title','List Clients - InPlace Auction')

@push('page-style')
<style>
    /*.table-responsive{
        overflow-x: hidden;
    }*/
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Clients</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item active">
                                    <a href="{{ route('getClients') }}">All Clients</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body container">
            <!-- Add Clients Form -->
            <section id="update-client-container">
                <div class="row">
                    <!--<div class="col-md-4 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-12">
                                    <h6 class="py-50">Actions</h6>
                                </div>
                                @if(!empty($client->IMAGE))
                                <div class="col-12 text-center">
                                    <img class="img-fluid" src="{{ asset('storage/client_images/'.$client->IMAGE) }}" alt="Logo">
                                </div>
                                @endif
                                <div class="list-group list-group-flush">
                                    <a href="{{ route('addContactClient', $client->CLIENT_ID) }}" class="list-group-item list-group-item-action border-0 d-flex">
                                        <div class="list-icon">
                                            <i class="badge-circle badge-circle-light-secondary bx bx-plus-circle mr-1 text-primary"></i>
                                        </div>
                                        <div class="list-content">
                                            <h5>Add Contact</h5>
                                            <p class="mb-0">
                                                Adds contact to the client
                                            </p>
                                        </div>
                                    </a>
                                    <a href="#basic-datatable" class="list-group-item list-group-item-action border-0 d-flex">
                                        <div class="list-icon">
                                            <i class="badge-circle badge-circle-light-secondary bx bxs-show mr-1"></i>
                                        </div>
                                        <div class="list-content">
                                            <h5>View Contact List</h5>
                                            <p class="mb-0">View associated contacts</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!--<div class="col-12 text-center">
                      <img class="img-fluid" src="{{ $client->imageSignedUrl }}" alt="Logo">
                    </div>-->
                    <div class="col-12 addContainer">
                        <div class="card">
                            <!--<div class="card-header">
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="updateAddForm" action="{{ route('updateClientAjax') }}" method="post">
                                        <input type="hidden" name="client_id" value="{{ $client->CLIENT_ID }}">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Status Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>Approved</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="approved" id="approved">
                                                                <option value="1" @if($client->APPROVED === 1) selected @endif>Approved</option>
                                                                <option value="0" @if($client->APPROVED === 0) selected @endif>Not Approved</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Status</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" id="status" name="status" id="customSelectStatus">
                                                                <option value="1" @if($client->STATUS === 1) selected @endif>Active</option>
                                                                <option value="0" @if($client->STATUS === 1) selected @endif>Not Active</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
                                                            <input type="text" id="first_name" class="form-control" name="first_name" placeholder="first_name" value="{{ $client->FIRSTNAME }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Last Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Last Name" value="{{ $client->LASTNAME }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="company_name" class="form-control" name="company_name" placeholder="Company Name" value="{{ $client->COMPANY }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Nick Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="nick_name" class="form-control" name="nick_name" placeholder="Nick Name" value="{{ $client->NICKNAME }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Address</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="address" class="form-control" name="address" placeholder="Address" value="{{ $client->ADDRESS1 }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="city" class="form-control" name="city" placeholder="City" value="{{$client->CITY}}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="state" class="form-control" name="state" placeholder="State" value="{{ $client->STATE }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="postalCode" class="form-control" name="postalCode" placeholder="Zip" value="{{ $client->ZIP }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Email</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="email" id="email" class="form-control" name="email" placeholder="Email" value="{{ $client->EMAIL }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Mobile</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="number" id="mobile" class="form-control" name="mobile" placeholder="Mobile" value="{{ $client->PHONE }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Fax</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="number" id="fax" class="form-control" name="fax" placeholder="Fax" value="{{ $client->FAX }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Cell Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="number" id="phone" class="form-control" name="phone" placeholder="Cell Phone" value="{{ $client->CELL }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 py-1">
                                                    <h6 class="py-50">Commission Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>Commission</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="commission" class="form-control" name="commission" placeholder="Commission" value="{{ $client->DEFAULT_COMM_RATE }}">
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
                                                            <input type="text" id="userName" class="form-control" name="userName" placeholder="Username" value="{{$client->USERNAME}}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Marketing Emails</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" id="marketingEmails" name="marketingEmails">
                                                                <option value="NONE" @if($client->MKT_EMAIL_FREQ == 'NONE') selected @endif>Do Not Send Marketing Emails</option>
                                                                <option value="WEEKLY" @if($client->MKT_EMAIL_FREQ == 'WEEKLY') selected @endif>Weekly</option>
                                                                <option value="2XMONTH" @if($client->MKT_EMAIL_FREQ == '2XMONTH') selected @endif>Twice Monthly</option>
                                                                <option value="MONTH" @if($client->MKT_EMAIL_FREQ == 'MONTH') selected @endif>Monthly</option>
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
                                                    <button type="button" id="updateClient" class="btn btn-primary mr-1 mb-1">Update Client</button>
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

            <!-- Contact List -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Contacts</h4>
                                <span style="float: right;">
                                     <button type="button" onclick="location.href='{{ route('addContactClient', $client->CLIENT_ID) }}'" class="btn btn-primary mr-1 mb-1">Add Contacts</button>
                                </span>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table dataTable zero-configuration">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Marketing Emails</th>
                                                <th>Contact Info</th>
                                                <th>Username</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($client->contacts) > 0)
                                                @foreach( $client->contacts as $contact )
                                                    <tr>
                                                        <td><a href="#">{{ $contact->FIRSTNAME }} {{ $contact->LASTNAME }}</a></td>
                                                        <td><a href="mailto:{{ $contact->EMAIL }}">{{ $contact->EMAIL }}</a> </td>
                                                        <td>{{ $contact->MKT_EMAIL_FREQ }}</td>
                                                        <td>{{ $contact->PHONE }}</td>
                                                        <td>{{ $contact->USERNAME }}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                              <tr><td colspan="5" class="text-center">No Contacts</td></tr>
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
        </div>

    </div>
    @push('page-js')
        <script>
            $(document).ready(function(){
                var updateClientContainer = $('#update-client-container');
                var clientForm = $('#updateAddForm'); // Form

                /************************************* Block Unblock Container *************************************/

                function blockClientAddContainer(){

                    updateClientContainer.block({
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
                function unBlockClientAddContainer(){
                    updateClientContainer.unblock();
                }

                /********************************** Create Client Submission *************************************************/

                $('#updateClient').click(function() { // Even for Form Submit.

                    blockClientAddContainer();
                    $.ajax({
                        url: "{{route('updateClientAjax')}}",
                        type: "POST",
                        dataType: "json",
                        data: clientForm.serialize(),
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                //console.log(response)
                                unBlockClientAddContainer();
                                Swal.fire({
                                    title: "Good job!",
                                    text: "Client updated!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });

                            } else {
                                unBlockClientAddContainer();
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            unBlockClientAddContainer();
                        }
                    });
                });

            });
        </script>
    @endpush
@endsection
