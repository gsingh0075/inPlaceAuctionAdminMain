<!-- Client Invoice -->
<section id="client-invoice">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table dataTable zero-configuration">
                                <thead>
                                <tr>
                                    <th>Invoice#</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Number of Items</th>
                                    <th>View</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if( (!empty($clientInvoiceData)) && (count($clientInvoiceData) > 0))
                                    @foreach( $clientInvoiceData as $clientInvoice )
                                        <tr>
                                            <td>{{ $clientInvoice->invoice_number }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $clientInvoice->create_dt)->format('j F, Y') }} </td>
                                            <td>
                                                @if(empty( $clientInvoice->paid_dt1))
                                                    <span class="text-info"> !! NOT PAID !!</span>
                                                @else
                                                    PAID
                                                @endif
                                            </td>
                                            <td @if($clientInvoice->paid === 1) class="text-success"
                                                @else class="text-danger" @endif>
                                                ${{ round($clientInvoice->invoice_amount,2) }}</td>
                                            <td>{{ count($clientInvoice->lines) }}</td>
                                            <td> <a href="{{ route('viewClientInvoice', [$clientInvoice->client_invoice_id, $assignment->assignment_id] ) }}">View</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">No Invoices</td>
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
