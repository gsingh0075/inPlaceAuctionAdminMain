@extends('layouts.masterHorizontal')

@section('title','List Assignment - InPlace Auction')

@push('page-style')
<style>
    /*.table-responsive{
        overflow-x: hidden;
    }*/
    #getAssignmentDataTable a{
        text-decoration: underline;
    }
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Assignments</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Assignments
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
                                <h4 class="card-title">Assignments</h4>
                            </div>-->
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table" id="getAssignmentDataTable">
                                            <thead>
                                            <tr>
                                                <th></th>
                                                <th>#ID</th>
                                                <th>Lease</th>
                                                <th>State</th>
                                                <th>Items</th>
                                                <th>Client Name</th>
                                                <th>Company</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Approved</th>
                                                <th>Is Appraisal</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>#ID</th>
                                                <th>Lease</th>
                                                <th>State</th>
                                                <th>Items</th>
                                                <th>Client Name</th>
                                                <th>Company</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Approved</th>
                                                <th>Is Appraisal</th>
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

    var assignmentTable = $('#getAssignmentDataTable');

    $(document).ready(function() {


        var buttonCommon = {
            exportOptions: {
                format: {
                    header: function ( text, index, node ) {
                        // Change column header if it includes something
                        console.log(index);
                        if(index === 1){
                            return '#';
                        }
                        if(index === 2){
                            return 'Lease Info';
                        }
                        if(index === 3){
                            return 'Items';
                        }
                        if(index === 4){
                            return 'Client';
                        }
                        if(index === 5){
                            return 'Company';
                        }
                        if(index === 6){
                            return 'Date';
                        }
                        if(index === 10){
                            return 'Is Appraisal';
                        }
                        //return text.includes("Company") ?
                           // text.replace( 'Company' ) :
                            //text;
                    }
                }
            }
        };


        var table = assignmentTable.DataTable( {
            dom: 'Blfrtip',
            processing: true,
            serverSide: true,
            pageLength : 100,
            ajax: {
                url: "{{route('getAssignmentDatatable')}}",
            },
            order : [[ 7, "desc" ]],
            initComplete: function() {
                this.api().columns([6]).every(function() {
                    let column = this;
                    let select = $('<select><option value="">Company</option></select>')
                        .appendTo( $(column.header()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search( val ? val : '', true, false )
                                .draw();
                        } );
                    let distinctClient = [];
                    column.data().unique().sort().each( function ( d, j ) {
                        //console.log(d);
                        if( !distinctClient.includes(d.client_id) ){
                            select.append( '<option value="'+d.client_id+'">'+d.client_info.COMPANY+'</option>' );
                            distinctClient.push(d.client_id);
                        }
                        //console.log(distinctClient);

                    } );
                });
                this.api().columns([8]).every(function() {
                    let column = this;
                    let select = $('<select><option value="">Status</option></select>')
                        .appendTo( $(column.header()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search( val ? val : '', true, false )
                                .draw();
                        } );
                    select.append( '<option value="1" selected>Open</option>' );
                    select.append( '<option value="0">Close</option>' );
                });
                this.api().columns([9]).every(function() {
                    let column = this;
                    let select = $('<select><option value="">Assignment Status</option></select>')
                        .appendTo( $(column.header()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search( val ? val : '', true, false )
                                .draw();
                        } );
                    select.append( '<option value="1">Approved</option>' );
                    select.append( '<option value="0">Not Approved</option>' );
                });
                this.api().columns([10]).every(function() {
                    let column = this;
                    let select = $('<select><option value="">Is Appraisal</option></select>')
                        .appendTo( $(column.header()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            column
                                .search( val ? val : '', true, false )
                                .draw();
                        } );
                    select.append( '<option value="1">Yes</option>' );
                    select.append( '<option value="0">No</option>' );
                });
            },
            rowCallback : function (row, data, index) {
                if(data["assignment_color_status"] < 24) {
                    $('td', row).addClass('twoWeekOld');
                }
                if(data["assignment_color_status"] >= 24 && data["assignment_color_status"] < 42 ) {
                    $('td', row).addClass('sixWeekOld');
                }
                if(data["assignment_color_status"] >= 42 && data["assignment_color_status"] < 94 ) {
                    $('td', row).addClass('twelveWeekOld');
                }
                if(data["assignment_color_status"] >= 94 ) {
                    $('td', row).addClass('inWeekOld');
                }
            },
            columns: [
                {
                    "className": 'details-control',
                    "orderable": false,
                    "data"     : null,
                    "defaultContent": ''
                },
                { data: "assignment_id" ,
                    render : function ( data, type, row, meta ) {
                        if(data) {
                            if(row.assignment_color_status < 24) {
                                return '<span class="twoWeekOld">'+data+'</span>';
                            }
                            if(row.assignment_color_status >= 24 && row.assignment_color_status < 42 ) {
                                return '<span class="sixWeekOld">'+data+'</span>';
                            }
                            if(row.assignment_color_status >= 42 && row.assignment_color_status < 94 ) {
                                return '<span class="twelveWeekOld">'+data+'</span>';
                            }
                            if(row.assignment_color_status >= 94 ) {
                                return '<span class="inWeekOld">'+data+'</span>';
                            }
                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: "lease_nmbr",
                    render : function( data, type, row, meta) {

                        if(data) {
                            let leaseInfo = '';
                            leaseInfo +=  '<a href="/assignment/'+row.assignment_id+'">'+data+'</a>';
                            if(row.ls_full_name) {
                                if(row.ls_company){
                                    leaseInfo += '<br><br>'+row.ls_company;
                                }
                                leaseInfo += '<br>'+ row.ls_full_name;
                                if(row.ls_address1){
                                    leaseInfo += ' '+row.ls_address1;
                                }
                            }
                           return leaseInfo;

                        } else {
                            return '-';
                        }
                    }
                },
                {
                    data: "ls_state",
                },
                {
                    data: "items",
                    render : function( data, type, row, meta) {

                        if(data) {
                            if(data.length > 0){
                               let itemData = '';
                                for (var i = 0; i < data.length; i++) {
                                    if(data[i].SOLD_FLAG === 1){
                                        itemData += ' <span class=text-info> !!SOLD!!</span> ';
                                    }
                                    itemData += data[i].ITEM_MAKE +' '+ data[i].ITEM_MODEL +' ' + data[i].ITEM_SERIAL + ' <br> <br>';
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
                    data: "client",
                    render : function ( data, type, row, meta ) {
                        //return (data) ? data.client_id : '-';
                        if(data) {
                             return (data.client_info) ? '<a href="/client/'+data.client_info.CLIENT_ID+'">'+data.client_info.FIRSTNAME +' '+ data.client_info.LASTNAME+'</a>' : '-';
                        } else {
                            return '-';
                        }
                    },
                    orderable : false
                },
                { data: "client",
                    render : function ( data, type, row, meta ) {
                        //return (data) ? data.client_id : '-';
                        if(data) {
                            return (data.client_info) ? data.client_info.COMPANY : '-';
                        } else {
                            return '-';
                        }
                    },
                    orderable : false
                },
                {
                    data: "dt_stmp",
                    render: function ( data, type, row, meta ) {
                        return (data) ?  moment(data).format('DD-MMM-YYYY') : '-';
                        //return (data) ?  data.dt_stmp : '-';
                    },
                },
                {  data: "isopen",
                    render : function(data, type, row, meta) {
                        return (data === 1) ?  'Open' : 'Close' ;
                    },
                    orderable: false
                },
                {  data: "approved",
                    render : function(data, type, row, meta) {
                        return (data === 1) ?  'Approved' : 'Not Approved' ;
                    },
                    orderable: false
                },
                {  data: "is_appraisal",
                    render : function(data, type, row, meta) {
                        return (data === 1) ?  'Yes' : 'No' ;
                    },
                    orderable: false
                }
            ],
            buttons: [
                $.extend( true, {}, buttonCommon, {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                } ),
                /*{
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                }*/
                $.extend( true, {}, buttonCommon, {
                    extend: 'print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6]
                    }
                } ),
            ]
        });

        // click on Child Row
        $('#getAssignmentDataTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
            }
        });

        // Return Extra information
        function format ( d ) {
            // `d` is the original data object for the row

            var div = $('<div/>').addClass('loading').text('Loading.....');
            $.ajax({
                url: "{{ route('getChildInfoAssignment') }}",
                type: "POST",
                dataType: "json",
                data: {
                    assignment_id:d.assignment_id,
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (result) {
                    if (result.success) {
                        div.html(result.html);
                    } else {
                        $.each(result.errors, function (key, value) {
                            toastr.error('Marker Loading Failed '+value);
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                }
            });

            return div;

            //return 'All the extra information goes here.'+d.assignment_id;
        }


    });
</script>
@endpush
@endsection
