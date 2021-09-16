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
                        <h5 class="content-header-title float-left pr-1 mb-0">Customer</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('getAllCustomers') }}">Back to all Customer</a>
                                </li>
                                <li class="breadcrumb-item active">Add Customer
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
                    <div class="col-12" id="customer-add-container">
                        <div class="card">
                            <!--<div class="card-header">
                                <h6>New FMV</h6>
                            </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="customerAddData" action="{{ route('addCustomerAjax') }}" method="post">
                                        <div class="form-body">
                                            <!-- Request By Details -->
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="py-50">Customer Details</h6>
                                                </div>
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="status">Status</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="status" id="status">
                                                                <option value="1">Active</option>
                                                                <option value="0">InActive</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="first_name">First Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="first_name" class="form-control" name="first_name" placeholder="First Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="last-name">Last Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="last-name" class="form-control" name="last_name" placeholder="Last Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="title">Contact Title</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="title" class="form-control" name="title" placeholder="Title">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="company-name">Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="company-name" class="form-control" name="company-name" placeholder="Company Name">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="address">Address</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="address" class="form-control" name="address" placeholder="Address">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="city">City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="city" class="form-control" name="city" placeholder="City">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="state">State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <select class="custom-select form-control" name="state" id="state">
                                                                <option value="">Please Select</option>
                                                               @if(isset($states) && !empty($states))
                                                                   @foreach($states as $st)
                                                                       <option value="{{ $st->STATE_ID }}">{{ $st->STATE }}</option>
                                                                   @endforeach
                                                               @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="zip">Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="zip" class="form-control" name="zip" placeholder="Zip">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="email">Email</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="email" class="form-control" name="email" placeholder="Email">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="phone">Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="phone" class="form-control" name="phone" placeholder="Phone">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="fax">Fax</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="fax" class="form-control" name="fax" placeholder="Fax">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="cell_phone">Cell Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="cell_phone" class="form-control" name="cell_phone" placeholder="Cell Phone">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Request By Details -->
                                            <!-- Additional Comments Section -->
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <input type="button" class="btn btn-primary mr-1 mb-1" id="createCustomer" value="Create Customer">
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

    var customerForm = $('#customerAddData'); // Form
    var customerContainer = $('#customer-add-container'); // Main Container

    $(document).ready(function() {

        $('#createCustomer').click(function(){ // Even for Form Submit.

                    blockExt(customerContainer, $('#waitingMessage'));
                    $.ajax({
                        url: "{{route('addCustomerAjax')}}",
                        type: "POST",
                        dataType: "json",
                        data: customerForm.serialize(),
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                unBlockExt(customerContainer);
                                Swal.fire({
                                    title: "Good job!",
                                    text: "Customer added!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location = "{{ route('getAllCustomers') }}"
                                    }
                                });
                            } else {
                                unBlockExt(customerContainer);
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                           unBlockExt(customerContainer)
                        }
                    });

           });


    });

</script>
@endpush
@endsection
