<!-- Item List -->
<section id="assignment-files-containers">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Items</h4>
                    <span style="float: right;">
                     @if(count($assignment->items) > 0)
                            <button type="button" class="btn btn-primary mr-1 mb-1"
                                    id="findContractor"
                                    data-toggle="modal"
                                    data-target="#findContractorMap">Find Near By Contractor
                            </button>
                        @endif
                        <button type="button" class="btn btn-primary mr-1 mb-1">
                               <a href="{{ route('addItem', $assignment->assignment_id ) }}" style="color: #fff">Add Item</a>
                        </button>
                    </span>
                </div>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table dataTable zero-configuration">
                                <thead>
                                <tr>
                                    <th>Item#</th>
                                    <th>Categories</th>
                                    <th>Qty</th>
                                    <th>Year</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Serial</th>
                                    <th>FMV</th>
                                    <th>State</th>
                                    <th>Bid</th>
                                    <th>Expense</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($assignment->items) > 0)
                                    @foreach( $assignment->items as $item )
                                        <tr>
                                            <td>{{ $item->ASSIGNMENT_ID }} - {{ $item->ITEM_NMBR }}</td>
                                            <td>
                                                @if(isset($item->categories) && !empty($item->categories))
                                                    @foreach($item->categories as $category)
                                                        @if(isset($category->category)&& !empty($category->category))
                                                            <button type="button"
                                                                    class="btn btn-sm btn-primary glow"
                                                                    style="margin:2px 0;">{{ $category->category->category_name }}</button>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ $item->QUANTITY }} </td>
                                            <td>{{ $item->ITEM_YEAR }}</td>
                                            <td>{{ $item->ITEM_MAKE }}</td>
                                            <td>{{ $item->ITEM_MODEL }}</td>
                                            <td>{{ $item->ITEM_SERIAL }}</td>
                                            <td class="text-success">${{ round($item->FMV,2 )}}</td>
                                            <td>{{ $item->LOC_STATE }}</td>
                                            <td>
                                                   <a href="javascript:void(0)" data-toggle="modal"
                                                   data-id="{{ $item->ITEM_ID }}" data-item="{{ $item->ITEM_SERIAL }}"
                                                   data-target="#addItemBidModal">Place</a>
                                            </td>
                                            <td>   <a href="javascript:void(0)" data-toggle="modal"
                                                   data-id="{{ $item->ITEM_ID }}" data-item="{{ $item->ITEM_SERIAL }}"
                                                   data-target="#addItemExpenseModal">Add</a></td>
                                            <td>
                                                   <a href="{{ route('viewItem', $item->ITEM_ID)}}"
                                                   target="_blank">View</a> /
                                                   <a href="javascript:void(0)"
                                                   class="deleteFile"
                                                   data-attr-link="">Delete</a>
                                            </td>
                                        </tr>
                                        @if( (isset($item->expense)) && (count($item->expense)> 0))
                                            <tr class="tableRow-light">
                                                <td colspan="12" style="border: none">
                                                    <div class="content-wrapper">
                                                        <div class="content-body container">
                                                            <section id="bid-section">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                <h4 class="card-title">Item Expense</h4>
                                                                            </div>
                                                                            <div class="card-content">
                                                                                <div class="card-body">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table dataTable zero-configuration">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>Expense ID#</th>
                                                                                                <th>Type</th>
                                                                                                <th>Date</th>
                                                                                                <th>Amount</th>
                                                                                                <th>Description</th>
                                                                                                <th>Chargeable</th>
                                                                                                <th>From Remittance</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            @foreach($item->expense as $expense)
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        {{ $expense->item_expense_id }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        {{ $expense->expense_type }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        @if(!empty($expense->expense_dt))
                                                                                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $expense->expense_dt )->format('j F, Y') }}
                                                                                                        @endif
                                                                                                    </td>
                                                                                                    <td class="text-danger">
                                                                                                        ${{ round($expense->expense_amount,2) }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        {{ $expense->description }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        @if($expense->chargeable === 1)
                                                                                                            YES
                                                                                                        @elseif($expense->chargeable === 0)
                                                                                                             N0
                                                                                                        @else
                                                                                                            -
                                                                                                        @endif
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        @if($expense->subtract_from_remittance === 1)
                                                                                                            YES @else
                                                                                                            - @endif
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        @if( (isset($item->bids)) && (count($item->bids)>0) )
                                            <tr class="tableRow-light">
                                                <td colspan="12" style="border: none">
                                                    <div class="content-wrapper">
                                                        <div class="content-body container">
                                                            <section id="bid-section">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                <h4 class="card-title">Total Bids ( {{ count($item->bids) }} )</h4>
                                                                            </div>
                                                                            <div class="card-content">
                                                                                <div class="card-body">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table dataTable zero-configuration">
                                                                                            @php $bidAcceptance = TRUE; @endphp
                                                                                            @foreach($item->bids as $bid)
                                                                                                @if( $bid->BID_ACCEPTED === 1 )
                                                                                                    @php $bidAcceptance = FALSE; @endphp
                                                                                                    @break
                                                                                                @endif
                                                                                            @endforeach
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>Customer</th>
                                                                                                <th>Amount</th>
                                                                                                <th>Date</th>
                                                                                                <th>Status</th>
                                                                                                @if($bidAcceptance)
                                                                                                    <th>Accept</th>
                                                                                                @endif
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            @foreach($item->bids as $bid)
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        @if(!empty($bid->customer))
                                                                                                            {{ $bid->customer->FIRSTNAME }} {{ $bid->customer->LASTNAME }}
                                                                                                            <br>
                                                                                                            ( {{ $bid->customer->COMPANY }}
                                                                                                            )
                                                                                                        @endif
                                                                                                    </td>
                                                                                                    <td class="text-success">
                                                                                                        ${{ round($bid->BID, 2) }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $bid->BID_DT)->format('j F, Y') }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        @if($bid->BID_ACCEPTED === 1)
                                                                                                            Accepted @else
                                                                                                            - @endif
                                                                                                    </td>
                                                                                                    @if($bidAcceptance)
                                                                                                        <td>
                                                                                                            <a href="javascript:void(0)"
                                                                                                               class="accept_bid-item"
                                                                                                               data-id="{{ route('acceptBid', $bid->BID_ID) }}">Accept</a>
                                                                                                        </td>
                                                                                                    @endif
                                                                                                </tr>
                                                                                            @endforeach
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </section>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                    @endforeach
                                 @else
                                    <tr>
                                        <td colspan="11" class="text-center">No Items</td>
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
<!-- End Item list -->

<!-- All the Item Modal Boxes Here -->

<!-- Find Contractor Near By  -->
<div class="modal fade text-left" id="authorizeModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="authorizeModal" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Authorize Contractors</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="items">Please select the items to be picked</label>
                        <input type="hidden" name="authorize_contractor_id" id="authorize_contractor_id" value="">
                    </div>
                    <div class="col-12">
                        <div class="row">
                            @if(count($assignment->items) > 0)
                                @foreach( $assignment->items as $item )
                                    <div class="col-1 text-center">
                                        <input type="checkbox" class="form-control" id="items"
                                               name="authorize_item[]" value="{{ $item->ITEM_ID }}">
                                    </div>
                                    <div class="col-11 itemText">
                                        <p class="mb-0"
                                           style="margin-top: 10px;">{{ $item->ITEM_MAKE }} {{ $item->ITEM_MODEL }} {{ $item->ITEM_SERIAL }} {{ $item->LOC_STATE }} </p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="row">
                            <div class="col-md-3 col-12">
                                <label for="v_or_i">Type of PickUp</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <select class="custom-select form-control" name="v_or_i" id="v_or_i">
                                    <option value="V" selected="">Voluntary Pickup</option>
                                    <option value="I">Involuntary Repo</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-12">
                                <label for="method">Method</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                <select class="custom-select form-control" name="method" id="method">
                                    <option value="">Not Selected</option>
                                    <option value="PC">Make Prior Contact</option>
                                    <option value="VA">Visit Unannounced</option>
                                </select>
                            </div>
                            <div class="col-md-3 col-12">
                                <label for="contractorSendEmail">Send to Email</label>
                            </div>
                            <div class="col-md-9 form-group required col-12">
                                <input type="text" class="form-control" name="sendEmail" id="contractorSendEmail"
                                       placeholder="" value="">
                            </div>
                            <div class="col-md-3  col-12">
                                <label for="special_instructions">Special Instructions</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                    <textarea class="form-control char-textarea"
                                              name="special_instructions" id="special_instructions" rows="3"
                                              placeholder="Instructions if any.."></textarea>
                            </div>
                            <div class="col-md-3 col-12">
                                <label for="terms">Terms</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                    <textarea class="form-control char-textarea" name="terms"
                                              id="terms" rows="3" placeholder="Terms if any.."></textarea>
                            </div>
                            <div class="col-md-3 col-12">
                                <label for="additional_info">Additional Information</label>
                            </div>
                            <div class="col-md-9 form-group col-12">
                                    <textarea class="form-control char-textarea" name="additional_info"
                                              id="additional_info" rows="3"
                                              placeholder="Additional info if any.."></textarea>
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
                        data-action="{{ route('authorizeContractor') }}" id="authorizeContractorBtn">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Authorize</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Contractor Near By Modal Box -->

<!-- Place Bid Modal -->
<div class="modal fade text-left" id="addItemBidModal" data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-labelledby="addItemBidModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Place Bid - <span id="itemBidDescModalHeading"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form action="{{ route('addBidsToItem') }}" method="post" id="addItemBidAjax">
                <input type="hidden" name="item_bid_id" id="item_bid_id" value="">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-4 col-12">
                            <label for="customer_id">Customer</label>
                        </div>
                        <div class="col-md-8 form-group col-12">
                            <select class="custom-select form-control" name="customer_id" id="customer_id">
                                <!-- Dynamically we will load -->
                                <option value="">Select</option>
                                @if(isset($customers) && !empty($customers))
                                    @foreach($customers as $customer)
                                        <option data-email={{ $customer->EMAIL }} data-first="{{ $customer->FIRSTNAME }}"
                                                data-last="{{ $customer->LASTNAME }}"
                                                value="{{ $customer->CUSTOMER_ID }}">{{ $customer->FIRSTNAME }} {{ $customer->LASTNAME }} {{ $customer->COMPANY }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="bid_amount">Bid Amount</label>
                        </div>
                        <div class="col-md-8 form-group col-12">
                            <input type="number" class="form-control" id="bid_amount" name="bid_amount">
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="bid_date">Bid Date</label>
                        </div>
                        <div class="col-md-8 form-group col-12">
                            <input type="text" class="form-control" id="bid_date" name="bid_date">
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="bid_comment">Comments</label>
                        </div>
                        <div class="col-md-8 form-group col-12">
                                        <textarea name="bid_comment" id="bid_comment" rows="3"
                                                  style="width: 100%"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="addItemBid" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Add</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Place Bid Modal -->

<!-- Place Item Expense -->
<div class="modal fade text-left" id="addItemExpenseModal" data-backdrop="static" data-keyboard="false"
     tabindex="-1" role="dialog" aria-labelledby="addItemExpenseModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Add Expense - <span id="itemExpenseDescModalHeading"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form action="{{ route('addExpenseToItem') }}" method="post" id="addItemExpenseAjax">
                <input type="hidden" name="item_expense_id" id="item_expense_id" value="">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-4 col-12">
                            <label for="expense_type">Expense Type</label>
                        </div>
                        <div class="col-md-8 form-group col-12">
                            <select class="custom-select form-control" name="expense_type"
                                    id="expense_type">
                                <!-- Dynamically we will load -->
                                <option value="">Select</option>
                                @if(isset($expenseType) && !empty($expenseType))
                                    @foreach($expenseType as $type)
                                        <option value="{{ $type->expense_type }}">{{ $type->expense_type }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="expense_amount">Amount</label>
                        </div>
                        <div class="col-md-8 form-group col-12">
                            <input type="number" class="form-control" id="expense_amount"
                                   name="expense_amount">
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="expense_chargeable">Chargeable</label>
                        </div>
                        <div class="col-md-8 form-group col-12">
                            <select class="custom-select form-control" name="expense_chargeable"
                                    id="expense_chargeable">
                                <option value="1">Please select</option>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-12">
                            <label for="expense_comment">Comments</label>
                        </div>
                        <div class="col-md-8 form-group col-12">
                                        <textarea name="expense_comment" id="expense_comment" rows="3"
                                                  style="width: 100%"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Close</span>
                    </button>
                    <button type="button" id="addItemExpense" class="btn btn-primary ml-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Add</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Place Item Expense -->

<!-- Find Contractor Near By  -->
<div class="modal fade text-left" id="findContractorMap" data-backdrop="static"
     data-keyboard="false" tabindex="-1" role="dialog"
     aria-labelledby="findContractorMap" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered"
         role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Find Contractors</h4>
                <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 py-2 filtersContainer">
                       <div class="row">
                           <div class="col-md-2 col-12">
                               <label for="filterContractors">Filter Contractors</label>
                           </div>
                           <div class="col-md-6 col-12">
                               <select class="form-control custom-select" id="filterContractors" name="filter_contractors[]" style="width: 100%" multiple>
                                   @if(isset($contractors) && !empty($contractors))
                                       @foreach($contractors as $cn)
                                           <option value="{{ $cn['contractor_id'] }}">{{ $cn['first_name'] }} {{ $cn['last_name'] }} {{ $cn['company'] }} {{ $cn['city'] }} {{ $cn['state'] }}</option>
                                       @endforeach
                                   @endif
                               </select>
                           </div>
                           <div class="col-md-2 col-12">
                               <button type="button" class="btn btn-primary mr-1 mb-1" id="filterContractorButton">Filter</button>
                           </div>
                       </div>
                    </div>
                    <div class="col-12">
                        <p><b>NOTE: Yellow marker shows Item locations. Blue markers shows contractor locations.</b></p>
                        <div class="map" id="contractorMap">
                            <!-- Loads via Ajax-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary"
                        data-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Contractor Near By Modal Box -->
