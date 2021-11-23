<div class="card-body p-0">
    <div class="container">
        <div class="row p-1">
            <div class="col-md-9 col-12">
                <div class="d-inline-block">
                    <!-- chart-1   -->
                    <div class="d-flex market-statistics-1">
                        <!-- chart-statistics-1 -->
                        <div id="donut-success-chart"></div>
                        <!-- data -->
                        <!--<div class="statistics-data my-auto">
                            <div class="statistics">
                                @php $totalCustomerInvoiceAmountSent = 0 ; @endphp
                                @if( (isset($customerInvoicesOut)) &&  (count($customerInvoicesOut) > 0))
                                    @foreach( $customerInvoicesOut as $amt)
                                        @php $totalCustomerInvoiceAmountSent += $amt->invoice_amount; @endphp
                                    @endforeach
                                @endif
                                <span class="font-medium-2 mr-50 text-bold-600"> Total ${{ number_format(round($totalCustomerInvoiceAmountSent,2)) }}</span>
                            </div>
                        </div>-->
                    </div>
                </div>
                @php $totalPaidAmount = 0 ;
                 $totalUnPaidAmount = 0 ;
                @endphp
                @if( (isset($customerInvoicesPaid)) &&  (count($customerInvoicesPaid) > 0))
                    @foreach( $customerInvoicesPaid as $amount)
                        @if($amount->paid === 1)
                            @php $totalPaidAmount += $amount->invoice_amount; @endphp
                        @else
                            @php $totalUnPaidAmount += $amount->invoice_amount; @endphp
                        @endif

                    @endforeach
                @endif
                <div class="d-inline-block">
                    <!-- chart-1   -->
                    <div class="d-flex market-statistics-1">
                        <!-- chart-statistics-1 -->
                        <div id="donut-success-chart"></div>
                        <!-- data -->
                        <div class="statistics-data my-auto">
                            <div class="statistics">
                                <span class="font-medium-2 mr-50 text-bold-600 text-success"> Paid ${{ number_format(round($totalPaidAmount,2)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-inline-block">
                    <!-- chart-1   -->
                    <div class="d-flex market-statistics-1">
                        <div id="donut-success-chart"></div>
                        <div class="statistics-data my-auto">
                            <div class="statistics">
                                <span class="font-medium-2 mr-50 text-bold-600 text-danger"> Invoice Sent ${{ number_format(round($totalCustomerInvoiceAmountSent,2)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive" id="customer-receivables" style="height: 500px">
            <!-- table start -->
            <table id="table-marketing-campaigns" class="table table-borderless table-marketing-campaigns mb-0">
                <thead>
                <tr>
                    <th>Invoice#</th>
                    <th>Gen Dt</th>
                    <th>Paid Dt</th>
                    <th>Amount</th>
                    <th>Customer</th>
                    <th>Item</th>
                    <th>Assignment</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @if( (isset($customerInvoicesPaid)) &&  (count($customerInvoicesPaid) > 0))
                    @foreach($customerInvoicesPaid as $customerInvoice)
                        <tr>
                            <td>
                                {{ $customerInvoice->invoice_number }}
                            </td>
                            <td>
                                @if(!empty($customerInvoice->sent_date))
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->sent_date )->format('j F, Y') }}
                                @endif
                            </td>
                            <td>
                                @if(!empty($customerInvoice->paid_dt))
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->paid_dt )->format('j F, Y') }}
                                @endif
                            </td>
                            <td class="@if($customerInvoice->paid === 1) text-success @else text-danger @endif">
                                ${{ round($customerInvoice->invoice_amount,2) }}
                            </td>
                            <td>
                                {{ $customerInvoice->customer->COMPANY }}
                            </td>
                            <td>
                                @if(count($customerInvoice->items) > 0 )
                                    @foreach( $customerInvoice->items as $item)
                                        @if(!empty($item->item))
                                            {{ $item->item->ITEM_ID }}  {{ $item->item->ITEM_NMBR }} {{ $item->item->ITEM_MAKE }} {{ $item->item->ITEM_MODEL }}<br>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @php $assignmentId = ''; @endphp
                                @if(count($customerInvoice->items) > 0)
                                    @foreach( $customerInvoice->items as $item)
                                        @if(!empty($item->item))
                                        @php $assignmentId = $item->item->ASSIGNMENT_ID @endphp
                                        @endif
                                    @endforeach
                                @endif
                                <a href="{{ route('showAssignment', $assignmentId) }}" class="text-info" target="_blank">{{ $assignmentId }}</a>
                            </td>
                            <td>
                                @if($customerInvoice->paid !== 1)
                                    <a href="javascript:void(0)" class="markInvoiceAsPaid"
                                       data-amount="{{ round($customerInvoice->invoice_amount,2) }}"
                                       data-id="{{ $customerInvoice->invoice_auth_id }}"
                                       data-toggle="modal"
                                       data-target="#customerInvoicePaidModal">Mark as
                                        Paid</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">
                            No Pending Customer Receivables
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            <!-- table ends -->
        </div>
    </div>
</div>
<script type="text/javascript">
    new PerfectScrollbar("#customer-receivables", { wheelPropagation: false });
</script>
