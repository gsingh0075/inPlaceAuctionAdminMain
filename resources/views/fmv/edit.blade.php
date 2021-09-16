@extends('layouts.masterHorizontal')

@section('title','List FMV - InPlace Auction')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
    .card-links{
        float: left;
        font-size: 15px;
        padding: 0 5px;
        text-transform: capitalize;
    }
    a.card-links {
        text-decoration: underline;
    }
    .pac-container {
        z-index: 10000 !important;
    }
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
                                <li class="breadcrumb-item"><a href="{{ route('getFmv') }}">Back to all FMV</a>
                                </li>
                                <li class="breadcrumb-item active">Edit Fmv
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body container">
            <!-- Add Clients Form -->
            <section id="floating-label-layouts">
                <div class="row match-height">
                    <div class="col-12 addContainer" id="fmv-update-container">
                        <div class="card">
                            <div class="card-header">
                                <h6>
                                    {{ $fmv->debtor_company }} ( Lease - {{ $fmv->lease_number }})
                                </h6>
                                <h6 class="pt-2 text-danger">
                                    @if(isset($fmv->sent_date) && !empty($fmv->sent_date))
                                        <span class="card-links"><i><b> Last Sent : </b></i>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fmv->sent_date)->format('j F, Y') }}</span> <br>
                                    @endif
                                    <a class="card-links" href="{{ route('generatePDF', $fmv->fmv_id) }}">View PDF</a> <br>
                                    <a class="card-links" id="sendFmv" href="javascript:void(0)" data-attr-link="{{ route('sendFmv', $fmv->fmv_id) }}">Send to Client</a>
                                </h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="fmvUpdateData" action="{{ route('updateFmvAjax') }}" method="post"  enctype="multipart/form-data">
                                        <input type="hidden" name="fmv_id" value="{{ $fmv->fmv_id }}">
                                        <div class="form-body">
                                            <!-- Status Details -->
                                            <div class="row mt-2">
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
                                                                <option value="3" @if($fmv->priority == 3) selected @endif>Low Priority</option>
                                                                <option value="2" @if($fmv->priority == 2) selected @endif>Standard Priority</option>
                                                                <option value="1" @if($fmv->priority == 1) selected @endif>High Priority < 1</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="request_date">Date Requested</label>
                                                        </div>
                                                        <div class="col-md-8 form-group required col-12">
                                                            <input type="text" id="request_date" class="form-control pickDate" value="@if(!empty($fmv->request_date)) {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fmv->request_date)->format('j F, Y') }} @endif" name="request_date" placeholder="Date Requested">
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
                                                                    <option value="{{ $user->id }}" @if($fmv->admin_id == $user->id) selected @endif>{{ $user->name }}</option>
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
                                                            <option value="New Deal"  @if($fmv->reason_for_fmv == 'New Deal') selected @endif>Front End Estimate (New Deal)</option>
                                                            <option value="LLRE" @if($fmv->reason_for_fmv == 'LLRE') selected @endif>Loan Loss Reserve Estimate</option>
                                                            <option value="Repo" @if($fmv->reason_for_fmv == 'Repo') selected @endif>Potential Repossession</option>
                                                            <option value="EOL" @if($fmv->reason_for_fmv == 'EOL') selected @endif>End of Lease Negotiation</option>
                                                            <option value="Internal" @if($fmv->reason_for_fmv == 'Internal') selected @endif>Internal</option>
                                                            <option value="Desktop" @if($fmv->reason_for_fmv == 'Desktop') selected @endif>Desktop Appraisal</option>
                                                            <option value="Collection" @if($fmv->reason_for_fmv == 'Collection') selected @endif>Collection Negotiation</option>
                                                            <option value="None" @if($fmv->reason_for_fmv == 'None') selected @endif>No Reason</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <label for="client_id">Client</label>
                                                    </div>
                                                    <div class="col-md-8 form-group required col-12">
                                                        <select class="custom-select form-control" name="client_id" id="client_id">
                                                            <!-- Dynamically we will load -->
                                                            <option value="">Client</option>
                                                            @if(isset($clients) && !empty($clients))
                                                                @foreach($clients as $client)
                                                                    <option value="{{ $client->CLIENT_ID }}" @if($fmv->client_id == $client->CLIENT_ID) selected @endif>{{ $client->FIRSTNAME }} {{ $client->LASTNAME }} {{ $client->COMPANY }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <label for="special_instructions">Special Instructions</label>
                                                    </div>
                                                    <div class="col-md-8 form-group col-12">
                                                        <input type="text" id="special_instructions" class="form-control" name="special_instructions" placeholder="Special Instructions" value="{{ $fmv->special_instructions }}">
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
                                                        <input type="text" id="first_name" class="form-control" name="first_name" placeholder="First Name" value="{{ $fmv->request_by_firstname }}">
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <label for="last-name">Last Name</label>
                                                    </div>
                                                    <div class="col-md-8 form-group col-12">
                                                        <input type="text" id="last-name" class="form-control" name="last_name" placeholder="Last Name" value="{{ $fmv->request_by_lastname }}">
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <label for="email">Email</label>
                                                    </div>
                                                    <div class="col-md-8 form-group col-12">
                                                        <input type="text" id="email" class="form-control" name="email" placeholder="Email" value="{{ $fmv->request_by_email }}">
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <label for="cc_email">CC Email</label>
                                                    </div>
                                                    <div class="col-md-8 form-group col-12">
                                                        <input type="text" id="cc_email" class="form-control" name="cc_email" placeholder="CC Email" value="{{ $fmv->request_by_cc }}">
                                                    </div>
                                                    <div class="col-md-4 col-12">
                                                        <label for="phone">Phone</label>
                                                    </div>
                                                    <div class="col-md-8 form-group col-12">
                                                        <input type="text" id="phone" class="form-control" name="phone" placeholder="Phone" value="{{ $fmv->request_by_phone }}">
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
                                                            <input type="text" id="lease_number" class="form-control" name="lease_number" placeholder="Lease Number" value="{{ $fmv->lease_number }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="company_name">Company Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="company_name" class="form-control" name="company_name" placeholder="Company Name" value="{{ $fmv->debtor_company }}">
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="lease_name">Name</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <input type="text" id="lease_name" class="form-control" name="lease_name" placeholder="Name" value="{{ $fmv->debtor_full_name }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Lease Details -->
                                            <!-- Additional Section Details -->
                                            <div class="row">
                                                <div class="col-md-8 col-12">
                                                    <div class="row">
                                                        <div class="col-md-4 col-12">
                                                            <label for="additional_comments">Additional Comment</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="additional_comments" id="additional_comments" rows="3" placeholder="Additional Comments">{{ $fmv->comments }}</textarea>
                                                        </div>
                                                        <div class="col-md-4 col-12">
                                                            <label for="private_comments">Private Comment</label>
                                                        </div>
                                                        <div class="col-md-8 form-group col-12">
                                                            <textarea class="form-control char-textarea" name="private_comments" id="private_comments" rows="3" placeholder="Private Comments">{{ $fmv->private_comments }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Additional Section Details -->
                                            <div class="row mt-2">
                                              <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1" id="updateFMV">Update FMV</button>
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
            <!-- Items List -->
            <section id="fmv-items-containers">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Items
                                <span style="float: right;">
                                     <button type="button" class="btn btn-primary mr-1 mb-1" id="addItems" data-toggle="modal" data-target="#itemAddFormModal">Add Items</button>
                                </span>
                                </h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table dataTable zero-configuration">
                                            <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Original Amount</th>
                                                <th>Equip Address</th>
                                                <th>FMV Estimate</th>
                                                <th>Recovery</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($fmv->items) > 0)
                                                @foreach( $fmv->items as $item )
                                                    <tr>
                                                        <td>{{ $item->make }}, {{ $item->model }} {{ $item->equip_year }} {{ $item->ser_number }}</td>
                                                        <td>${{ number_format($item->orig_amt,0,'.',',') }} </td>
                                                        <td>{{ $item->equip_address }} {{ $item->equip_city }} {{ $item->equip_state }}</td>
                                                        <td>${{ number_format($item->high_fmv_estimate,0,'.',',') }}/${{ number_format($item->mid_fmv_estimate,0,'.',',') }}/${{ number_format($item->low_fmv_estimate,0,'.',',') }}</td>
                                                        <td>${{ number_format($item->cost_of_recovery_high,0,'.',',') }} / ${{ number_format($item->cost_of_recovery_low,0,'.',',') }}</td>
                                                        <td>
                                                            <a href="javascript:void(0)" class="editItem" data-attr-link="{{ route('editItem', $item->fmv_item_id) }}">Edit</a><br>
                                                            <a href="javascript:void(0)" class="deleteItem" data-attr-link="{{ route('deleteItemFmv', $item->fmv_item_id) }}">Delete</a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="6" class="text-center">No Items</td></tr>
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
            <!-- End Items List -->

            <!-- Edit Item Form -->
            <div class="modal fade text-left" id="itemEditFormModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="itemEditFormModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel33">Edit Item</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <div id="itemModalOuterContainer">
                            <!-- Loads via jax -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Edit Item Form End -->

            <!-- Item Form -->
            <div class="modal fade text-left" id="itemAddFormModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="itemAddFormModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel33">Add Item</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <form action="{{ route('addItemAjax') }}" method="post" id="addItemFmv">
                            <input type="hidden" name="fmv_id" value="{{ $fmv->fmv_id }}">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-4 col-12">
                                        <label for="make">Make</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <input type="text" class="form-control" id="make" name="make">
                                        <p class="mb-0"><small class="text-muted text-info">Please type in slow for quick suggestions.</small></p>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="model">Modal</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <input type="text" class="form-control" id="model" name="model">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="equip_year">Equipment Year</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <input type="text" class="form-control" id="equip_year" name="equip_year">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="ser_nmbr">Serial</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <input type="text" class="form-control" id="ser_nmbr" name="ser_nmbr">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 col-12">
                                        <label for="orig_amt">Original cost</label>
                                    </div>
                                    <div class="col-md-8 form-group required col-12">
                                        <input type="text" class="form-control" id="orig_amt" name="orig_amt">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 col-12">
                                        <label for="low_fmv_estimate">FLV</label>
                                    </div>
                                    <div class="col-md-8 form-group required col-12">
                                        <input type="text" class="form-control" id="low_fmv_estimate" name="low_fmv_estimate">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="mid_fmv_estimate">OLV</label>
                                    </div>
                                    <div class="col-md-8 form-group required col-12">
                                        <input type="text" class="form-control" id="mid_fmv_estimate" name="mid_fmv_estimate">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="high_fmv_estimate">FMV</label>
                                    </div>
                                    <div class="col-md-8 form-group required col-12">
                                        <input type="text" class="form-control" id="high_fmv_estimate" name="high_fmv_estimate">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 col-12">
                                        <label for="cost_of_recovery_low">Recovery LOW</label>
                                    </div>
                                    <div class="col-md-8 form-group required col-12">
                                        <input type="text" class="form-control" id="cost_of_recovery_low" name="cost_of_recovery_low">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="cost_of_recovery_high">Recovery HIGH</label>
                                    </div>
                                    <div class="col-md-8 form-group required col-12">
                                        <input type="text" class="form-control" id="cost_of_recovery_high" name="cost_of_recovery_high">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 col-12">
                                        <label for="item_description">Equipment Description</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <textarea name="item_description" class="form-control"  id="item_description"></textarea>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 col-12">
                                        <label for="autocompleteAddress">Equip Address</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <input type="text" class="form-control" id="autocompleteAddress" name="equip_address">
                                        <p class="mb-0"><small class="text-muted text-info">Please type in slow for quick suggestions.</small></p>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 col-12">
                                        <label for="locality">City</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <input type="text" class="form-control" id="locality" name="equip_city">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="administrative_area_level_1">State</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <input type="text" class="form-control" id="administrative_area_level_1" name="equip_state">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="postal_code">Zip</label>
                                    </div>
                                    <div class="col-md-8 form-group col-12">
                                        <input type="text" class="form-control" id="postal_code" name="equip_zip">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="button" id="addItemFormModal" class="btn btn-primary ml-1">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Add</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Item Form End -->

            <!-- Item Form -->
            <div class="modal fade text-left" id="itemAddFilesModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="itemAddFilesModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel33">Add Files</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <form action="{{ route('addFmvFiles') }}"  method="post"  enctype="multipart/form-data" id="addFilesFmv">
                            <input type="hidden" name="fmv_id" id="fmv_id" value="{{ $fmv->fmv_id }}">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="dropzone" id="fmvFiles">
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

            <!-- Files List -->
            <section id="fmv-files-containers">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Files</h4>
                                <span style="float: right;">
                                     <button type="button" class="btn btn-primary mr-1 mb-1" id="addFiles" data-toggle="modal" data-target="#itemAddFilesModal">Add Files</button>
                                </span>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table dataTable zero-configuration">
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
                                            @if(count($fmv->files) > 0)
                                                @foreach( $fmv->files as $file )
                                                    <tr>
                                                        <td>{{ $file->logs }}</td>
                                                        <td>{{ $file->fileType }} </td>
                                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $file->updated_at)->format('j F, Y') }}</td>
                                                        <td><a href="{{ $file->fileSignedUrl }}" target="_blank">View</a></td>
                                                        <td><a href="javascript:void(0)" class="deleteFile" data-attr-link="{{ route('deleteFileFmv', $file->id) }}">Delete</a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="5" class="text-center">No Files</td></tr>
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
        </div>
    </div>
@push('page-vendor-js')
<script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDOSZ6FRxGMp9PN_6TDuiY7mfa0CQZlXJg&libraries=places"></script>
@endpush
@push('page-js')
<script>

    Dropzone.autoDiscover = false;
    var fmvForm = $('#fmvUpdateData'); // Form
    var fmvContainer = $('#fmv-update-container'); // Main Container
    var fmvFilesContainers = $('#fmv-files-containers');
    var deleteFile = $('.deleteFile');
    var deleteItem = $('.deleteItem');
    var addItemFormModal = $('#addItemFormModal');
    var itemAddFormModal = $('#itemAddFormModal');
    var addItemFmv = $('#addItemFmv');
    var itemEditFormModal = $('#itemEditFormModal');
    var itemEditModalBody = $('#itemEditModalBody');
    var itemModalOuterContainer = $('#itemModalOuterContainer');

    // Google Address Auto Complete
    let placeSearch;
    let autocomplete;
    const componentForm = {
        locality: "long_name",
        administrative_area_level_1: "short_name",
        postal_code: "short_name",
    };
    initAutocomplete( "autocompleteAddress" );

    function initAutocomplete( fieldName ) {
        // Create the autocomplete object, restricting the search predictions to
        // geographical location types.
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById(fieldName),
            //document.getElementsByName('equip_address'),
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



    // Block Container and Un block
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
        fmvFilesContainers.block({
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
        fmvFilesContainers.unblock();
    }


    function blockItemContainer(){

        itemAddFormModal.block({
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
        itemAddFormModal.unblock();
    }


    $(document).ready(function() { // Document Ready Function

        $('.pickDate').pickadate(); // Date Picker

        $('#client_id').select2({
            placeholder: "Client",
        });

        // Auto Complete Request
        $('#make').autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: "{{ route('itemSuggestion') }}",
                    dataType: "json",
                    type: "POST",
                    headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                    data: {
                        term: request.term
                    },
                    success: function( data ) {
                        //console.log(data);
                        response( data.data );
                    }
                } );
            },
            minLength: 2,
            select: function( event, ui ) {
                console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
                // Lets substitute the values.
                $('#model').val(ui.item.model);
                $('#low_fmv_estimate').val(ui.item.flv);
                $('#mid_fmv_estimate').val(ui.item.olv);
                $('#high_fmv_estimate').val(ui.item.fmv);
                $('#orig_amt').val(ui.item.orig);
                $('#cost_of_recovery_low').val(ui.item.recovery_low);
                $('#cost_of_recovery_high').val(ui.item.recovery_high);
            }
        });

        // Edit Item Click

        $('.editItem').click(function(){

            var itemLink = $(this).attr('data-attr-link');
            itemEditFormModal.modal('show');
            // Ready to send Ajax request to get data and fill.
            blockExt(itemEditFormModal, $('#waitingMessage'));
            $.ajax({
                url: itemLink,
                type: "GET",
                dataType: "json",
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        itemModalOuterContainer.html(response.html);
                        unBlockExt( itemEditFormModal );
                    } else {
                        $.each(response.errors, function (key, value) {
                            toastr.error(value);
                        });
                        unBlockExt( itemEditFormModal );
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt( itemEditFormModal );
                }
            });

        });
        // Add Item Modal
        addItemFormModal.click(function(){
            blockItemContainer();
            $.ajax({
                url: "{{route('addItemAjax')}}",
                type: "POST",
                dataType: "json",
                data: addItemFmv.serialize(),
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        unBlockItemContainer();
                        itemAddFormModal.modal('toggle');
                        addItemFmv[0].reset();
                        Swal.fire({
                            title: "Good job!",
                            text: "Item added",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                        });
                    } else {
                        unBlockItemContainer();
                        $.each(response.errors, function (key, value) {
                            toastr.error(value);
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    unBlockItemContainer();
                    console.log(xhr, resp, text);
                    toastr.error(text);
                }
            });

        });

        // From Submission without Files

        $('#updateFMV').click(function(e){ // Even for Form Submit.
              e.preventDefault();
                //if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) { // Check if Files are there.
                    blockFMVContainer();
                    $.ajax({
                        url: "{{route('updateFmvAjax')}}",
                        type: "POST",
                        dataType: "json",
                        data: fmvForm.serialize(),
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                unBlockFMVContainer();
                                Swal.fire({
                                    title: "Good job!",
                                    text: "FMV updated!",
                                    type: "success",
                                    confirmButtonClass: 'btn btn-primary',
                                    buttonsStyling: false,
                                }).then(function (result) {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                unBlockFMVContainer();
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value);
                                });
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            unBlockFMVContainer();
                        }
                    });
               // } else {
                    //myDropzone.processQueue(); // If files are there.
               // }
           });

          // Delete Files

          deleteFile.click(function(e){

              var deleteLink = $(this).attr('data-attr-link');

              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
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

                      blockFMVContainer();
                      $.ajax({
                          url: deleteLink,
                          type: "GET",
                          dataType: "json",
                          headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                          success: function (response) {
                              if (response.status) {
                                  unBlockFMVContainer();
                                  Swal.fire({
                                      title: "Deleted!",
                                      text: "FMV file was deleted!",
                                      type: "success",
                                      confirmButtonClass: 'btn btn-primary',
                                      buttonsStyling: false,
                                  }).then(function (result) {
                                      if (result.value) {
                                          window.location.reload();
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
                  }
              })

          });

          // Delete Item FMV

          deleteItem.click(function(e){ // delete File click

              var deleteLink = $(this).attr('data-attr-link');

              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
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

                      blockFMVContainer();
                      $.ajax({
                          url: deleteLink,
                          type: "GET",
                          dataType: "json",
                          headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                          success: function (response) {
                              if (response.status) {
                                  unBlockFMVContainer();
                                  Swal.fire({
                                      title: "Deleted!",
                                      text: "FMV Item was deleted!",
                                      type: "success",
                                      confirmButtonClass: 'btn btn-primary',
                                      buttonsStyling: false,
                                  }).then(function (result) {
                                      if (result.value) {
                                          window.location.reload();
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
                  }
              })

          });

          // Add Files

           $('#addFileFormModal').click(function(){
            myDropzone.processQueue();
           });

           $('#fmvFiles').dropzone({
            url: "{{route('addFmvFiles')}}",
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
                formData.append('fmv_id', $('#fmv_id').val());
            },
            success: function(file, response){
                if(response.status){
                   //console.log(response)
                    unBlockFMVContainer();
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
                    unBlockFMVContainer();
                }
            }
        });

    });

    // Send PDF

    $('#sendFmv').click(function(e){

        var sendFmvLink = $(this).attr('data-attr-link');

        Swal.fire({
            title: 'Are you sure?',
            text: "You want to send Evaluation to client",
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

                blockFMVContainer();
                $.ajax({
                    url: sendFmvLink,
                    type: "GET",
                    dataType: "json",
                    headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            unBlockFMVContainer();
                            Swal.fire({
                                title: "Sent!",
                                text: "FMV was sent!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                if (result.value) {
                                    window.location.reload();
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
            }
        })
    });
</script>
@endpush
@endsection
