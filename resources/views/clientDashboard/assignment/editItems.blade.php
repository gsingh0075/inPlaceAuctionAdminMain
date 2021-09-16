<!-- Item List -->
<section id="assignment-files-containers">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table dataTable zero-configuration">
                                <thead>
                                <tr>
                                    <th>Item#</th>
                                    <th>Qty</th>
                                    <th>Year</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Serial</th>
                                    <th>State</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($assignment->items) > 0)
                                    @foreach( $assignment->items as $item )
                                        <tr>
                                            <td>{{ $item->ASSIGNMENT_ID }} - {{ $item->ITEM_NMBR }}</td>
                                            <td>{{ $item->QUANTITY }} </td>
                                            <td>{{ $item->ITEM_YEAR }}</td>
                                            <td>{{ $item->ITEM_MAKE }}</td>
                                            <td>{{ $item->ITEM_MODEL }}</td>
                                            <td>{{ $item->ITEM_SERIAL }}</td>
                                            <td>{{ $item->LOC_STATE }}</td>
                                        </tr>
                                        @if( (isset($item->clientReports) && count($item->clientReports) > 0) )
                                            <tr class="tableRow-light">
                                                <td colspan="12" style="border: none">
                                                    <div class="content-wrapper">
                                                        <div class="content-body container">
                                                            <section id="bid-section">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="card">
                                                                            <div class="card-header">
                                                                                <h4 class="card-title text-info"> !! Condition Reports !!</h4>
                                                                            </div>
                                                                            <div class="card-content">
                                                                                <div class="card-body">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table dataTable zero-configuration">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>Name</th>
                                                                                                <th>Date</th>
                                                                                                <th>View</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            @foreach($item->clientReports as $report)
                                                                                                <tr>
                                                                                                    <td>
                                                                                                       {{ $report->logs }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $report->created_at)->format('j F, Y') }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <a href="{{ $report->fileSignedUrl }}" target="_blank">View</a>
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
                                                                                <h4 class="card-title text-info"> !! Total Bids ( {{ count($item->bids) }} ) !!</h4>
                                                                            </div>
                                                                            <div class="card-content">
                                                                                <div class="card-body">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table dataTable zero-configuration">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>Amount</th>
                                                                                                <th>Date</th>
                                                                                            </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                            @foreach($item->bids as $bid)
                                                                                                <tr>
                                                                                                    <td class="text-success">
                                                                                                        ${{ round($bid->BID, 2) }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $bid->BID_DT)->format('j F, Y') }}
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