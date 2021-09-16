<!-- Client Invoice -->
<section id="client-invoice">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Client Invoice</h4>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table dataTable zero-configuration">
                                <thead>
                                <tr>
                                    <th>Invoice#</th>
                                    <th>Created Date</th>
                                    <th>Paid Date</th>
                                    <th>Send Date</th>
                                    <th>Amount</th>
                                    <th>Number of Items</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if( (!empty($clientInvoiceData)) && (count($clientInvoiceData) > 0))
                                    @foreach( $clientInvoiceData as $clientInvoice )
                                        <tr>
                                            <td>{{ $clientInvoice->invoice_number }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->create_dt)->format('j F, Y') }} </td>
                                            <td>
                                                @if(!empty( $clientInvoice->paid_dt1))
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->paid_dt1)->format('j F, Y')}}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty( $clientInvoice->sent_dt))
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->sent_dt)->format('j F, Y')}}
                                                @endif
                                            </td>
                                            <td @if($clientInvoice->paid === 1) class="text-success"
                                                @else class="text-danger" @endif>
                                                ${{ round($clientInvoice->invoice_amount,2) }}</td>
                                            <td>{{ count($clientInvoice->lines) }}</td>
                                            <td>
                                                <a href="{{ route('viewClientInvoice', [$clientInvoice->client_invoice_id, $assignment->assignment_id] ) }}">View</a>
                                                /

                                                @if($clientInvoice->paid !== 1)
                                                    @if($clientInvoice->sent === 1)
                                                        <a href="javascript:void(0)"
                                                           class="sendClientInvoice"
                                                           data-attr-link="{{ route('sendClientInvoice', [$clientInvoice->client_invoice_id, $assignment->assignment_id] ) }}">ReSend</a>
                                                    @else
                                                        <a href="javascript:void(0)"
                                                           class="sendClientInvoice"
                                                           data-attr-link="{{ route('sendClientInvoice', [$clientInvoice->client_invoice_id, $assignment->assignment_id] ) }}">Send</a>
                                                    @endif

                                                    / <a href="javascript:void(0)"
                                                         class="markClientInvoiceAsPaid"
                                                         data-amount="{{ round($clientInvoice->invoice_amount,2) }}"
                                                         data-id="{{ $clientInvoice->client_invoice_id }}"
                                                         data-toggle="modal"
                                                         data-target="#clientInvoicePaidModal">Mark as
                                                        Paid</a>

                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No Client Invoices</td>
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
<!-- Client Invoice -->

<!-- Client Paid Invoice Modal -->
<div class="modal fade text-left" id="clientInvoicePaidModal" data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-labelledby="clientInvoicePaidModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Client Invoice <span
                            id="originalClientInvoice"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <label for="client_date_paid">Date paid</label>
                                <input type="hidden" name="client_invoice_id" id="client_invoice_id"
                                       value="">
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <input type="text" class="form-control" name="client_date_paid"
                                       id="client_date_paid" placeholder="" value="">
                            </div>
                            <div class="col-md-3 col-12">
                                <label for="client_amount_paid">Amount</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <input type="text" class="form-control" name="client_amount_paid"
                                       id="client_amount_paid" placeholder="" value="">
                            </div>
                            <div class="col-md-3 col-12">
                                <label for="client_type_paid">Check/Wire</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <input type="text" class="form-control" name="client_type_paid"
                                       id="client_type_paid" placeholder="" value="">
                            </div>
                            <div class="col-md-3  col-12">
                                <label for="client_memo_paid">Memo</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <input type="text" class="form-control" name="client_memo_paid"
                                       id="client_memo_paid" placeholder="" value="">
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
                        data-action="{{ route('clientInvoicePaid') }}" id="clientInvoicePaidBtn">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Mark as Paid</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Client Paid Invoice Modal -->
