<div class="card-body p-0">
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
                            @if( (isset($clientRemittanceData)) &&  (count($clientRemittanceData) > 0))
                                @foreach( $clientRemittanceData as $amount)
                                    @php $totalPendingAmount += $amount->invoice->paid_amount; @endphp
                                @endforeach
                            @endif
                            <span class="font-medium-2 mr-50 text-bold-600"> Total Paid ${{ number_format(round($totalPendingAmount,2)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @php $totalPaidAmount = 0 ;
                 $totalUnPaidAmount = 0 ;
            @endphp
            @if( (isset($clientRemittanceData)) &&  (count($clientRemittanceData) > 0))
                @foreach( $clientRemittanceData as $amount)
                    @php $totalUnPaidAmount += $amount->invoice->paid_amount; @endphp
                    @if($amount->SENT === 1)
                        @php $totalPaidAmount += $amount->REMITTANCE_AMT; @endphp
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
                            <span class="font-medium-2 mr-50 text-bold-600 text-danger">Remitted ${{ number_format(round($totalPaidAmount,2)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-inline-block">
                <!-- chart-1   -->
                <div class="d-flex market-statistics-1">
                    <!-- chart-statistics-1 -->
                    <div id="donut-success-chart"></div>
                    <!-- data -->
                    <div class="statistics-data my-auto">
                        <div class="statistics">
                            <span class="font-medium-2 mr-50 text-bold-600 text-success"> Profit ${{ number_format(round(($totalUnPaidAmount-$totalPaidAmount),2)) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive" id="remittance-receivable-table" style="height: 500px">
        <!-- table start -->
        <table id="table-marketing-campaigns" class="table table-borderless table-marketing-campaigns mb-0">
            <thead>
            <tr>
                <th>Remittance#</th>
                <th>Customer Paid</th>
                <th>Paid Date</th>
                <th>Remitted Date</th>
                <th>Remitted Amount</th>
                <th>Commission</th>
                <th>Send Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if( (isset($clientRemittanceData)) &&  (count($clientRemittanceData) > 0))
                @foreach($clientRemittanceData as $remittance)
                    <tr>
                        <td>
                            {{ $remittance->CLIENT_REMITTANCE_NUMBER }}
                        </td>
                        <td class="text-info">
                            ${{ round($remittance->invoice->paid_amount,2) }}
                        </td>
                        <td>
                           @if(!empty($remittance->invoice->paid_dt))
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $remittance->invoice->paid_dt )->format('j F, Y') }}
                           @else
                               -
                           @endif
                        </td>
                        <td>
                            @if(!empty($remittance->REMITTANCE_DATE))
                              {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $remittance->REMITTANCE_DATE )->format('j F, Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="@if($remittance->SENT === 1) text-danger @endif">
                            @if($remittance->SENT === 1)
                               ${{ round($remittance->REMITTANCE_AMT,2) }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-info">
                            @if($remittance->SENT === 1)
                                <span class="text-success">${{ round(($remittance->invoice->paid_amount - $remittance->REMITTANCE_AMT),2) }}</span> <br>
                                {{ round((($remittance->invoice->paid_amount-$remittance->REMITTANCE_AMT)/$remittance->invoice->paid_amount) * 100,2) }} %
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if(!empty($remittance->SENT_DATE))
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $remittance->SENT_DATE )->format('j F, Y') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($remittance->SENT !== 1)
                                <a href="javascript:void(0)" class="remitClient"
                                   data-amount="{{ round($remittance->invoice->paid_amount,2) }}"
                                   data-id="{{ $remittance->CLIENT_REMITTANCE_ID }}"
                                   data-toggle="modal"
                                   data-target="#clientRemittanceModal">Remit Amount</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">
                        No Pending Client Remittance
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
        <!-- table ends -->
    </div>
</div>
<script type="text/javascript">
    new PerfectScrollbar("#remittance-receivable-table", { wheelPropagation: false });
</script>