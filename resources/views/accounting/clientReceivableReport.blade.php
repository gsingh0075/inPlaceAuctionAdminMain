@extends('layouts.masterHorizontal')

@section('title','List Client Invoices - InPlace Auction')

@push('page-style')
<style>
    .table#getClientInvoiceDataTable td a{
        text-decoration: underline;
        color: #bdd1f8;
    }
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Client Receivable Report</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Client Receivable Report
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
                                                <th>Client</th>
                                                <th>Inv#</th>
                                                <th>Total</th>
                                                <th>Avg Inv</th>
                                                <th>#Paid</th>
                                                <th>Avg Payment <br>
                                                    Turnaround (Days)</th>
                                                <th>Paid</th>
                                                <th>Total Paid</th>
                                                <th>#UnPaid</th>
                                                <th>UnPaid</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                              @if(isset($clientInvoices) && !empty($clientInvoices))
                                                  @foreach($clientInvoices as $invoice)
                                                      <tr>
                                                          <td>
                                                              {{ $invoice->COMPANY }}
                                                          </td>
                                                          <td>
                                                             {{ count($invoice->invoices) }}
                                                          </td>
                                                          <td class="text-danger">
                                                            @php $totalInvoiceAmount = 0;
                                                                 $totalNumberOfInvoices = 0;
                                                                 $totalDaysToPay = 0;
                                                                 $totalInvoicePaidAmount = 0;
                                                                 $totalPaidInvoice = 0;
                                                                 $totalUnpaidAmount = 0;
                                                                 $totalUnPaidInvoice = 0;
                                                            @endphp
                                                            @if(!empty($invoice->invoices))
                                                                @foreach($invoice->invoices as $i)
                                                                      @if($i['paid'] === 1)
                                                                          @php $totalInvoicePaidAmount += $i['invoice_amount'];
                                                                               $totalPaidInvoice +=1;
                                                                          @endphp
                                                                      @else
                                                                          @php $totalUnpaidAmount += $i['invoice_amount'];
                                                                               $totalUnPaidInvoice +=1;
                                                                          @endphp
                                                                      @endif
                                                                      @php $totalInvoiceAmount += $i['invoice_amount'];
                                                                           $totalNumberOfInvoices += 1;
                                                                           $totalDaysToPay += $i['time_pay_days'];
                                                                      @endphp
                                                                @endforeach
                                                            @endif
                                                              ${{ round($totalInvoiceAmount,2) }}
                                                          </td>
                                                          <td>
                                                             {{ $totalPaidInvoice }}
                                                          </td>
                                                          <td class="text-info">
                                                              ${{ round($totalInvoiceAmount/$totalNumberOfInvoices,2) }}
                                                          </td>
                                                          <td>
                                                              {{ ceil($totalDaysToPay/$totalNumberOfInvoices) }}
                                                          </td>
                                                          <td class="text-success">
                                                              ${{ round($totalInvoicePaidAmount,2) }}
                                                          </td>
                                                          <td class="text-success">
                                                              ${{ round($totalInvoicePaidAmount,2) }}
                                                          </td>
                                                          <td>
                                                              {{ $totalUnPaidInvoice }}
                                                          </td>
                                                          <td class="text-danger">
                                                               ${{ round($totalUnpaidAmount,2) }}
                                                          </td>
                                                      </tr>
                                                  @endforeach
                                              @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Client</th>
                                                <th>Inv#</th>
                                                <th>Total</th>
                                                <th>Avg Inv</th>
                                                <th>#Paid</th>
                                                <th>Avg Payment <br>
                                                    Turnaround (Days)</th>
                                                <th>Paid</th>
                                                <th>Total Paid</th>
                                                <th>#UnPaid</th>
                                                <th>UnPaid</th>
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
            pageLength: 200
        });
    });
</script>
@endpush
@endsection
