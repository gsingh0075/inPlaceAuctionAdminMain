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
                       <div class="statistics-data my-auto">
                           <div class="statistics">
                               @php $totalInvoiceAmountSent = 0 ; @endphp
                               @if( (isset($clientInvoicesOut)) &&  (count($clientInvoicesOut) > 0))
                                   @foreach( $clientInvoicesOut as $amount)
                                       @php $totalInvoiceAmountSent += $amount->invoice_amount; @endphp
                                   @endforeach
                               @endif
                               <!--<span class="font-medium-2 mr-50 text-bold-600"> Total ${{ number_format(round($totalInvoiceAmountSent,2)) }}</span>-->
                           </div>
                       </div>
                   </div>
               </div>
               @php $totalPaidAmount = 0 ;
                    $totalUnPaidAmount = 0 ;
               @endphp
               @if( (isset($clientInvoicesPaid)) &&  (count($clientInvoicesPaid) > 0))
                   @foreach( $clientInvoicesPaid as $amount)
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
                   <div class="d-flex market-statistics-1">
                       <div id="donut-success-chart"></div>
                       <div class="statistics-data my-auto">
                           <div class="statistics">
                               <span class="font-medium-2 mr-50 text-bold-600 text-danger"> Invoice Sent ${{ number_format(round($totalInvoiceAmountSent,2)) }}</span>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
       <div class="table-responsive" id="client-receivable-table" style="height: 500px">
           <!-- table start -->
           <table id="table-marketing-campaigns" class="table table-borderless table-marketing-campaigns mb-0">
               <thead>
               <tr>
                   <th>Invoice#</th>
                   <th>Sent Dt</th>
                   <th>Paid Dt</th>
                   <th>Amount</th>
                   <th>Items</th>
                   <th>Client</th>
                   <th>Assignment</th>
                   <th>Action</th>
               </tr>
               </thead>
               <tbody>
               @if( (isset($clientInvoicesPaid)) &&  (count($clientInvoicesPaid) > 0))
                   @foreach($clientInvoicesPaid as $clientInvoice)
                       <tr>
                           <td>
                               {{ $clientInvoice->invoice_number }}
                           </td>
                           <td>
                               @if(!empty($clientInvoice->sent_dt))
                               {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->sent_dt )->format('j F, Y') }}
                               @endif
                           </td>
                           <td>
                               @if(!empty($clientInvoice->paid_dt1))
                               {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->paid_dt1 )->format('j F, Y') }}
                               @endif
                           </td>
                           <td class="@if($clientInvoice->paid === 1) text-success @else text-danger @endif">
                               ${{ round($clientInvoice->invoice_amount,2) }}
                           </td>
                           <td>
                               @if(isset($clientInvoice->lines) && !empty($clientInvoice->lines))
                                   @foreach($clientInvoice->lines as $line)
                                       @if(isset($line->expense->item) && !empty($line->expense->item))
                                           {{ $line->expense->item->ITEM_MAKE }} {{ $line->expense->item->ITEM_MODEL }} {{ $line->expense->item->ITEM_YEAR }} <br>
                                       @endif
                                   @endforeach
                               @endif
                           </td>
                           <td>
                               @if(!empty($clientInvoice->client)) {{ $clientInvoice->client->COMPANY }} @endif
                           </td>
                           <td>
                               @php $assignmentId = ''; @endphp
                               @if(isset($clientInvoice->lines) && !empty($clientInvoice->lines))
                                   @foreach($clientInvoice->lines as $line)
                                       @if(isset($line->expense->item) && !empty($line->expense->item))
                                           @php $assignmentId = $line->expense->item->ASSIGNMENT_ID @endphp
                                       @endif
                                   @endforeach
                               @endif
                               <a href="{{ route('showAssignment', $assignmentId) }}" class="text-info" target="_blank">{{ $assignmentId }}</a>
                           </td>
                           <td>
                               @if($clientInvoice->paid !== 1)
                                   <a href="javascript:void(0)"
                                      class="markClientInvoiceAsPaid"
                                      data-amount="{{ round($clientInvoice->invoice_amount,2) }}"
                                      data-id="{{ $clientInvoice->client_invoice_id }}"
                                      data-toggle="modal"
                                      data-target="#clientInvoicePaidModal">Mark as
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
                           No Pending Client Receivables
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
    new PerfectScrollbar("#client-receivable-table", { wheelPropagation: false });
</script>
