@extends('layouts.masterHorizontal')

@section('title','List Client Invoices - InPlace Auction')

@push('page-style')
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Customer Receivables</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Customer Receivables
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Zero configuration table -->
            <section id="basic-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <div class="col-12 text-left pb-2" style="font-size: 15px;">
                                            Total Amount: <span class="text-danger">${{ round($totalPendingAmount, 2) }}</span>
                                        </div>
                                        <table class="table" id="getCustomerInvoiceDataTable">
                                            <thead>
                                            <tr>
                                                <th>Invoice#</th>
                                                <th>Amount</th>
                                                <th>Company</th>
                                                <th>Number of Items</th>
                                                <th>Items</th>
                                                <th>View</th>
                                                <th>Created Date</th>
                                                <th>Assignment</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if( (!empty($customerInvoices)) && (count($customerInvoices) > 0))
                                                @foreach( $customerInvoices as $customerInvoice )
                                                    <tr>
                                                        @php $assignmentId = ''; @endphp
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif><a href="{{ route('viewCustomerInvoice', $customerInvoice->invoice_auth_id ) }}">{{ $customerInvoice->invoice_number }}</a></td>
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>${{ round($customerInvoice->invoice_amount,2) }}</td>
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>{{ $customerInvoice->customer->COMPANY }} </td>
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>{{ count($customerInvoice->items) }}</td>
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
                                                            @if(!empty($customerInvoice->items))
                                                                @foreach( $customerInvoice->items as $i)
                                                                    @if(!empty($i->item))
                                                                        @php $assignmentId = $i->item->ASSIGNMENT_ID; @endphp
                                                                        {{ $i->item['ITEM_MAKE'] }} {{ $i->item['ITEM_MODEL'] }} {{ $i->item['ITEM_YEAR'] }}  ( <b>{{ $i->item['ITEM_SERIAL'] }}</b> ) <br>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif><a href="{{ route('viewCustomerInvoice', $customerInvoice->invoice_auth_id ) }}">View</a></td>
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->create_dt)->format('j F, Y') }} </td>
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
                                                            <a href="{{ route('showAssignment', $assignmentId) }}">{{ $assignmentId }}</a>
                                                        </td>
                                                        <td @if($customerInvoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
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
                                            @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Invoice#</th>
                                                <th>Amount</th>
                                                <th>Company</th>
                                                <th>Number of Items</th>
                                                <th>Items</th>
                                                <th>View</th>
                                                <th>Created Date</th>
                                                <th>Assignment</th>
                                                <th>Action</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Zero configuration table -->
        </div>
    </div>

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
                                    <input type="number" class="form-control" name="customer_amount_paid"
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
@push('page-vendor-js')
<script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('app-assets/js/scripts/moment/moment.js') }}"></script>
@endpush
@push('page-js')
<script>

    var customerInvoicePaidBtn = $('#customerInvoicePaidBtn');
    var customerInvoicePaidModal = $('#customerInvoicePaidModal');
    var customerInvoiceId = $('#customer_invoice_id');


    $(document).ready(function() {

        $('#getCustomerInvoiceDataTable').DataTable({
            pageLength : 100,
          });

        $('#customer_date_paid').pickadate();

        customerInvoicePaidModal.on('show.bs.modal', function (e) {
            let btn = $(e.relatedTarget);
            let id = btn.data('id');
            let amount = btn.data('amount');
            customerInvoiceId.val(id);
            $('#originalCustomerInvoice').html('<span class="text-info">$ ' + amount + '</span>');
        });

        /**** Mark Invoice Paid as Customer ****/
        customerInvoicePaidBtn.click(function () {

            console.log('customer invoice button clicked');
            var action = $(this).attr('data-action');

            let customer_date_paid = $('#customer_date_paid');
            let customer_amount_paid = $('#customer_amount_paid');
            let customer_type_paid = $('#customer_type_paid');
            let customer_memo_paid = $('#customer_memo_paid');

            blockExt(customerInvoicePaidModal, $('#waitingMessage'));

            $.ajax({
                url: action,
                type: "POST",
                dataType: "json",
                data: {
                    'invoice_id': customerInvoiceId.val(),
                    'paid_date': customer_date_paid.val(),
                    'amount': customer_amount_paid.val(),
                    'type': customer_type_paid.val(),
                    'memo': customer_memo_paid.val()
                },
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            title: "Good job!",
                            text: "Invoice is marked as paid",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                //window.location.reload();
                                unBlockExt( customerInvoicePaidModal );
                            } else {
                                unBlockExt( customerInvoicePaidModal );
                            }
                            customerInvoicePaidModal.modal('hide');

                            // Resetting the values
                            customerInvoiceId.val('');
                            customer_date_paid.val('');
                            customer_amount_paid.val('');
                            customer_type_paid.val('');
                            customer_memo_paid.val('');

                            window.location.reload();


                        });
                    } else {
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                        unBlockExt( customerInvoicePaidModal );
                        customerInvoicePaidModal.modal('hide');
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    //unBlockFMVContainer();
                }
            });

        });


    });

</script>
@endpush
@endsection
