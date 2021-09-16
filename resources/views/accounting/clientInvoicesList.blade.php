@extends('layouts.masterHorizontal')

@section('title','List Client Invoices - InPlace Auction')

@push('page-style')
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Client Invoices</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Client Invoices
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
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table" id="getClientInvoiceDataTable">
                                            <thead>
                                            <tr>
                                                <th>Invoice#</th>
                                                <th>Amount</th>
                                                <th>Company</th>
                                                <th>View</th>
                                                <th>Sent Date</th>
                                                <th>Status</th>
                                                <th>Account Details</th>
                                                <th>Assignment</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                              @if(isset($clientInvoices) && !empty($clientInvoices))
                                                  @foreach($clientInvoices as $invoice)
                                                      <tr>
                                                          <td  @if($invoice->sent !== 1 ) class="inWeekOld" @endif>
                                                              {{ $invoice->invoice_number }}
                                                          </td>
                                                          <td @if($invoice->sent !== 1 ) class="inWeekOld" @endif>
                                                             ${{ round($invoice->invoice_amount,2) }}
                                                          </td>
                                                          <td @if($invoice->sent !== 1 ) class="inWeekOld" @endif>
                                                            @if(!empty($invoice->client))
                                                                {{ $invoice->client->COMPANY }}
                                                            @else
                                                                -
                                                            @endif
                                                          </td>
                                                          <td @if($invoice->sent !== 1 ) class="inWeekOld" @endif>
                                                            @if(!empty($invoice->lines))
                                                              @php $assignmentId = ''; @endphp
                                                              @php $assignmentDetails = ''; @endphp
                                                              @if(isset($invoice->lines[0]->expense))
                                                                  @if(isset($invoice->lines[0]->expense->item))
                                                                          @php $assignmentId = $invoice->lines[0]->expense->item->ASSIGNMENT_ID;
                                                                                $assignmentDetails = $invoice->lines[0]->expense->item->assignment->ls_full_name.' '.$invoice->lines[0]->expense->item->assignment->ls_company;
                                                                          @endphp
                                                                  @endif
                                                              @endif
                                                              @if(!empty($assignmentId))
                                                                <a href="{{ route('viewClientInvoice', [$invoice->client_invoice_id, $assignmentId] ) }}">View</a>
                                                              @else
                                                                 -
                                                              @endif
                                                            @else
                                                                 -
                                                            @endif
                                                          </td>
                                                          <td @if($invoice->sent !== 1 ) class="inWeekOld" @endif>
                                                              @if(!empty( $invoice->sent_dt))
                                                                  {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->sent_dt)->format('j F, Y')}}
                                                              @else
                                                                  <span class="text-info">!!!! NOT SENT YET !!!!</span>
                                                              @endif
                                                          </td>
                                                          <td @if($invoice->sent !== 1 ) class="inWeekOld" @endif>
                                                              @if( $invoice->paid === 1)
                                                                  <span class="text-success">PAID</span> <br>
                                                                  <b>Paid On:</b> {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->paid_dt1)->format('j F, Y')}} <br>
                                                                  <b>Paid Amount:</b> ${{ round($invoice->paid_amount1,2) }} <br>
                                                                  <b>Reference:</b> {{ $invoice->check_num1 }}
                                                              @else
                                                                  <span class="text-info">!!! NOT PAID !!!</span>
                                                              @endif
                                                          </td>
                                                          <td @if($invoice->sent !== 1 ) class="inWeekOld" @endif>{{ $assignmentDetails }}</td>
                                                          <td @if($invoice->sent !== 1 ) class="inWeekOld" @endif>
                                                              <a target="_blank" href="{{ route('showAssignment',$assignmentId ) }}">{{ $assignmentId }}</a>
                                                          </td>
                                                          <td @if($invoice->sent !== 1 ) class="inWeekOld" @endif>
                                                              <a href="javascript:void(0)" data-id="{{ $invoice->client_invoice_id }}">Delete</a>
                                                          </td>
                                                      </tr>
                                                  @endforeach
                                              @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Invoice#</th>
                                                <th>Amount</th>
                                                <th>Company</th>
                                                <th>View</th>
                                                <th>Sent Date</th>
                                                <th>Status</th>
                                                <th>Account Details</th>
                                                <th>Assignment</th>
                                                <th>Action</th>
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
<script type="text/javascript">
    $(document).ready(function(){
            $('#getClientInvoiceDataTable').dataTable({
                "pageLength": 100,
                "order": [["0","desc"]]
            });
    });
</script>
@endpush
@endsection
