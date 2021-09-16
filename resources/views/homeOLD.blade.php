@extends('layouts.masterHorizontal')

@section('title','Home Dashboard')

@push('page-style')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/dashboard-ecommerce.css') }}">
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- Dashboard Starts -->
            <section id="dashboard-ecommerce">
                <div class="row">
                    <div class="col-12">
                        <div class="row">

                            <!-- Section Goes Old  -->
                            <div class="col-md-6 col-12">

                                <div class="row">
                             <!-- Data Section -->
                                <div class="col-md-4 col-12 dashboard-users-success">
                                    <div class="card text-center">
                                        <div class="card-content">
                                            <div class="card-body py-1">
                                                <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                    <i class="bx bx-user-check"></i>
                                                </div>
                                                <div class="text-muted line-ellipsis">Active Clients.</div>
                                                <h3 class="mb-0">{{ $totalActiveClients }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 dashboard-users-danger">
                                    <div class="card text-center">
                                        <div class="card-content">
                                            <div class="card-body py-1">
                                                <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                                    <i class="bx bx-note font-medium-5"></i>
                                                </div>
                                                <div class="text-muted line-ellipsis">Total FMV</div>
                                                <h3 class="mb-0">{{ $totalFMV }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 dashboard-users-danger">
                                    <div class="card text-center">
                                        <div class="card-content">
                                            <div class="card-body py-1">
                                                <div class="badge-circle badge-circle-lg badge-circle-light-secondary mx-auto mb-50">
                                                    <i class="bx bx-note font-medium-5"></i>
                                                </div>
                                                <div class="text-muted line-ellipsis">Total FMV To Assignments</div>
                                                <h3 class="mb-0">{{ $totalFmvToAssignment }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <!-- End Data Section -->

                            <!--- Amount Section -->
                            <div class="col-md-3 col-12">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bxs-dollar-circle"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Invoice Sent Amount.</div>
                                            <h3 class="mb-0 text-success">${{ number_format($sentInvoiceAmount, 2, '.', ',') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bxs-dollar-circle"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Invoice Amount Paid.</div>
                                            <h3 class="mb-0 text-success">${{ number_format($paidInvoiceAmount, 2, '.', ',') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bxs-dollar-circle"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Customer Invoice Sent.</div>
                                            <h3 class="mb-0 text-success">${{ number_format($customerSentInvoiceAmount, 2, '.', ',') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <div class="card text-center">
                                    <div class="card-content">
                                        <div class="card-body py-1">
                                            <div class="badge-circle badge-circle-lg badge-circle-light-success mx-auto mb-50">
                                                <i class="bx bxs-dollar-circle"></i>
                                            </div>
                                            <div class="text-muted line-ellipsis">Customer Invoice Paid.</div>
                                            <h3 class="mb-0 text-success">${{ number_format($customerPaidInvoiceAmount, 2, '.', ',') }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Invoice Section -->

                                </div>

                            </div>

                            <div class="col-md-6 col-12">

                                <!--- Give Summary of Account -->

                                <div class="col-12 dashboard-latest-update">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center pb-50">
                                            <h4 class="card-title">Latest Update</h4>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButtonSec" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    2019
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButtonSec">
                                                    <a class="dropdown-item" href="#">2019</a>
                                                    <a class="dropdown-item" href="#">2018</a>
                                                    <a class="dropdown-item" href="#">2017</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body p-0 pb-1">
                                                <ul class="list-group list-group-flush">
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
                                                                <span class="list-title">Total Products</span>
                                                                <small class="text-muted d-block">1.2k New Products</small>
                                                            </div>
                                                        </div>
                                                        <span>10.6k</span>
                                                    </li>
                                                    <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-info m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-stats text-info font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <span class="list-title">Total Sales</span>
                                                                <small class="text-muted d-block">39.4k New Sales</small>
                                                            </div>
                                                        </div>
                                                        <span>26M</span>
                                                    </li>
                                                    <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-danger m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-credit-card text-danger font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <span class="list-title">Total Revenue</span>
                                                                <small class="text-muted d-block">43.5k New Revenue</small>
                                                            </div>
                                                        </div>
                                                        <span>15.89M</span>
                                                    </li>
                                                    <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-success m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-dollar text-success font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <span class="list-title">Total Cost</span>
                                                                <small class="text-muted d-block">Total Expenses</small>
                                                            </div>
                                                        </div>
                                                        <span>1.25B</span>
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
                                                                <span class="list-title">Total Users</span>
                                                                <small class="text-muted d-block">New Users</small>
                                                            </div>
                                                        </div>
                                                        <span>1.2k</span>
                                                    </li>
                                                    <li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between">
                                                        <div class="list-left d-flex">
                                                            <div class="list-icon mr-1">
                                                                <div class="avatar bg-rgba-danger m-0">
                                                                    <div class="avatar-content">
                                                                        <i class="bx bx-edit-alt text-danger font-size-base"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="list-content">
                                                                <span class="list-title">Total Visits</span>
                                                                <small class="text-muted d-block">New Visits</small>
                                                            </div>
                                                        </div>
                                                        <span>4.6k</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>

                    <!-- Invoices Out -->
                    <div class="col-xl-6 col-12 dashboard-marketing-campaign">
                        <div class="card marketing-campaigns">
                            <div class="card-header d-flex justify-content-between align-items-center pb-0">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <h4 class="card-title">Client Receivables</h4>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <fieldset class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="clientReceivableYear">Year</label>
                                                    </div>
                                                    <select class="form-control" id="clientReceivableYear">
                                                        @foreach($year as $y)
                                                            <option value="{{ $y }}" @if( \Carbon\Carbon::now()->year == $y) selected @endif>{{ $y }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <fieldset class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="clientReceivableMonth">Month</label>
                                                    </div>
                                                    <select class="form-control" id="clientReceivableMonth">
                                                        @foreach($months as $key => $val)
                                                            <option value="{{ $val }}" @if( \Carbon\Carbon::now()->month == $y) selected @endif>{{ $key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </fieldset>
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
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <h4 class="card-title">Customer Receivables</h4>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <fieldset class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="customerReceivableYear">Year</label>
                                                    </div>
                                                    <select class="form-control" id="customerReceivableYear">
                                                        @foreach($year as $y)
                                                            <option value="{{ $y }}" @if( \Carbon\Carbon::now()->year == $y) selected @endif>{{ $y }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3 col-12">
                                            <fieldset class="form-group">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="customerReceivableMonth">Month</label>
                                                    </div>
                                                    <select class="form-control" id="customerReceivableMonth">
                                                        @foreach($months as $key => $val)
                                                            <option value="{{ $val }}" @if( \Carbon\Carbon::now()->month == $y) selected @endif>{{ $key }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </fieldset>
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
                    <div class="col-md-6 col-12" id="container-column-chart-fmv">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">FMV Financial Data 2020</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div id="column-chart-fmv"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12" id="container-mixed-chart-fmv-to-assignment">
                        <div class="card mb-2">
                            <div class="card-header">
                                <h4 class="card-title">FMV to Assignment Data Type Analysis 2020</h4>
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

@push('page-js')
<!--<script src="{{ asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>-->
<script src="{{ asset('app-assets/js/scripts/moment/moment.js') }}"></script>
<script src="{{ asset('assets/js/home/chart.js') }}"></script>
@endpush
@endsection
