<div class="row p-1">
        <div class="col-md-9 col-12">
            <div class="d-inline-block">
                <!-- chart-1   -->
                <div class="d-flex market-statistics-1">
                    <!-- chart-statistics-1 -->
                    <div id="donut-success-chart"></div>
                    <!-- data -->
                    <div class="statistics-data my-auto">
                        <div class="statistics">
                            @php $totalPendingAmount = 0 ; @endphp
                            @if( (isset($clientInvoicesOut)) &&  (count($clientInvoicesOut) > 0))
                                @foreach( $clientInvoicesOut as $amount)
                                    @php $totalPendingAmount += $amount->invoice_amount; @endphp
                                @endforeach
                            @endif
                            <span class="font-medium-2 mr-50 text-bold-600"> Total ${{ number_format(round($totalPendingAmount,2)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive" id="client-receivable-table" style="height: 635px">
        <!-- table start -->
        <table id="table-marketing-campaigns" class="table table-borderless table-marketing-campaigns mb-0">
            <thead>
            <tr>
                <th>Invoice#</th>
                <th>Sent Dt</th>
                <th>Amount</th>
                <th>Assignment</th>
            </tr>
            </thead>
            <tbody>
            @if( (isset($clientInvoicesOut)) &&  (count($clientInvoicesOut) > 0))
                @foreach($clientInvoicesOut as $clientInvoice)
                    <tr>
                        <td>
                            <a href="{{ route('getMyInvoices',[$clientInvoice->client_invoice_id,$clientInvoice->lines[0]->expense->item->ASSIGNMENT_ID]) }}">{{ $clientInvoice->invoice_number }}</a>
                        </td>
                        <td>
                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->sent_dt )->format('j F, Y') }}
                        </td>
                        <td class="@if($clientInvoice->paid === 1) text-success @else text-danger @endif">
                            ${{ round($clientInvoice->invoice_amount,2) }}
                        </td>
                        <td>
                            <a href="{{ route('showAssignmentClient', $clientInvoice->lines[0]->expense->item->ASSIGNMENT_ID ) }}">
                                IPA # {{ $clientInvoice->lines[0]->expense->item->ASSIGNMENT_ID }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">
                        No Pending Invoices.
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
        <!-- table ends -->
    </div>
<script type="text/javascript">
    new PerfectScrollbar("#client-receivable-table", { wheelPropagation: false });
</script>
