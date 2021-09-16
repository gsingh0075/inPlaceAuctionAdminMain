@extends('layouts.masterHorizontal')

@section('title','Add Client - InPlace Auction')

@push('page-style')
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
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active"><a href="{{ route('getClients') }}">All Clients</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body container addContainer">
            <!-- Add Clients Form -->
            <section id="add-client-container">
                <div class="row match-height">
                    <div class="col-12">
                        <div class="card">
                            <!--<div class="card-header">
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="clientAddForm" action="{{ route('addClientAjax') }}" method="post">
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
                                                                <option value="1">Approved</option>
                                                                <option value="0">Not Approved</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Status</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" id="status" name="status">
                                                                <option value="1">Active</option>
                                                                <option value="0">Not Active</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Basic Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>First Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="first-name-floating-icon" id="first_name" class="form-control" name="first_name" placeholder="First Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Last Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Nick Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="nick_name" class="form-control" name="nick_name" placeholder="Nick Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Address</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="address" class="form-control" name="address" placeholder="Address">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="city" class="form-control" name="city" placeholder="City">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="state" class="form-control" name="state" placeholder="State">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="postalCode" class="form-control" name="postalCode" placeholder="Zip">
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
                                                            <input type="number" id="mobile" class="form-control" name="mobile" placeholder="Mobile">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Fax</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="number" id="fax" class="form-control" name="fax" placeholder="Fax">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Call Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="number" id="phone" class="form-control" name="phone" placeholder="Cell Phone">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Commission Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>Commission</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="commission" class="form-control" name="commission" placeholder="Commission">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Account Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label>UserName</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="userName" class="form-control" name="userName" placeholder="Username">
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
                                                    <button type="button" id="createClient" class="btn btn-primary mr-1 mb-1">Create Client</button>
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
        </div>

    </div>
@push('page-js')
<script>
    $(document).ready(function(){
        var addClientContainer = $('#add-client-container');
        var clientForm = $('#clientAddForm'); // Form

        /************************************* Block Unblock Container *************************************/

        function blockClientAddContainer(){

            addClientContainer.block({
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
            addClientContainer.unblock();
        }

        /********************************** Create Client Submission *************************************************/

        $('#createClient').click(function() { // Even for Form Submit.

            blockClientAddContainer();
            $.ajax({
                url: "{{route('addClientAjax')}}",
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
                            text: "Client added!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location = "{{ route('getClients') }}"
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
