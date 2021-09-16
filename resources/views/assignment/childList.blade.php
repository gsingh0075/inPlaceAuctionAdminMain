 <div class="content-wrapper" style="padding: 5px;">
        <div class="content-body container">
            <!-- Files List -->
            <section id="assignment-files-containers">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Files</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-striped zero-configuration">
                                            <thead>
                                            <tr>
                                                <th>File Name</th>
                                                <th>Type</th>
                                                <th>Upload Date</th>
                                                <th>View</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($assignment->files) > 0)
                                                @foreach( $assignment->files as $file )
                                                    <tr>
                                                        <td>{{ $file->filename }}</td>
                                                        <td>{{ $file->fileType }} </td>
                                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $file->updated_at)->format('j F, Y') }}</td>
                                                        <td><a href="{{ $file->fileSignedUrl }}" target="_blank">View</a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="4" class="text-center">No Files</td></tr>
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
            <!-- End Files list -->
            <section id="chats">
                <div class="row">
                    <div class="col-md-6 col-12 widget-chat-card">
                        <div class="widget-chat widget-chat-messages">
                        <div class="card">
                            <div class="card-header" style="color: #ff5b5c;">
                                Client Communication
                            </div>
                            <div class="card-content">
                                <div class="card-body widget-chat-container widget-chat-scroll" id="widget-public-{{ $assignment->assignment_id }}" style="background-color: #e5e9ed">
                                    <div class="chat-content">
                                        @if(isset($assignment->communicationsPublic) && (count($assignment->communicationsPublic)>0))
                                            @foreach($assignment->communicationsPublic as $communication)
                                                @if($communication->posted_by == 'ADMIN')
                                                    <div class="chat chat-left">
                                                        <div class="chat-body">
                                                            <div class="chat-message">
                                                                <p>{{ $communication->communication_note }}</p>
                                                                <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="chat">
                                                        <div class="chat-body">
                                                            <div class="chat-message">
                                                                <p>{{ $communication->communication_note }}</p>
                                                                <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            <div class="badge badge-pill badge-light-secondary my-1">No conversation found</div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                     </div>
                    </div>

                    <div class="col-md-6 col-12 widget-chat-card">
                        <div class="widget-chat widget-chat-messages">
                            <div class="card">
                                <div class="card-header" style="color: #39da8a;">
                                    Internal Communication
                                </div>
                                <div class="card-content">
                                    <div class="card-body widget-chat-container widget-chat-scroll" id="widget-private-{{ $assignment->assignment_id }}" style="background-color: #e5e9ed">
                                        <div class="chat-content">
                                            @if(isset($assignment->communicationsPrivate) && (count($assignment->communicationsPrivate)>0))
                                                @foreach($assignment->communicationsPrivate as $communication)
                                                    @if($communication->posted_by == 'ADMIN')
                                                        <div class="chat chat-left">
                                                            <div class="chat-body">
                                                                <div class="chat-message">
                                                                    <p>{{ $communication->communication_note }}</p>
                                                                    <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="chat">
                                                            <div class="chat-body">
                                                                <div class="chat-message">
                                                                    <p>{{ $communication->communication_note }}</p>
                                                                    <span class="chat-time">{{ \Carbon\Carbon::createFromTimestamp(strtotime($communication->dt_stmp))->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <div class="badge badge-pill badge-light-secondary my-1">No conversation found</div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

            <!-- Item List -->
            <section id="assignment-files-containers">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Items</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-striped zero-configuration">
                                            <thead>
                                            <tr>
                                                <th>Item#</th>
                                                <th>Categories</th>
                                                <th>Qty</th>
                                                <th>Year</th>
                                                <th>Make</th>
                                                <th>Model</th>
                                                <th>FMV</th>
                                                <th>State</th>
                                                <th>View</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(count($assignment->items) > 0)
                                                @foreach( $assignment->items as $item )
                                                    <tr>
                                                        <td>{{ $item->ITEM_ID }} - {{ $item->ITEM_NMBR }}</td>
                                                        <td>
                                                            @if(isset($item->categories) && !empty($item->categories))
                                                                @foreach($item->categories as $category)
                                                                    @if(isset($category->category)&& !empty($category->category))
                                                                        <button type="button" class="btn btn-sm btn-primary glow" style="margin:2px 0;">{{ $category->category->category_name }}</button>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>{{ $item->QUANTITY }} </td>
                                                        <td>{{ $item->ITEM_YEAR }}</td>
                                                        <td>{{ $item->ITEM_MAKE }}</td>
                                                        <td>{{ $item->ITEM_MODEL }}</td>
                                                        <td>${{ round($item->FMV,2) }}</td>
                                                        <td>{{ $item->LOC_STATE }}</td>
                                                        <td><a href="{{ route('viewItem', $item->ITEM_ID)}}" target="_blank">View</a></td>
                                                    </tr>
                                                    @if(isset($item->expense) && (count($item->expense) > 0))
                                                        <tr class="tableRow-light">
                                                            <td colspan="10" style="border: none">
                                                                <div class="content-wrapper">
                                                                    <div class="contnet-body container">
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
                                                                                                    <table class="table table-striped zero-configuration">
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
                                                                                                                    {{ $expense->expense_dt }}
                                                                                                                </td>
                                                                                                                <td class="text-danger">
                                                                                                                    ${{ round($expense->expense_amount,2) }}
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    {{ $expense->description }}
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    @if($expense->charable === 1) YES @else - @endif
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    @if($expense->subtract_from_remittance === 1) YES @else - @endif
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
                                                    @if(isset($item->bids) && (count($item->bids) >0))
                                                        <tr class="tableRow-light">
                                                            <td colspan="10" style="border: none">
                                                                <div class="content-wrapper">
                                                                    <div class="contnet-body container">
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
                                                                                                    <table class="table table-striped zero-configuration">
                                                                                                        <thead>
                                                                                                        <tr>
                                                                                                            <th>Customer</th>
                                                                                                            <th>Amount</th>
                                                                                                            <th>Date</th>
                                                                                                            <th>Status</th>
                                                                                                        </tr>
                                                                                                        </thead>
                                                                                                        <tbody>
                                                                                                        @foreach($item->bids as $bid)
                                                                                                            <tr>
                                                                                                                <td>
                                                                                                                    @if(!empty($bid->customer))
                                                                                                                        {{ $bid->customer->FIRSTNAME }} {{ $bid->customer->LASTNAME }} <br> ( {{ $bid->customer->COMPANY }})
                                                                                                                    @endif
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    ${{ round($bid->BID, 2) }}
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $bid->BID_DT)->format('j F, Y') }}
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    @if($bid->BID_ACCEPTED === 1) Accepted @else - @endif
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

                                                @endforeach
                                            @else
                                                <tr><td colspan="9" class="text-center">No Items</td></tr>
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

        @if( (!empty($contractData)) && (count($contractData) > 0))
            <!-- Contract Authorization -->
            <section id="assignment-item-authorization">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Item Authorization</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table table-striped zero-configuration">
                                            <thead>
                                            <tr>
                                                <th>Authorization ID</th>
                                                <th>Created Date</th>
                                                <th>Contractor</th>
                                                <th>Number of Items</th>
                                                <th>Send Date</th>
                                                <th>File</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if( (!empty($contractData)) && (count($contractData) > 0))
                                                @foreach( $contractData as $contractor )
                                                    <tr>
                                                        <td>{{ $contractor->contractor_auth_id }}</td>
                                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $contractor->create_dt)->format('j F, Y') }} </td>
                                                        <td>{{ $contractor->contractor->first_name }} {{ $contractor->contractor->last_name }}</td>
                                                        <td>{{ count($contractor->authItems) }}</td>
                                                        <td>
                                                            @if(!empty($contractor->sent_date))
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $contractor->sent_date)->format('j F, Y')}}
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ route('viewContractorAuthorization', [$contractor->contractor_auth_id, $assignment->assignment_id] ) }}">View</a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="6" class="text-center">No Authorizations</td></tr>
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
            @endif
            <!-- End Contract Authorization -->
        @if( (!empty($clientInvoiceData)) && (count($clientInvoiceData) > 0))
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
                                        <table class="table table-striped zero-configuration">
                                            <thead>
                                            <tr>
                                                <th>Invoice#</th>
                                                <th>Created Date</th>
                                                <th>Paid Date</th>
                                                <th>Send Date</th>
                                                <th>Amount</th>
                                                <th>Number of Items</th>
                                                <th>File</th>
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
                                                            @else class="text-danger" @endif>${{ round($clientInvoice->invoice_amount,2) }}</td>
                                                        <td>{{ count($clientInvoice->lines) }}</td>
                                                        <td><a href="{{ route('viewClientInvoice', [$clientInvoice->client_invoice_id, $assignment->assignment_id] ) }}">View</a></td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr><td colspan="6" class="text-center">No Client Invoices</td></tr>
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
            @endif

        </div>
    </div>
 <script type="text/javascript">
     $(document).ready(function(){
         //alert('Child opened');
         new PerfectScrollbar("#widget-public-{{ $assignment->assignment_id }}", { wheelPropagation: false });
         new PerfectScrollbar("#widget-private-{{ $assignment->assignment_id }}", { wheelPropagation: false });
     });
 </script>
