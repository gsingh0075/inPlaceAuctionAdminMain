@extends('layouts.masterHorizontal')

@section('title','Edit Assignment - InPlace Auction')

@push('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
    <style>
        #contractorMap {
            height: 500px;
        }

        #contractorMarkerContent p {
            color: #000;
            margin-bottom: 10px;
        }

        .progress {
            height: 1.4rem;
        }

        .progress .progress-bar {
            border-radius: 0;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content-header container-fluid">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Assignment</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('getAssignment') }}">List</a>
                                </li>
                                <li class="breadcrumb-item active">Edit
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body container-fluid">
            <div class="col-12 p-0 mb-2">
                <div class="progress">
                    @if(isset($assignment->assignment_id) && !empty($assignment->assignment_id))
                        <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="15" aria-valuemin="0"
                             aria-valuemax="100"> Assignment Created
                        </div>
                       @php $recoveryStatus = false; $soldStatus = false; $customerPaid = false; $clientPaid = false; @endphp
                       @if(isset($assignment->items) && count($assignment->items) > 0 )
                           @foreach($assignment->items as $item)
                               @if(!empty($item->itemContractor))
                                   @php $recoveryStatus = true; @endphp
                               @endif
                               @if(!empty($item->invoiceAuth))
                                       @php $soldStatus = true; @endphp
                                    @if(isset($item->invoiceAuth->invoice) && !empty($item->invoiceAuth->invoice))
                                        @if($item->invoiceAuth->invoice->paid === 1)
                                            @php $customerPaid = true; @endphp
                                        @endif
                                        @if(isset($item->invoiceAuth->invoice->remittance) && !empty($item->invoiceAuth->invoice->remittance))
                                             @if($item->invoiceAuth->invoice->remittance->SENT === 1)
                                                   @php $clientPaid = true; @endphp
                                             @endif
                                        @endif
                                   @endif
                               @endif
                           @endforeach
                            @if($recoveryStatus)
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="30"
                                 aria-valuemin="0" aria-valuemax="100">Item Recovery
                            </div>
                                 @if($soldStatus)
                                       <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" aria-valuenow="30"
                                            aria-valuemin="0" aria-valuemax="100">Sold Items
                                       </div>
                                       @if($customerPaid)
                                           <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20"
                                                aria-valuemin="0" aria-valuemax="100">Customer Paid
                                           </div>
                                           @if($clientPaid)
                                               <div class="progress-bar bg-success" role="progressbar" style="width: 20%" aria-valuenow="20"
                                                    aria-valuemin="0" aria-valuemax="100">Remitted Client
                                               </div>
                                           @endif
                                       @endif
                                 @endif

                            @endif
                       @endif
                    @endif
                </div>
            </div>
            <!-- Edit Assignment Form -->

            <div class="row">
                <div class="col-3">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
                        <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</a>
                        <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</a>
                        <a class="nav-link" id="v-pills-settings-tab" data-toggle="pill" href="#v-pills-settings" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</a>
                    </div>
                </div>
                <div class="col-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">...</div>
                        <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
                        <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
                        <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
                    </div>
                </div>
            </div>


            <section id="floating-label-layouts">
                <div class="row match-height">
                    <div class="col-12 addContainer" id="fmv-update-container">
                        <div class="card">
                            <!--<div class="card-header">
                             </div>-->
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="assignmentUpdateData" action="#" method="post"
                                          enctype="multipart/form-data">
                                        <input type="hidden" name="assignment_id"
                                               value="{{ $assignment->assignment_id }}">
                                        <div class="form-body">
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="py-50">Client Details</h6>
                                                </div>
                                                <div class="col-12">
                                                    <div class="row">
                                                        <div class="col-md-3 col-12">
                                                            <b>Client's Name</b>
                                                        </div>
                                                        <div class="col-md-9 col-12">
                                                            @if(isset($assignment->client->clientInfo)) {{ $assignment->client->clientInfo->FIRSTNAME }} {{ $assignment->client->clientInfo->LASTNAME }} @endif
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <b>Client's Company</b>
                                                        </div>
                                                        <div class="col-md-9 col-12">
                                                            @if(isset($assignment->client->clientInfo)) {{ $assignment->client->clientInfo->COMPANY }} @endif
                                                        </div>
                                                        <div class="col-md-3 col-12">
                                                            <b>Client's Email</b>
                                                        </div>
                                                        <div class="col-md-9 col-12">
                                                            @if(isset($assignment->client->clientInfo)) {{ $assignment->client->clientInfo->EMAIL }} @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Lease Information -->
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="py-50">Lease Information</h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_full_name">Lessees's Full Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="ls_full_name" class="form-control"
                                                                   name="ls_full_name" placeholder="Full Name"
                                                                   value="{{ $assignment->ls_full_name }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_company">Lessees's Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="ls_company" class="form-control"
                                                                   name="ls_company" placeholder="Company Name"
                                                                   value="{{ $assignment->ls_company }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_address1">Lessees's Address</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="ls_address1" class="form-control"
                                                                   name="ls_address1" placeholder="Address"
                                                                   value="{{ $assignment->ls_address1 }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_city">Lessees's City</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="ls_city" class="form-control"
                                                                   name="ls_city" placeholder="City"
                                                                   value="{{ $assignment->ls_city }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_state">Lessees's State</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="ls_state" class="form-control"
                                                                   name="ls_state" placeholder="State"
                                                                   value="{{ $assignment->ls_state }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_zip">Lessees's Zip</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="ls_zip" class="form-control"
                                                                   name="ls_zip" placeholder="Zip"
                                                                   value="{{ $assignment->ls_zip }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="ls_buss_phone">Lessees's Business Phone</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="ls_buss_phone" class="form-control"
                                                                   name="ls_buss_phone" placeholder="Phone"
                                                                   value="{{ $assignment->ls_buss_phone }}">
                                                        </div>
                                                        <!--<div class="col-12">
                                                            <button type="button" class="btn btn-primary mr-1 mb-1" id="findContractor" data-toggle="modal" data-target="#findContractorMap">Find Near By Contractor</button>
                                                        </div>-->
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Lease Information -->

                                            <!-- Lease Information -->
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="py-50">Lease Details</h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="lease_numbr">Lease Number</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="lease_numbr" class="form-control"
                                                                   name="lease_numbr" placeholder="Lease Number"
                                                                   value="{{ $assignment->lease_nmbr }}">
                                                        </div>
                                                    <!--<div class="col-md-4 col-12">
                                                            <label>Accelerated Balance</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="outstanding_bal" class="form-control" name="outstanding_bal" placeholder="Accelerated Balance" value="{{ $assignment->outstanding_bal }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Late Charge</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="late_charges" class="form-control" name="late_charges" placeholder="Late Charge" value="{{ $assignment->late_charges }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Net Book Investment</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="nbi" class="form-control" name="nbi" placeholder="Net Book Investment" value="{{ $assignment->nbi }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Aging Start Date</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="aging_start_dt" class="form-control" name="aging_start_dt" placeholder="Start Date" value="{{ $assignment->aging_start_dt }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Purchase Option</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="purch_option" class="form-control" name="purch_option" placeholder="Purchase Option" value="{{ $assignment->purch_option }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Monthly Payment</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="monthly_payment" class="form-control" name="monthly_payment" placeholder="Monthly Payment" value="{{ $assignment->monthly_payment }}">
                                                        </div>-->
                                                        <div class="col-md-4 col-12">
                                                            <label for="dt_lease_inception_month">Date of Lease
                                                                Inception</label>
                                                        </div>
                                                        <div class="col-md-4 form-group col-12">
                                                            <select class="custom-select form-control"
                                                                    name="dt_lease_inception_month"
                                                                    id="dt_lease_inception_month">
                                                                <!-- Dynamically we will load -->
                                                                <option value="0">Select Month</option>
                                                                @foreach($months as $key => $val)
                                                                    @if(empty($assignment->dt_lease_inception))
                                                                        <option value="{{ $key }}">{{ $val }}</option>
                                                                    @else
                                                                        <option value="{{ $key }}"
                                                                                @if($key == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->dt_lease_inception)->format('m')) selected @endif>{{ $val }}</option>
                                                                    @endif

                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 form-group col-12">
                                                            <select class="custom-select form-control"
                                                                    name="dt_lease_inception_year"
                                                                    id="dt_lease_inception_year">
                                                                @php
                                                                    $latestYear = date('Y');
                                                                    $earliestYear = 1980;
                                                                @endphp
                                                                <option value="">Select Year</option>
                                                                @foreach( range( $latestYear, $earliestYear ) as $i  )
                                                                    @if(empty($assignment->dt_lease_inception))
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @else
                                                                        <option value="{{ $i }}"
                                                                                @if($i == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->dt_lease_inception)->format('Y')) selected @endif>{{ $i }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="lease_term">Lease Term</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="lease_term" class="form-control"
                                                                   name="lease_term" placeholder="Lease Term"
                                                                   value="{{ $assignment->lease_term }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Lease Information -->
                                            <!-- Assignment Information -->
                                            <div class="row mt-2">
                                                <div class="col-12">
                                                    <h6 class="py-50">Assignment Information</h6>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="dt_stmp">Assignment date</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="dt_stmp" class="form-control"
                                                                   name="dt_stmp" placeholder="Date"
                                                                   value="{{ $assignment->dt_stmp }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="lst_upd">Assignment Last Updated</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="lst_upd" class="form-control"
                                                                   name="lst_upd" placeholder="Date"
                                                                   value="{{ $assignment->lst_upd }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="isopen">Assignment Status</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="isopen"
                                                                    id="isopen">
                                                                <option value="1"
                                                                        @if($assignment->isopen == 1) selected @endif>
                                                                    Open
                                                                </option>
                                                                <option value="0"
                                                                        @if($assignment->isopen == 0) selected @endif>
                                                                    Close
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="approved">Approval Status</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="approved"
                                                                    id="approved">
                                                                <option value="1"
                                                                        @if($assignment->active == 1) selected @endif>
                                                                    Approved
                                                                </option>
                                                                <option value="0"
                                                                        @if($assignment->active == 0) selected @endif>
                                                                    Not Approved
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="recovered">Recovered ?</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="recovered"
                                                                    id="recovered">
                                                                <option value="1"
                                                                        @if($assignment->recovered == 1) selected @endif>
                                                                    Recovered
                                                                </option>
                                                                <option value="0"
                                                                        @if($assignment->recovered == 0) selected @endif>
                                                                    Not Recovered
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="recovery_month">Recovery Date</label>
                                                        </div>
                                                        <div class="col-md-3 form-group col-12">
                                                            <select class="custom-select form-control"
                                                                    name="recovery_month" id="recovery_month">
                                                                <option value="0">Month</option>
                                                                @foreach($months as $key => $val)
                                                                    @if(empty($assignment->recovery_dt))
                                                                        <option value="{{ $key }}">{{ $val }}</option>
                                                                    @else
                                                                        <option value="{{ $key }}"
                                                                                @if($key == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->recovery_dt)->format('m')) selected @endif>{{ $val }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 form-group col-12">
                                                            <select class="custom-select form-control"
                                                                    name="recovery_day">
                                                                <option value="">Day</option>
                                                                @for($m=1; $m<=31; $m++)
                                                                    @if(!empty($assignment->recovery_dt))
                                                                        <option value="{{ $m }}"
                                                                                @if($m == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->recovery_dt)->format('d')) selected @endif>{{ $m }}</option>
                                                                    @else
                                                                        <option value="{{ $m }}">{{ $m }}</option>
                                                                    @endif
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 form-group col-12">
                                                            <select class="custom-select form-control"
                                                                    name="recovery_year" id="recovery_year">
                                                                <option value="">Year</option>
                                                                @foreach( range( $latestYear, $earliestYear ) as $i  )
                                                                    @if(empty($assignment->recovery_dt))
                                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                                    @else
                                                                        <option value="{{ $i }}"
                                                                                @if($i == \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->recovery_dt)->format('Y')) selected @endif>{{ $i }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="client_note">Client Notes</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea data-length="20"
                                                                      class="form-control char-textarea active"
                                                                      name="client_note" id="client_note" rows="3"
                                                                      placeholder="Client notes"
                                                                      style="color: rgb(48, 65, 86);">{{ $assignment->client_note }}</textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="voluntary">Voluntary ?</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <select class="custom-select form-control" name="voluntary"
                                                                    id="voluntary">
                                                                <option value="1"
                                                                        @if($assignment->voluntary == 1) selected @endif>
                                                                    Voluntary
                                                                </option>
                                                                <option value="0"
                                                                        @if($assignment->voluntary == 0) selected @endif>
                                                                    InVoluntary
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="res_repo">Reason </label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_repo == 1) checked
                                                                           @endif name="res_repo" id="res_repo">
                                                                    <label for="res_repo">Repossession</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_coll == 1) checked
                                                                           @endif name="res_coll" id="res_coll">
                                                                    <label for="res_coll">Collection</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_skip == 1) checked
                                                                           @endif name="res_skip" id="res_skip">
                                                                    <label for="res_skip">Skip Trace</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_rmkt == 1) checked
                                                                           @endif name="res_rmkt" id="res_rmkt">
                                                                    <label for="res_rmkt">Remarketing</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_app == 1) checked
                                                                           @endif name="res_app" id="res_app">
                                                                    <label for="res_app">Appraisal</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_fmv == 1) checked
                                                                           @endif name="res_fmv" id="res_fmv">
                                                                    <label for="res_fmv">Fair Market Value</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_drive == 1) checked
                                                                           @endif name="res_drive" id="res_drive">
                                                                    <label for="res_drive">Drive by Research</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_inv == 1) checked
                                                                           @endif name="res_inv" id="res_inv">
                                                                    <label for="res_inv">Investigation</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_knock == 1) checked
                                                                           @endif name="res_knock" id="res_knock">
                                                                    <label for="res_knock">Knock and Demand</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_ins == 1) checked
                                                                           @endif name="res_ins" id="res_ins">
                                                                    <label for="res_ins">Inspection</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="checkbox">
                                                                    <input type="checkbox" class="form-control"
                                                                           value="1"
                                                                           @if($assignment->res_eol == 1) checked
                                                                           @endif name="res_eol" id="res_eol">
                                                                    <label for="res_eol">End of Lease</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label>Prior Contact Status </label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <fieldset>
                                                                <div class="radio">
                                                                    <input type="radio" class="form-control" value="1"
                                                                           @if($assignment->prior_contact == 1) checked
                                                                           @endif name="res_repo" id="res_repo">
                                                                    <label for="res_repo">Make Prior Contact</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12 offset-md-4">
                                                            <fieldset>
                                                                <div class="radio">
                                                                    <input type="radio" class="form-control" value="0"
                                                                           @if($assignment->prior_contact == 0) checked
                                                                           @endif name="res_repo" id="res_repo">
                                                                    <label for="res_repo">Visit Unanounced/Do not make
                                                                        Prior Contact</label>
                                                                </div>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Assignment Information -->
                                            <div class="row mt-2">
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-primary mr-1 mb-1"
                                                            id="updateAssignment">Update Assignment
                                                    </button>
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
            <!--Assignment Form Files -->

            <!-- Item Form -->
            <div class="modal fade text-left" id="itemAddFilesModal" data-backdrop="static" data-keyboard="false"
                 tabindex="-1" role="dialog" aria-labelledby="itemAddFilesModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel33">Add Files</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <form action="{{ route('addItemAjax') }}" method="post" enctype="multipart/form-data"
                              id="addFilesAssignment">
                            <input type="hidden" name="assignment_id" id="assignment_id"
                                   value="{{ $assignment->assignment_id }}">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="dropzone" id="assignmentFiles">
                                            <div class="dz-message">Drop Files Here To Upload</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="button" id="addFileFormModal" class="btn btn-primary ml-1">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Add</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add Form Files End -->

            <!-- Communication Section -->
            <section id="chats">
                <div class="row">
                    <div class="col-md-6 col-12 widget-chat-card">
                        <div class="widget-chat widget-chat-messages">
                            <div class="card">
                                <div class="card-header" style="color: #ff5b5c;">
                                    Client Communication
                                </div>
                                <div class="card-content">
                                    <div class="card-body widget-chat-container widget-chat-scroll" id="public-chat"
                                         style="background-color: #e5e9ed; border-radius: 0">
                                        <div class="chat-content">
                                            @if(isset($assignment->communicationsPublic) && (count($assignment->communicationsPublic)>0))
                                                @foreach($assignment->communicationsPublic as $communication)
                                                    @if($communication->posted_by == 'ADMIN')
                                                        <div class="chat chat-left">
                                                            <div class="chat-body">
                                                                <div class="chat-message">
                                                                    <p>{{ $communication->communication_note }}</p>
                                                                    <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="chat">
                                                            <div class="chat-body">
                                                                <div class="chat-message">
                                                                    <p>{{ $communication->communication_note }}</p>
                                                                    <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="badge badge-pill badge-light-secondary my-1">No conversation
                                                    found
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <input type="hidden" name="client_id" id="client_id"
                                               value="{{ $assignment->client->clientInfo->CLIENT_ID }}">
                                        <textarea class="form-control char-textarea active" name="public_notes"
                                                  id="public_notes" rows="3" placeholder="Note seen by client"
                                                  style="color: rgb(48, 65, 86);"></textarea>
                                        <button type="button" class="btn btn-primary mt-1 saveCommunication"
                                                data-type="public" id="updatePublicNotes">Save Note
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12 widget-chat-card">
                        <div class="widget-chat widget-chat-messages">
                            <div class="card">
                                <div class="card-header" style="color: #39da8a;">
                                    Internal Communication
                                </div>
                                <div class="card-content">
                                    <div class="card-body widget-chat-container widget-chat-scroll" id="private-chat"
                                         style="background-color: #e5e9ed; border-radius: 0;">
                                        <div class="chat-content">
                                            @if(isset($assignment->communicationsPrivate) && (count($assignment->communicationsPrivate)>0))
                                                @foreach($assignment->communicationsPrivate as $communication)
                                                    @if($communication->posted_by == 'ADMIN')
                                                        <div class="chat chat-left">
                                                            <div class="chat-body">
                                                                <div class="chat-message">
                                                                    <p>{{ $communication->communication_note }}</p>
                                                                    <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="chat">
                                                            <div class="chat-body">
                                                                <div class="chat-message">
                                                                    <p>{{ $communication->communication_note }}</p>
                                                                    <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="badge badge-pill badge-light-secondary my-1">No conversation
                                                    found
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <textarea class="form-control char-textarea active" name="private_notes"
                                                  id="private_notes" rows="3" placeholder="Internal Note"
                                                  style="color: rgb(48, 65, 86);"></textarea>
                                        <button type="button" class="btn btn-primary mt-1 saveCommunication"
                                                data-type="private" id="updatePublicNotes">Save Note
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- End Communication Section -->

            <!-- Files List -->
            <section id="assignment-files-containers">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Files</h4>
                                <span style="float: right;">
                                     <button type="button" class="btn btn-primary mr-1 mb-1" id="addFiles"
                                             data-toggle="modal" data-target="#itemAddFilesModal">Add Files</button>
                                </span>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-striped zero-configuration">
                                            <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Type</th>
                                                <th>Upload Date</th>
                                                <th>View</th>
                                                <th>Delete</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($assignment->files) > 0)
                                                @foreach( $assignment->files as $file )
                                                    <tr>
                                                        <td>{{ $file->filename }}</td>
                                                        <td>{{ $file->fileType }} </td>
                                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $file->updated_at)->format('j F, Y') }}</td>
                                                        <td><a href="{{ $file->fileSignedUrl }}"
                                                               target="_blank">View</a></td>
                                                        <td><a href="javascript:void(0)" class="deleteFile"
                                                               data-attr-link="{{ route('deleteFileFmv', $file->id) }}">Delete</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">No Files</td>
                                                </tr>
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

            @include('assignment.editItems')
            @include('assignment.editItemsAuthorizations')
            @include('assignment.editClientInvoice')
            @include('assignment.editCustomerInvoice')
            @include('assignment.editCustomerRemittance')

        </div>
    </div>


    @push('page-vendor-js')
        <script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
        <script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDCU5X_ubbb82-b_S5MMxKuiSazk9YvpMU"></script>
    @endpush
    @push('page-js')
        <script>

            /** Ready Function ***/
            var addItemBidModal = $('#addItemBidModal');
            var itemBidId = $('#item_bid_id');
            var addItemExpenseModal = $('#addItemExpenseModal');
            var itemExpenseId = $('#item_expense_id');
            var authorizeContractorInput = $('#authorize_contractor_id');
            var authorizeContractorModal = $('#authorizeModal');

            var customerInvoicePaidBtn = $('#customerInvoicePaidBtn');
            var customerInvoicePaidModal = $('#customerInvoicePaidModal');
            var customerInvoiceId = $('#customer_invoice_id');

            var clientInvoicePaidBtn = $('#clientInvoicePaidBtn');
            var clientInvoicePaidModal = $('#clientInvoicePaidModal');
            var clientInvoiceId = $('#client_invoice_id');


            $(document).ready(function () {

                $('#customer_id').select2({
                    placeholder: "Customer",
                    dropdownParent: addItemBidModal
                });

                $('#expense_type').select2({
                    placeholder: "Expense Type",
                    dropdownParent: addItemExpenseModal
                });

                $('#customer_date_paid').pickadate(); // Date Picker
                $('#client_date_paid').pickadate(); // Date Picker

                /****** Bid Modal Show **************/
                addItemBidModal.on('show.bs.modal', function (e) {
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    console.log(id);
                    itemBidId.val(id);
                });

                /******* Expense Modal Show ********/
                addItemExpenseModal.on('show.bs.modal', function (e) {
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    console.log(id);
                    itemExpenseId.val(id);
                });

                /******** Authorize Modal Show ***********/
                authorizeContractorModal.on('show.bs.modal', function (e) {
                    $('#findContractorMap').modal('hide');
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    let contractorEmail = btn.data('email');
                    console.log(id);
                    authorizeContractorInput.val(id);
                    $('#contractorSendEmail').val(contractorEmail);
                });

                /****** Customer Paid Modal Show **/
                clientInvoicePaidModal.on('show.bs.modal', function (e) {
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    let amount = btn.data('amount');
                    clientInvoiceId.val(id);
                    $('#originalClientInvoice').html('$' + amount);
                });

                /****** Client Paid Modal Show **/
                customerInvoicePaidModal.on('show.bs.modal', function (e) {
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    let amount = btn.data('amount');
                    customerInvoiceId.val(id);
                    $('#originalCustomerInvoice').html('$' + amount);
                })


            });

            /*** Mark Invoice as Paid Client **/
            clientInvoicePaidBtn.click(function () {

                console.log('Button clicked');
                var action = $(this).attr('data-action');

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'invoice_id': clientInvoiceId.val(),
                        'paid_date': $('#client_date_paid').val(),
                        'amount': $('#client_amount_paid').val(),
                        'type': $('#client_type_paid').val(),
                        'memo': $('#client_memo_paid').val()
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Invoice is marked as paid",
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

            /*** Mark Invoice as Paid Customer **/
            customerInvoicePaidBtn.click(function () {

                console.log('Button clicked');
                var action = $(this).attr('data-action');

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'invoice_id': customerInvoiceId.val(),
                        'paid_date': $('#customer_date_paid').val(),
                        'amount': $('#customer_amount_paid').val(),
                        'type': $('#customer_type_paid').val(),
                        'memo': $('#customer_memo_paid').val()
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Invoice is marked as paid",
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
                        //unBlockFMVContainer();
                    }
                });

            });
            /*** Authorize Contractors **/
            $('#authorizeContractorBtn').click(function () {

                console.log('Button clicked');
                var action = $(this).attr('data-action');

                let items = [];
                $('input[name="authorize_item[]"]:checked').each(function () {
                    items.push($(this).val());
                });
                console.log(items);

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'contractor_id': parseInt(authorizeContractorInput.val()),
                        'items': items,
                        'send_email': $('#contractorSendEmail').val(),
                        'type_of_pickup': $('#v_or_i').val(),
                        'special_instruction': $('#special_instructions').val(),
                        'additional_information': $('#additional_info').val(),
                        'terms': $('#terms').val(),
                        'method': $('#method').val(),
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Contractor is authorized added successfully!",
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
                        //unBlockFMVContainer();
                    }
                });


            });
            /******* Save Expense ********/
            $('#addItemExpense').click(function () {

                let form = $('#addItemExpenseAjax');
                let action = form.attr('action');

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'item_id': parseInt(itemExpenseId.val()),
                        'client_id': parseInt($('#client_id').val()),
                        'amount': parseInt($('#expense_amount').val()),
                        'expense_type': $('#expense_type').val(),
                        'chargeable': $('#expense_chargeable').val(),
                        'comments': $('#expense_comment').val(),
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Expense was added successfully!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                form[0].reset();
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
                        //unBlockFMVContainer();
                    }
                });

            });

            /*** Send Contractor Authorization *******/
            $('.sendContractorAuthorization').click(function () {

                var sendContractorLink = $(this).attr('data-attr-link');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send Authorization to Contractor",
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
                            url: sendContractorLink,
                            type: "GET",
                            dataType: "json",
                            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Sent!",
                                        text: "Authroization was sent!",
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
                                //unBlockFMVContainer();
                            }
                        });
                    }
                })

            });
            /*** Send Client Invoice *******/
            $('.sendClientInvoice').click(function () {

                var sendInvoiceLink = $(this).attr('data-attr-link');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send invoice to client",
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
                            url: sendInvoiceLink,
                            type: "GET",
                            dataType: "json",
                            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Sent!",
                                        text: "Invoice was sent!",
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
                                //unBlockFMVContainer();
                            }
                        });
                    }
                })

            });

            /*** Send Customer Invoice *******/
            $('.sendCustomerInvoice').click(function () {

                var sendInvoiceLink = $(this).attr('data-attr-link');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send invoice to customer",
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
                            url: sendInvoiceLink,
                            type: "GET",
                            dataType: "json",
                            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Sent!",
                                        text: "Invoice was sent!",
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
                                //unBlockFMVContainer();
                            }
                        });
                    }
                })

            });


            /****** Accept Bid *********/
            $('.accept_bid-item').click(function () {

                var bidDeleteUrl = $(this).attr('data-id');
                Swal.fire({
                    title: "Accept",
                    text: "This will generate invoice for the customer",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-s-cancel ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        console.log(bidDeleteUrl);

                        $.ajax({
                            url: bidDeleteUrl,
                            type: "GET",
                            dataType: "json",
                            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Good job!",
                                        text: "Bid was accepted successfully!",
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
                                //unBlockFMVContainer();
                            }
                        });
                    } else {
                        console.log('Cancelled hit');
                    }
                });

            });

            /******* Save Bid ********/
            $('#addItemBid').click(function () {

                let form = $('#addItemBidAjax');
                let action = form.attr('action');

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'item_id': parseInt(itemBidId.val()),
                        'customer_id': parseInt($('#customer_id').val()),
                        'amount': parseInt($('#bid_amount').val()),
                        'bid_comments': $('#bid_comment').val()
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Bid was added successfully!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                form[0].reset();
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
                        //unBlockFMVContainer();
                    }
                });

            });

            /****** Save Notes **/
            $('.saveCommunication').click(function () {

                var notes = '';
                var type = $(this).attr('data-type');
                if (type == 'public') {
                    notes = $('#public_notes').val();
                } else {
                    notes = $('#private_notes').val();
                }

                $.ajax({
                    url: "{{ route('saveCommunicationAssignment') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        'assignment_id': $('#assignment_id').val(),
                        'client_id': $('#client_id').val(),
                        'type': type,
                        'note': notes
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Note added successfully!",
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
                        //unBlockFMVContainer();
                    }
                });


            });
            var markers = [];
            var center = new google.maps.LatLng(30.29461050801138, 15.360816686284807);
            var map = new google.maps.Map(document.getElementById('contractorMap'), {
                zoom: 3,
                center: center,
                minZoom: 3,
                maxZoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            var infoWindow = new google.maps.InfoWindow;

            //Contractor Modal on show
            $('#findContractorMap').on('show.bs.modal', function (event) {

                $.ajax({
                    url: "{{ route('findNearByContractors') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        'assignment_id': $('#assignment_id').val()
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            console.log(response.data);
                            var bounds = new google.maps.LatLngBounds();
                            var mapData = response;

                            if (mapData.data.length >= 1) {
                                for (var i = 0; i < mapData.data.length; i++) {

                                    if (mapData.data[i].address_code.latitude !== '' && mapData.data[i].address_code.longitude !== '') {
                                        //console.log(mapData.data[i].address_code.latitude);
                                        var contr_LatLng = new google.maps.LatLng(parseFloat(mapData.data[i].address_code.latitude), parseFloat(mapData.data[i].address_code.longitude));
                                        bounds.extend(contr_LatLng);
                                        contractorMarker(contr_LatLng, mapData.data[i].contractor_id, mapData.data[i].name, mapData.data[i].type, $('#assignment_id').val());
                                    }
                                }

                            }

                            map.fitBounds(bounds);

                        } else {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        //unBlockFMVContainer();
                    }
                });

            });

            function contractorMarker(latLng, contractorID, title, type, assignmentId) {

                var iconType = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';

                if (type === 'A') {
                    iconType = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
                }

                var html = '<div id="contractorMarkerContent"><p>Loading........</p><div>';

                var marker = new google.maps.Marker({
                    map: map,
                    position: latLng,
                    title: title,
                    contractorID: contractorID,
                    assignmentId: assignmentId,
                    icon: {
                        url: iconType
                    }
                });

                console.log(marker);

                google.maps.event.addListener(marker, 'click', function () {

                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                    map.setCenter(marker.getPosition());

                    $.ajax({
                        url: "{{ route('contractorMarker') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            contractor_id: marker.contractorID,
                            assignment_id: marker.assignmentId,
                        },
                        headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                        success: function (result) {
                            if (result.success) {
                                $('#contractorMarkerContent').html(result.html);
                            } else {
                                $.each(result.errors, function (key, value) {
                                    toastr.error('Marker Loading Failed ' + value);
                                });
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                        }
                    });

                });
                markers.push(marker);


            }

            new PerfectScrollbar("#private-chat", {wheelPropagation: false});
            new PerfectScrollbar("#public-chat", {wheelPropagation: false});
            Dropzone.autoDiscover = false;

            /******* Drop Zone ********************************************/

            $('#addFileFormModal').click(function () {
                myDropzone.processQueue(); // If files are there.
            });
            $('#assignmentFiles').dropzone({
                url: "{{route('addAssignmentFiles')}}",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 100,
                maxFiles: 100,
                addRemoveLinks: true,
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                init: function () {
                    myDropzone = this;
                },
                sending: function (file, xhr, formData) {
                    //blockFMVContainer();
                    formData.append('assignment_id', $('#assignment_id').val());
                },
                success: function (file, response) {
                    if (response.status) {
                        //console.log(response)
                        //unBlockFMVContainer();
                        Swal.fire({
                            title: "Good job!",
                            text: "Files added!",
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
                        //unBlockFMVContainer();
                    }
                }
            });

        </script>
    @endpush
@endsection
