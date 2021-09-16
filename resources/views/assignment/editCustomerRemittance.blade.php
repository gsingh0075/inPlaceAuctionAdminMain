<!-- Customer Remittance Invoice -->
@php $remittanceYes = false; @endphp
    @if(  (!empty($assignment->items)) && (count($assignment->items) > 0) )
            @foreach($assignment->items as $item)
                @if(!empty($item->invoiceAuth))
                    @if(!empty($item->invoiceAuth->invoice))
                        @if(!empty($item->invoiceAuth->invoice->remittance))
                            @php $remittanceYes = true; @endphp
                        @endif
                    @endif
                @endif
            @endforeach
    @endif
<section id="customer-remittance">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Customer Remittance </h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table dataTable zero-configuration">
                                <thead>
                                <tr>
                                    <th>Remittance #</th>
                                    <th>Invoice Amount</th>
                                    <th>Generate Date</th>
                                    <th>Remittance Date</th>
                                    <th>Amount</th>
                                    <th>Send Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(  (!empty($assignment->items)) && (count($assignment->items) > 0) )
                                    @foreach($assignment->items as $item)
                                        @if(!empty($item->invoiceAuth))
                                            @if(!empty($item->invoiceAuth->invoice))
                                                @if(!empty($item->invoiceAuth->invoice->remittance))
                                                    <tr>
                                                        <td><a href="{{ route('viewClientRemittancePdf', $item->invoiceAuth->invoice->remittance->CLIENT_REMITTANCE_ID) }}">{{ $item->invoiceAuth->invoice->remittance->CLIENT_REMITTANCE_NUMBER }}</a></td>
                                                        <td>${{ round($item->invoiceAuth->invoice->invoice_amount,2) }}</td>
                                                        <td>
                                                            @if(!empty($item->invoiceAuth->invoice->remittance->GENERATED_DATE))
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->invoiceAuth->invoice->remittance->GENERATED_DATE)->format('j F, Y')}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(!empty($item->invoiceAuth->invoice->remittance->REMITTANCE_DATE))
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->invoiceAuth->invoice->remittance->REMITTANCE_DATE)->format('j F, Y')}}
                                                            @endif
                                                        </td>
                                                        <td>${{ round($item->invoiceAuth->invoice->remittance->REMITTANCE_AMT,2) }}</td>
                                                        <td>
                                                            @if(!empty($item->invoiceAuth->invoice->remittance->SENT_DATE))
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->invoiceAuth->invoice->remittance->SENT_DATE)->format('j F, Y')}}
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ route('viewClientRemittancePdf', $item->invoiceAuth->invoice->remittance->CLIENT_REMITTANCE_ID) }}">View</a></td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No Client Remittance</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Customer Invoice -->

<!--
<div class="modal fade text-left" id="customerInvoicePaidModal" data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-labelledby="customerInvoicePaidModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Customer Invoice <span
                            id="originalCustomerInvoice"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <label for="customer_date_paid">Date paid</label>
                                <input type="hidden" name="customer_invoice_id" id="customer_invoice_id"
                                       value="">
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <input type="text" class="form-control" name="customer_date_paid"
                                       id="customer_date_paid" placeholder="" value="">
                            </div>
                            <div class="col-md-3 col-12">
                                <label for="customer_amount_paid">Amount</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <input type="text" class="form-control" name="customer_amount_paid"
                                       id="customer_amount_paid" placeholder="" value="">
                            </div>
                            <div class="col-md-3 col-12">
                                <label for="customer_type_paid">Check/Wire</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <input type="text" class="form-control" name="customer_type_paid"
                                       id="customer_type_paid" placeholder="" value="">
                            </div>
                            <div class="col-md-3  col-12">
                                <label for="customer_memo_paid">Memo</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <input type="text" class="form-control" name="customer_memo_paid"
                                       id="customer_memo_paid" placeholder="" value="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
                <button type="button" class="btn btn-light-secondary"
                        data-action="{{ route('customerInvoicePaid') }}" id="customerInvoicePaidBtn">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Mark as Paid</span>
                </button>
            </div>
        </div>
    </div>
</div>
 Modal -->
