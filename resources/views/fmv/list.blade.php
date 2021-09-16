@extends('layouts.masterHorizontal')

@section('title','List FMV - InPlace Auction')

@push('page-style')
<style>
   #getFmvDataTable a{
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
                        <h5 class="content-header-title float-left pr-1 mb-0">FMV</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active"> <b>Fmv {{ $operator }} {{ $archiveYear }}</b>
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
                                        <table class="table dataTable" id="getFmvDataTable">
                                            <thead>
                                            <tr>
                                                <th>Request</th>
                                                <th>Company</th>
                                                <th>Lease#</th>
                                                <th>
                                                    Sum <br>
                                                    Orig Amt
                                                </th>
                                                <th>
                                                    Sum Value Est.<br>
                                                    FLV/OLV/FMV
                                                </th>
                                                <th>
                                                    Sum Rcvry Est.
                                                    Low/High
                                                </th>
                                                <th>Evaluator</th>
                                                <th>Reason</th>
                                                <th>Client</th>
                                                <th>Sent</th>
                                                <th>Action</th>
                                                <th>Assignment</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($fmv) && !empty($fmv))
                                              @foreach($fmv as $f)
                                                  <tr>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          @if(!empty( $f->request_date))
                                                              {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $f->request_date)->format('j F, Y')}}
                                                          @endif
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          {{ $f->debtor_full_name }} <br>
                                                          {{ $f->debtor_company }}
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          <a href="{{ route('showFmv', $f->fmv_id) }}">{{ $f->lease_number }}</a>
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          @php
                                                            $sumOriginal = 0;
                                                            $low = 0;
                                                            $med = 0;
                                                            $high = 0;
                                                            $costRecoveryLow = 0;
                                                            $costRecoveryHigh = 0;
                                                          @endphp
                                                          @if(isset($f->items) && !empty($f->items))
                                                             @foreach( $f->items as $item )
                                                                  @php
                                                                      $sumOriginal += $item->orig_amt;
                                                                      $low += $item->low_fmv_estimate;
                                                                      $med += $item->mid_fmv_estimate;
                                                                      $high += $item->high_fmv_estimate;
                                                                      $costRecoveryLow += $item->cost_of_recovery_low;
                                                                      $costRecoveryHigh += $item->cost_of_recovery_high;
                                                                  @endphp
                                                             @endforeach
                                                          @endif
                                                          ${{ number_format($sumOriginal,2) }}
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          ${{ number_format($low,2) }} / ${{ number_format($med,2) }} / ${{ number_format($high,2) }}
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          ${{ number_format($costRecoveryLow,2) }} / ${{ number_format($costRecoveryHigh,2) }}
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          @if(isset($f->user) && !empty($f->user))
                                                              {{ $f->user->name }}
                                                          @endif
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          {{ $f->reason_for_fmv }}
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          @if(isset($f->client) && !empty($f->client))
                                                              <a href="{{ route('showClient', $f->client->CLIENT_ID) }}">
                                                               {{ $f->client->COMPANY }}
                                                              </a>
                                                          @endif
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                      @if(!empty( $f->sent_date))
                                                          {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $f->sent_date)->format('j F, Y')}}
                                                      @else
                                                          <span class="text-info">!!! NOT SENT</span>
                                                      @endif
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          <a href="{{ route('generatePDF',$f->fmv_id) }}">PDF</a><br>
                                                          <a href="{{ route('showFmv', $f->fmv_id) }}">View</a> <br>
                                                          <a class="sendFmvList" data-attr-link="{{ route('sendFmv',$f->fmv_id) }}" href="javascript:void(0)">Send</a><br>
                                                          <a href="javascript:void(0)" class="deleteFMV" data-attr-link="{{ route('deleteFmv', $f->fmv_id) }}">Delete</a>
                                                      </td>
                                                      <td @if(empty( $f->sent_date)) class="inWeekOld" @endif>
                                                          @if(empty($f->assignment_id))
                                                              <a href="javascript:void(0)" class="createAssignmentFmv" data-link="{{ route('createAssignmentFromFmv', $f->fmv_id) }}">Create</a> <br> <span class="text-info"> !!! NOT CONVERTED !!!</span>
                                                          @else
                                                              <a href="{{ route('showAssignment',$f->assignment_id) }}">View Assignment</a>
                                                          @endif
                                                      </td>
                                                  </tr>
                                              @endforeach
                                            @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Request</th>
                                                <th>Company</th>
                                                <th>Lease#</th>
                                                <th>
                                                    Sum <br>
                                                    Orig Amt
                                                </th>
                                                <th>
                                                    Sum Value Est.<br>
                                                    FLV/OLV/FMV
                                                </th>
                                                <th>
                                                    Sum Rcvry Est.
                                                    Low/High
                                                </th>
                                                <th>Evaluator</th>
                                                <th>Reason</th>
                                                <th>Client</th>
                                                <th>Sent</th>
                                                <th>Action</th>
                                                <th>Assignment</th>
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
        var getFmvDataTable = $('#getFmvDataTable');

        // Generate Listing for FMV
        getFmvDataTable.DataTable( {
            pageLength:10,
            order: [],
            buttons: [
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

     // Create Assignment from FMV
     body.on('click', '.createAssignmentFmv' , function(e){

         e.preventDefault();

         var postLink = $(this).attr('data-link');

         Swal.fire({
             title: 'Are you sure?',
             text: "You want to convert FMV to Assignment",
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
                 blockExt(getFmvDataTable, $('#waitingMessage'));
                 $.ajax({
                     url: postLink,
                     type: "GET",
                     dataType: "json",
                     headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                     success: function (response) {
                         if (response.status) {

                             unBlockExt(getFmvDataTable);
                             Swal.fire({
                                 title: "Done!",
                                 text: "Assignment was created!",
                                 type: "success",
                                 confirmButtonClass: 'btn btn-primary',
                                 buttonsStyling: false,
                             }).then(function (result) {
                                 if (result.value) {
                                     window.location.replace('/assignment/'+response.assignment_id);
                                 }
                             });
                         } else {
                             $.each(response.errors, function (key, value) {
                                 toastr.error(value)
                             });
                             unBlockExt(getFmvDataTable);

                         }
                     },
                     error: function (xhr, resp, text) {
                         console.log(xhr, resp, text);
                         toastr.error(text);
                         unBlockExt(getFmvDataTable);
                     }
                 });
             }
         })

     });

    // Delete FMV Function
    $('.deleteFMV').click(function(){

        var deleteFmvLink = $(this).attr('data-attr-link');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete this FMV",
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

                blockExt(getFmvDataTable, $('#waitingMessage'));

                $.ajax({
                    url: deleteFmvLink,
                    type: "GET",
                    dataType: "json",
                    headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            unBlockExt(getFmvDataTable);
                            Swal.fire({
                                title: "Deleted!",
                                text: "FMV was deleted!",
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
                            unBlockExt(getFmvDataTable);
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        unBlockExt(getFmvDataTable);
                    }
                });
            }
        })

    });

    // Send FMV Function.
    body.on('click', '.sendFmvList' ,  function(){

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

                    blockExt(getFmvDataTable, $('#waitingMessage'));
                    $.ajax({
                        url: sendFmvLink,
                        type: "GET",
                        dataType: "json",
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                unBlockExt(getFmvDataTable);
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
                                $.each(response.errors, function (key, value) {
                                    toastr.error(value)
                                });
                                unBlockExt(getFmvDataTable);
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            unBlockExt(getFmvDataTable);
                        }
                    });
                }
            })

        });

    });

</script>
@endpush
@endsection
