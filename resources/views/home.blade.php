@extends('layouts.masterHorizontal')

@section('title','Home Dashboard')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
<style>
    #assignmentMarkerContent{
        width: 400px;
    }
    #assignmentMarkerContent p{
        color: #000 !important;
    }
</style>
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Starts -->
            <section id="dashboard-ecommerce">
                <div class="row">
                    <!-- Load Google Map with pending Authorization -->
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-2 col-12">
                                                <h4 class="card-title">Assignments</h4>
                                            </div>
                                            <div class="col-md-10 col-12 text-right">
                                                <div class="row">
                                                    <div class="col-md-2 col-12">
                                                        <span>Created</span>
                                                        <img src="http://maps.google.com/mapfiles/ms/icons/purple-dot.png" alt="new assignment">
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <span>Recovery</span>
                                                        <img src="http://maps.google.com/mapfiles/ms/icons/red-dot.png" alt="new assignment">
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <span>Sold</span>
                                                        <img src="http://maps.google.com/mapfiles/ms/icons/yellow-dot.png" alt="new assignment">
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <span>Paid</span>
                                                        <img src="http://maps.google.com/mapfiles/ms/icons/blue-dot.png" alt="new assignment">
                                                    </div>
                                                    <div class="col-md-2 col-12">
                                                        <span>Remitted</span>
                                                        <img src="http://maps.google.com/mapfiles/ms/icons/green-dot.png" alt="new assignment">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-content" id="itemsPendingAuthorization" style="height: 700px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ends Map Area -->
                </div>
                <!-- Over All Account Summary -->
                <div class="row justify-content-center">
                    <div class="col-6" id="overallAccountSummary">
                        <div class="card">
                            <div class="container">
                                <div class="card-content">
                                    <div class="row my-1">
                                        <div class="col-12 text-center">
                                            <h4 class="card-title">Account Summary {{ date('Y') }}</h4>
                                        </div>
                                        <div class="col-12">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-user text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Total Accounts</span>
                                                        </div>
                                                    </div>
                                                    <span>{{ $totalAssignments }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-user text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Appraisal Accounts</span>
                                                        </div>
                                                    </div>
                                                    <span>{{  $appraisalAccount }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-user text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Approved Accounts</span>
                                                        </div>
                                                    </div>
                                                    <span>{{  $approvedAssignments }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-user text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Active Accounts</span>
                                                        </div>
                                                    </div>
                                                    <span>{{ $activeAssignments }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-user text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Open Accounts</span>
                                                        </div>
                                                    </div>
                                                    <span>{{  $openAssignments }}</span>
                                                </li>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-user text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Closed Accounts</span>
                                                        </div>
                                                    </div>
                                                    <span>{{ $closedAssignments }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bxs-zap text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Total Assets</span>
                                                        </div>
                                                    </div>
                                                    <span>{{  $totalAssets }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bxs-zap text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Assets Sold</span>
                                                        </div>
                                                    </div>
                                                    <span>{{ $assetsSold }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">YTD Inventory Value</span>
                                                        </div>
                                                    </div>
                                                    <span>${{ number_format($expectedOlvValue, 2) }}</span>
                                                </li>

                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <!-- Empty Row -->
                                                </li>
                                                <!-- Invoice Data -->
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-stats text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Fee Invoice Sent ( {{ $totalFeeInvoice }} )</span>
                                                        </div>
                                                    </div>
                                                    <span>${{ number_format($feeInvoiceSent, 2) }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Fee Invoice Paid</span>
                                                        </div>
                                                    </div>
                                                    <span><span class="text-success">+</span>${{ number_format($feeInvoicePaid, 2) }}</span>
                                                </li>

                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <!-- Empty Row -->
                                                </li>


                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <!-- Empty -->
                                                </li>
                                                <!-- Commission and Remittance -->
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-stats text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">EQ Invoice Sent ( {{ $totalCustomerInvoice }} )</span>
                                                        </div>
                                                    </div>
                                                    <span>${{ number_format($customerInvoiceSent, 2) }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">EQ Invoice Paid</span>
                                                        </div>
                                                    </div>
                                                    <span><span class="text-success">+</span>${{ number_format($customerInvoicePaid, 2) }}</span>
                                                </li>

                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <!-- Empty -->
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-credit-card text-danger text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <span class="list-title">Total Remittance Amount</span>
                                                        </div>
                                                    </div>
                                                    <span><span class="text-danger">-</span>${{ number_format($clientRemittanceAmount, 2) }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <h5> EQ Gross Commission</h5>
                                                        </div>
                                                    </div>
                                                    <span class="text-success">${{ number_format($commissionEarned, 2) }}</span>
                                                </li>
                                                <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                    <div class="list-left d-flex">
                                                        <div class="list-icon mr-1">
                                                            <div class="avatar bg-rgba-primary m-0">
                                                                <div class="avatar-content">
                                                                    <i class="bx bx-dollar text-success text-primary font-size-base"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="list-content">
                                                            <h5>Gross Profit</h5>
                                                        </div>
                                                    </div>
                                                    <span class="text-success">${{ number_format($profit, 2) }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Overall Account Summary -->
                <!-- Filters Section -->
                <div class="row">
                    <div class="col-12" id="filterSection">
                        <div class="card">
                            <div class="container">
                                <div class="card-content">
                                    <div class="row my-1">
                                        <div class="col-12 text-center">
                                            <h4 class="card-title">Filters</h4>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <fieldset class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text"
                                                               for="clientReceivableYear">Year</label>
                                                    </div>
                                                    <select class="form-control" id="clientReceivableYear">
                                                        @foreach($year as $y)
                                                            <option value="{{ $y }}"
                                                                    @if( \Carbon\Carbon::now()->year == $y) selected @endif>{{ $y }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <fieldset class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="clientReceivableMonth">Month</label>
                                                    </div>
                                                    <select class="form-control" id="clientReceivableMonth" name="clientReceivableMonth" multiple="multiple">
                                                        @foreach($months as $key => $val)
                                                            <option value="{{ $val }}" @if( \Carbon\Carbon::now()->format('m') == $val) selected="selected" @endif>{{ $key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Filter Section -->

                <div class="row">
                    <!-- Home Analytics Area -->
                    <div class="col-md-6 col-12">
                        <!--- Give Summary of Account -->
                        <div class="col-12 dashboard-latest-update p-0">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-6 col-12 p-0">
                                                <h4 class="card-title">Summary</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-content mt-1" id="homeAnalytics" style="height: 560px;">
                                    <!-- Loads Via Ajax -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices Out -->
                    <div class="col-xl-6 col-12 dashboard-marketing-campaign">
                        <div class="card marketing-campaigns">
                            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                                <div class="container p-0">
                                    <div class="row">
                                        <div class="col-12 p-0">
                                            <h4 class="card-title">Client Remittance</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content" id="clientRemittance">
                                <!-- Loads By Ajax -->
                            </div>
                        </div>
                    </div>
                    <!-- End -->

                    <!-- Invoices Out -->
                    <div class="col-xl-6 col-12 dashboard-marketing-campaign">
                        <div class="card marketing-campaigns">
                            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                                <div class="container p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <h4 class="card-title">Client Receivables</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content" id="clientReceivables">
                                <!-- Loads By Ajax -->
                            </div>
                        </div>
                    </div>
                    <!-- End -->


                    <!-- Invoices Out -->
                    <div class="col-xl-6 col-12 dashboard-marketing-campaign">
                        <div class="card marketing-campaigns">
                            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                                <div class="container p-0">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <h4 class="card-title">Customer Receivables</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-content" id="customerInvoices">
                                <!-- Loads Via Ajax -->
                            </div>
                        </div>
                    </div>
                    <!-- End -->
                    <div class="col-12" id="container-column-chart-fmv">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">FMV Financial Data <span class="yearHeading"></span></h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div id="column-chart-fmv"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" id="container-mixed-chart-fmv-to-assignment">
                        <div class="card mb-2">
                            <div class="card-header">
                                <h4 class="card-title">FMV to Assignment Data Type Analysis <span class="yearHeading"></span></h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div id="bar-chart-fmv-assignment"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <!----------------------------------- Modal Boxes ------------------------------------>

    <!-- Client Remittance Modal Box -->
    <div class="modal fade text-left" id="clientRemittanceModal" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="clientRemittanceModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Client Remittance <span
                                id="originalCustomerPayment"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <label for="remittance_date_paid">Date paid</label>
                                    <input type="hidden" name="remittance_id" id="remittance_id"
                                           value="">
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="remittance_date_paid"
                                           id="remittance_date_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="remittance_amount_paid">Amount</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="number" class="form-control" name="remittance_amount_paid"
                                           id="remittance_amount_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="remittance_type_paid">Check/Wire</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="remittance_type_paid"
                                           id="remittance_type_paid" placeholder="" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-light-secondary"
                            data-action="{{ route('customerAmountRemitted') }}" id="remitPaidBtn">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Remit Amount</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Customer Paid Invoice Modal -->


    <!-- Customer Paid Invoice Modal -->
    <div class="modal fade text-left" id="customerInvoicePaidModal" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="customerInvoicePaidModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Customer Invoice <span
                                id="originalCustomerInvoice"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <label for="customer_date_paid">Date paid</label>
                                    <input type="hidden" name="customer_invoice_id" id="customer_invoice_id"
                                           value="">
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="customer_date_paid"
                                           id="customer_date_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="customer_amount_paid">Amount</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="number" class="form-control" name="customer_amount_paid"
                                           id="customer_amount_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="customer_type_paid">Check/Wire</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="customer_type_paid"
                                           id="customer_type_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3  col-12">
                                    <label for="customer_memo_paid">Memo</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="customer_memo_paid"
                                           id="customer_memo_paid" placeholder="" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-light-secondary"
                            data-action="{{ route('customerInvoicePaid') }}" id="customerInvoicePaidBtn">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Mark as Paid</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Customer Paid Invoice Modal -->


    <!-- Client Paid Invoice Modal -->
    <div class="modal fade text-left" id="clientInvoicePaidModal" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="clientInvoicePaidModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Client Invoice <span
                                id="originalClientInvoice"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <label for="client_date_paid">Date paid</label>
                                    <input type="hidden" name="client_invoice_id" id="client_invoice_id"
                                           value="">
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="client_date_paid"
                                           id="client_date_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="client_amount_paid">Amount</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="number" class="form-control" name="client_amount_paid"
                                           id="client_amount_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="client_type_paid">Check/Wire</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="client_type_paid"
                                           id="client_type_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3  col-12">
                                    <label for="client_memo_paid">Memo</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="client_memo_paid"
                                           id="client_memo_paid" placeholder="" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" class="btn btn-light-secondary"
                            data-action="{{ route('clientInvoicePaid') }}" id="clientInvoicePaidBtn">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Mark as Paid</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Client Paid Invoice Modal -->


@push('page-js')
<!--<script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>-->
<script src="{{ asset('app-assets/js/scripts/moment/moment.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDOSZ6FRxGMp9PN_6TDuiY7mfa0CQZlXJg"></script>
<script src="{{ asset('assets/js/home/chart.js') }}"></script>
@endpush
@endsection
