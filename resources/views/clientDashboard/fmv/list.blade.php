@extends('clientDashboard.layouts.masterHorizontal')

@section('title','List FMV - InPlace Auction')

@push('page-style')
<style>
    /*.table-responsive{
        overflow-x: hidden;
    }*/
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">FMV</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Fmv
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Zero configuration table -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <!--<div class="card-header">
                                <h4 class="card-title">FMV</h4>
                            </div>-->
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table" id="getFmvDataTable">
                                            <thead>
                                            <tr>
                                                <th>FMV#</th>
                                                <th>Lease#</th>
                                                <th>Items</th>
                                                <th>
                                                    Sum Value Estimate<br>
                                                    (FLV,OLV,FMV)
                                                </th>
                                                <th>
                                                    Sum Recovery Cost<br>
                                                    (Low/High)
                                                </th>
                                                <th>Company</th>
                                                <th>Reason</th>
                                                <th>Request</th>
                                                <th>View</th>
                                                <th>Assignment</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                            <tr>
                                                <th>FMV#</th>
                                                <th>Lease#</th>
                                                <th>Items</th>
                                                <th>
                                                    Sum Value Estimate<br>
                                                    (FLV,OLV,FMV)
                                                </th>
                                                <th>
                                                    Sum Recovery Cost<br>
                                                    (Low/High)
                                                </th>
                                                <th>Company</th>
                                                <th>Reason</th>
                                                <th>Request</th>
                                                <th>View</th>
                                                <th>Assignment</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Zero configuration table -->
        </div>
    </div>
@push('page-vendor-js')
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/moment/moment.js') }}"></script>
@endpush
@push('page-js')
<script>

    $(document).ready(function() {

        var body = $('body'); // define body

        /********************** generate listing for FMV ****************************/

        $('#getFmvDataTable').DataTable( {
            dom: 'Blfrtip',
            processing: true,
            serverSide: true,
            pageLength : 100,
            initComplete: function() {
            },
            ajax: {
                url: "{{route('getFmvClientDatatable')}}",
            },
            order : [[ 7, "desc" ]],
            columns: [
                {  data: "fmv_id"},
                { data: "lease_number"},
                {
                    data: "items",
                    render : function( data, type, row, meta) {

                        if(data) {
                            if(data.length > 0){
                                let itemData = '';
                                for (var i = 0; i < data.length; i++) {
                                    itemData += data[i].make +' '+ data[i].model +' ' + data[i].ser_nmbr + ' <br> <br>';
                                }
                                return itemData;
                            } else {
                                return '-';
                            }
                        } else {
                            return '-';
                        }

                    }
                },
                {
                    data: "items",
                    render : function( data, type, row, meta) {

                        if(data) {
                            if(data.length > 0){
                                let itemCost = '<span class=text-danger>';
                                for (var i = 0; i < data.length; i++) {
                                    itemCost += '( $'+Math.round(data[i].low_fmv_estimate) +' / '+ '$'+Math.round(data[i].mid_fmv_estimate) +'/ '+ '$' + Math.round(data[i].high_fmv_estimate) + ') <br> <br>';
                                }
                                itemCost +='</span>'
                                return itemCost;
                            } else {
                                return '-';
                            }
                        } else {
                            return '-';
                        }

                    }
                },
                {
                    data: "items",
                    render : function( data, type, row, meta) {

                        if(data) {
                            if(data.length > 0){
                                let itemData = '<span class=text-danger>';
                                for (var i = 0; i < data.length; i++) {
                                    itemData += '( $'+Math.round(data[i].cost_of_recovery_low) +' / '+'$'+ Math.round(data[i].cost_of_recovery_high) +') <br> <br>';
                                }
                                itemData +='</span>'
                                return itemData;
                            } else {
                                return '-';
                            }
                        } else {
                            return '-';
                        }

                    }
                },
                { data: "debtor_company" },
                { data: "reason_for_fmv" },
                {
                    data: "request_date",
                    render : function(data, type, row, meta) {
                        return (data) ? moment(data).format('DD-MMM-YYYY') : '-';
                    }
                },
                {  data: "fmv_id",
                    render : function(data, type, row, meta) {
                        return (data) ? '<a href="/generateFmvClientPDF/'+row.fmv_id+'">PDF</a>' : '-';
                    }
                },
                {
                    data: "assignment_id",
                    render : function(data, type, row, meta) {
                        return (data) ? '<a href="/assignmentClient/'+data+'">View</a>' : '<a data-link="#" href="javascript:void(0)">Conv 2 Assign</a>'
                    }
                }
            ],
            buttons: [
                /*{
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [1,2,3,4,5,6]
                    }
                },*/
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1,2,3,4,5]
                    }
                }
            ]
        });

    });

</script>
@endpush
@endsection
