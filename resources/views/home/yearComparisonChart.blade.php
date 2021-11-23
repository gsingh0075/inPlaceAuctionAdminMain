@extends('layouts.masterHorizontal')
@section('title','Year Comparison Chart')
@push('page-style')
<style>
</style>
@endpush

@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section id="yearsComparisonChart">
                <div class="row">
                    <!-- Years Comparison Chart -->
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
                    <!-- Ends Here -->
                </div>
            </section>

        </div>
    </div>




@push('page-js')
<script src="{{ asset('app-assets/js/scripts/moment/moment.js') }}"></script>
<script src="{{ asset('assets/js/home/yearComparisonChart.js') }}"></script>
@endpush
@endsection
