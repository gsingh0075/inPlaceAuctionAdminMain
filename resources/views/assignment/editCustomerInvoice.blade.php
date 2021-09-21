<!-- Customer Invoice -->
<section id="customer-invoice">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Customer Invoice</h4>
                    <span style="float: right;">
                         <button type="button" class="btn btn-primary mr-1 mb-1" id="addCustomerInvoice"
                          data-toggle="modal" data-target="#itemAddCustomerInvoiceModal">Create/Manage Invoice</button>
                    </span>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table dataTable zero-configuration">
                                <thead>
                                <tr>
                                    <th>Invoice#</th>
                                    <th>Customer</th>
                                    <th>Created Date</th>
                                    <th>Send Date</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Paid Date</th>
                                    <th>Paid Amount</th>
                                    <th>Source</th>
                                    <th>No of Items</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if( (!empty($customerInvoiceData)) && (count($customerInvoiceData) > 0))
                                    @foreach( $customerInvoiceData as $customerInvoice )
                                        <tr>
                                            <td>{{ $customerInvoice->invoice_number }}</td>
                                            <td>{{ $customerInvoice->customer->FIRSTNAME }} {{ $customerInvoice->customer->LASTNAME }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->create_dt)->format('j F, Y') }} </td>
                                            <td>
                                                @if(!empty($customerInvoice->sent_date))
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->sent_date)->format('j F, Y')}}
                                                @endif
                                            </td>
                                            <td>${{ round($customerInvoice->invoice_amount,2) }}</td>
                                            <td> @if($customerInvoice->paid === 1) YES @else NO @endif</td>
                                            <td>
                                                @if(!empty($customerInvoice->paid_dt))
                                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->paid_dt)->format('j F, Y')}}
                                                @endif
                                            </td>
                                            <td @if($customerInvoice->paid === 1) class="text-success"
                                                @else class="text-danger" @endif>
                                                ${{ round($customerInvoice->paid_amount,2) }}</td>
                                            <td>{{ $customerInvoice->check_num }}</td>
                                            <td>{{ count($customerInvoice->items) }}</td>
                                            <td>
                                                <a href="{{ route('viewCustomerInvoice', $customerInvoice->invoice_auth_id ) }}">View</a>
                                                /
                                                @if($customerInvoice->paid !== 1)
                                                    @if($customerInvoice->email_sent === 1)
                                                        <a href="javascript:void(0)"
                                                           class="sendCustomerInvoice"
                                                           data-attr-link="{{ route('sendCustomerInvoice', $customerInvoice->invoice_auth_id ) }}">ReSend</a>
                                                    @else
                                                        <a href="javascript:void(0)"
                                                           class="sendCustomerInvoice"
                                                           data-attr-link="{{ route('sendCustomerInvoice', $customerInvoice->invoice_auth_id ) }}">Send</a>
                                                    @endif
                                                    / <a href="javascript:void(0)" class="markInvoiceAsPaid"
                                                         data-amount="{{ round($customerInvoice->invoice_amount,2) }}"
                                                         data-id="{{ $customerInvoice->invoice_auth_id }}"
                                                         data-toggle="modal"
                                                         data-target="#customerInvoicePaidModal">Mark as
                                                        Paid</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="11" class="text-center">No Customer Invoices</td>
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
<!-- Generate Invoice for Customer -->
<div class="modal fade text-left" id="itemAddCustomerInvoiceModal" data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-labelledby="customerInvoicePaidModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Manage Customer Invoice<span
                            id="originalCustomerInvoice"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-striped zero-configuration">
                                        <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Item#</th>
                                            <th>Item Info</th>
                                            <th>Total Bids</th>
                                            <th>Accepted Customer</th>
                                            <th>City</th>
                                            <th>State</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $acceptedCustomer = array(); @endphp
                                        @if( (!empty($assignment->items)) && (count($assignment->items) > 0))
                                            @foreach( $assignment->items as $item )
                                                <tr>
                                                    <td>
                                                        @php $bidAccepted = FALSE; @endphp
                                                        @if(isset($item->bids) && (count($item->bids)>0) )
                                                            @foreach($item->bids as $bid)
                                                                @if($bid->BID_ACCEPTED === 1)
                                                                    @php $bidAccepted = TRUE; @endphp
                                                                    @if(!in_array($bid->customer->CUSTOMER_ID,$acceptedCustomer ))
                                                                        @php array_push($acceptedCustomer, $bid->customer->CUSTOMER_ID); @endphp
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        @if($bidAccepted && is_null($item->invoiceAuth))
                                                            <input type="checkbox" name="invoice_itemIds[]" value="{{ $item->ITEM_ID }}">
                                                        @else
                                                            <span class="text-danger">NA</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->ITEM_ID }}</td>
                                                    <td>{{ $item->ITEM_MAKE }} {{ $item->ITEM_MODEL }} {{ $item->ITEM_YEAR }}</td>
                                                    <td>
                                                        @if(isset($item->bids))
                                                            {{ count($item->bids) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($bidAccepted)
                                                            @foreach( $customers as $customer )
                                                                @if(in_array($customer->CUSTOMER_ID, $acceptedCustomer))
                                                                    <span class="text-info">{{ $customer->FIRSTNAME }} {{ $customer->LASTNAME }} <br> {{ $customer->COMPANY }}</span>
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            NA
                                                        @endif
                                                    </td>
                                                    <td>{{ $item->LOC_CITY }}</td>
                                                    <td> {{ $item->LOC_STATE }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="11" class="text-center">No Items</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="customer_invoice">Create an invoice for</label>
                                            </div>
                                            <div class="col-md-7 form-group col-12">
                                                <select name="customer_invoice" id="customer_invoice">
                                                    <option value="">Please Select</option>
                                                    @foreach( $customers as $customer )
                                                        @if(in_array($customer->CUSTOMER_ID, $acceptedCustomer))
                                                            <option value="{{ $customer->CUSTOMER_ID }}"> {{ $customer->FIRSTNAME }} {{ $customer->LASTNAME }} {{ $customer->COMPANY }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                <label for="notes_invoice">Notes</label>
                                            </div>
                                            <div class="col-md-7 form-group col-12">
                                                <textarea name="notes_invoice" id="notes_invoice" cols="5" rows="4" style="width:90%"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                        data-action="{{ route('generateCustomerInvoice') }}"
                        id="generateCustomerInvoice">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Generate Invoice</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Generate Invoice for Customer -->
<!-- Customer Paid Invoice Modal -->
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
<!-- Customer Paid Invoice Modal -->
