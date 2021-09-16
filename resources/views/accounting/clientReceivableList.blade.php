@extends('layouts.masterHorizontal')

@section('title','List Client Receivable - InPlace Auction')

@push('page-style')
<style>
    /*.table#getClientInvoiceDataTable td.inWeekOld {
        color: #ff9fbc;
    }
    .table#getClientInvoiceDataTable td a{
        text-decoration: underline;
        color: #bdd1f8;
    }*/
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Client Receivable</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Client Receivable
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
                                        <table class="table" id="getClientInvoiceDataTable">
                                            <thead>
                                            <tr>
                                                <th>Invoice#</th>
                                                <th>Amount</th>
                                                <th>Company</th>
                                                <th>Sent Date</th>
                                                <th>Action</th>
                                                <th>Assignment</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($clientInvoices) && !empty($clientInvoices))
                                                @foreach($clientInvoices as $invoice)
                                                    <tr>
                                                        <td @if($invoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
                                                            @if(!empty($invoice->lines))
                                                                @php $assignmentId = ''; @endphp
                                                                @if(isset($invoice->lines[0]->expense))
                                                                    @if(isset($invoice->lines[0]->expense->item))
                                                                        @php $assignmentId = $invoice->lines[0]->expense->item->ASSIGNMENT_ID @endphp
                                                                    @endif
                                                                @endif
                                                                @if(!empty($assignmentId))
                                                                    <a href="{{ route('viewClientInvoice', [$invoice->client_invoice_id, $assignmentId] ) }}">{{ $invoice->invoice_number }}</a>
                                                                @else
                                                                    -
                                                                @endif
                                                            @else
                                                                -
                                                            @endif

                                                        </td>
                                                        <td @if($invoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
                                                            ${{ round($invoice->invoice_amount,2) }}
                                                        </td>
                                                        <td @if($invoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
                                                            @if(!empty($invoice->client))
                                                                {{ $invoice->client->COMPANY }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td @if($invoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
                                                            @if(!empty( $invoice->sent_dt))
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $invoice->sent_dt)->format('j F, Y')}}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td @if($invoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
                                                            @if($invoice->paid !== 1)
                                                                <a href="javascript:void(0)"
                                                                   class="markClientInvoiceAsPaid"
                                                                   data-amount="{{ round($invoice->invoice_amount,2) }}"
                                                                   data-id="{{ $invoice->client_invoice_id }}"
                                                                   data-toggle="modal"
                                                                   data-target="#clientInvoicePaidModal">Mark as
                                                                    Paid</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td @if($invoice["invoice_color_status"] >= 42 ) class="inWeekOld" @endif>
                                                            <a target="_blank" href="{{ route('showAssignment',$assignmentId ) }}">{{ $assignmentId }}</a>
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
                                                <th>Sent Date</th>
                                                <th>Action</th>
                                                <th>Assignment</th>
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
                                    <input type="number" class="form-control" name="client_amount_paid"
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

    var clientInvoiceId = $('#client_invoice_id');
    var clientInvoicePaidBtn = $('#clientInvoicePaidBtn');
    var clientInvoicePaidModal = $('#clientInvoicePaidModal');

    $(document).ready(function() {
        $('#getClientInvoiceDataTable').DataTable({
            "pageLength": 50
        });

        $('#client_date_paid').pickadate(); // Date Picker

        clientInvoicePaidModal.on('show.bs.modal', function (e) {
            let btn = $(e.relatedTarget);
            let id = btn.data('id');
            let amount = btn.data('amount');
            clientInvoiceId.val(id);
            $('#originalClientInvoice').html('<span class="text-info">$' + amount + '</span>');
        });



        /*** Mark Invoice as Paid Client **/
        clientInvoicePaidBtn.click(function () {

            console.log('client invoice clicked');
            var action = $(this).attr('data-action');

            let client_date_paid = $('#client_date_paid');
            let client_amount_paid = $('#client_amount_paid');
            let client_type_paid = $('#client_type_paid');
            let client_memo_paid = $('#client_memo_paid');

            blockExt($('#clientInvoicePaidModal'), $('#waitingMessage'));

            $.ajax({
                url: action,
                type: "POST",
                dataType: "json",
                data: {
                    'invoice_id': clientInvoiceId.val(),
                    'paid_date': client_date_paid.val(),
                    'amount': client_amount_paid.val(),
                    'type': client_type_paid.val(),
                    'memo': client_memo_paid.val()
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
                                unBlockExt( clientInvoicePaidModal );
                            } else {
                                unBlockExt( clientInvoicePaidModal );
                            }
                            clientInvoicePaidModal.modal('hide');

                            // resetting the values
                            clientInvoiceId.val('');
                            client_date_paid.val('');
                            client_amount_paid.val('');
                            client_type_paid.val('');
                            client_memo_paid.val('');

                            window.location.reload();
                        });
                    } else {
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                        unBlockExt( clientInvoicePaidModal );
                        clientInvoicePaidModal.modal('hide');
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                }
            });

        });

    });

</script>
@endpush
@endsection
