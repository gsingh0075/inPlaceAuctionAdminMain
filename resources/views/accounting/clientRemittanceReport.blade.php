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
                        <h5 class="content-header-title float-left pr-1 mb-0">Client Remittance Report</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Client Remittance Report
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
                                        <table class="table dataTable" id="getClientRemittanceReportDataTable">
                                            <thead>
                                            <tr>
                                                <th>Customer Inv</th>
                                                <th>Client</th>
                                                <th>Customer Paid</th>
                                                <th>Remittance</th>
                                                <th>Rmt Amt</th>
                                                <th>Rmt Date</th>
                                                <th>Check/Wire</th>
                                                <th>Items</th>
                                                <th>Commission</th>
                                                <th>Assignment</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                 @if(isset($clientRemittance) && !empty($clientRemittance))
                                                     @foreach( $clientRemittance as $cr )
                                                         <tr>
                                                             @php $assignmentId = ''; @endphp
                                                             <td>
                                                                 @if(!empty($cr->invoice))
                                                                     <a href="{{ route('viewCustomerInvoice', $cr->invoice->invoice_auth_id ) }}">View</a>
                                                                 @else
                                                                     -
                                                                 @endif
                                                             </td>
                                                             <td>
                                                                 {{ $cr->client->COMPANY }}
                                                             </td>
                                                             <td class="text-success">
                                                                 @if(!empty($cr->invoice))
                                                                     ${{ round($cr->invoice->paid_amount,2) }}
                                                                 @else
                                                                     -
                                                                 @endif
                                                             </td>
                                                             <td><a href="{{ route('viewClientRemittancePdf', $cr->CLIENT_REMITTANCE_ID) }}">{{ $cr->CLIENT_REMITTANCE_NUMBER }}</a></td>
                                                             <td class="text-danger">
                                                                 ${{ round($cr->REMITTANCE_AMT,2) }}
                                                             </td>
                                                             <td>
                                                                 @if(!empty($cr->REMITTANCE_DATE))
                                                                     {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $cr->REMITTANCE_DATE)->format('j F, Y') }}
                                                                 @endif
                                                             </td>
                                                             <td>
                                                                 {{ $cr->CHECKWIRENUM }}
                                                             </td>
                                                             <td>
                                                                 @if(!empty($cr->invoice))
                                                                    @if(!empty($cr->invoice->items))
                                                                        @foreach($cr->invoice->items as $item)
                                                                             @if(!empty($item->item))
                                                                                 @php $assignmentId = $item->item['ASSIGNMENT_ID']; @endphp
                                                                                 {{ $item->item['ITEM_MAKE'] }} {{ $item->item['ITEM_MODEL'] }} {{ $item->item['ITEM_YEAR'] }} ( <b>{{ $item->item['ITEM_SERIAL'] }}</b> ) <br>
                                                                             @endif
                                                                        @endforeach
                                                                    @endif
                                                                 @endif
                                                             </td>
                                                             <td>
                                                                 @if($cr->SENT === 1)
                                                                     @if(!empty($cr->invoice))
                                                                     <span class="text-success">${{ round(($cr->invoice->paid_amount - $cr->REMITTANCE_AMT),2) }}</span> <br>
                                                                     <span class="text-info">{{ round((($cr->invoice->paid_amount-$cr->REMITTANCE_AMT)/$cr->invoice->paid_amount) * 100,2) }} %</span>
                                                                     @endif
                                                                 @else
                                                                     -
                                                                 @endif
                                                             </td>
                                                             <td>
                                                                 <a href="{{ route('showAssignment', $assignmentId) }}" target="_blank">{{ $assignmentId }}</a>
                                                             </td>
                                                         </tr>
                                                     @endforeach
                                                 @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Customer Inv</th>
                                                <th>Client</th>
                                                <th>Customer Paid</th>
                                                <th>Remittance</th>
                                                <th>Rmt Amt</th>
                                                <th>Rmt Date</th>
                                                <th>Check/Wire</th>
                                                <th>Items</th>
                                                <th>Commission</th>
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
<script type="text/javascript">
    $(document).ready(function(){
        $('#getClientRemittanceReportDataTable').dataTable({
            pageLength : 100,
            order : [[3,'desc']],
        })
    });
</script>
@endpush
@endsection
