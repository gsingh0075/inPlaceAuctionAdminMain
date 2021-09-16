@extends('layouts.masterHorizontal')

@section('title','List Client Invoices - InPlace Auction')

@push('page-style')
    <style>
        .btn i {
            position: relative;
            top: 3px !important;
        }
    </style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Client Remittance</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Client Remittance
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
                                        <table class="table dataTable" id="getClientRemittanceDataTable">
                                            <thead>
                                            <tr>
                                                <th>Customer Invoice#</th>
                                                <th>Assignment Company Name</th>
                                                <th>Client</th>
                                                <th>Amount Paid</th>
                                                <th>Paid Date</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $assignmentId = '';@endphp
                                                @if(isset($clientRemittance) && !empty($clientRemittance))
                                                    @foreach($clientRemittance as $r)
                                                        <tr>
                                                            <td>
                                                                @php $assignmentCompanyName = '-'; $clientCompanyName = '-'; @endphp
                                                                <a href="{{ route('viewCustomerInvoice', $r->invoice_auth_id ) }}">
                                                                    {{ $r->invoice_number }}
                                                                </a>
                                                                <br>
                                                               @if(isset($r->items) && !empty($r->items))
                                                                  @foreach($r->items as $i)
                                                                    @php $assignmentId = $i->ITEM->ASSIGNMENT_ID;           @endphp
                                                                         @if(isset($i->ITEM->assignment) && !empty($i->ITEM->assignment))
                                                                           @php  $assignmentCompanyName =  $i->ITEM->assignment->ls_company.' ('. $i->ITEM->assignment->ls_full_name.') '; @endphp
                                                                            @if(isset($i->ITEM->assignment->client) && !empty($i->ITEM->assignment->client))
                                                                                @php  $clientCompanyName = $i->ITEM->assignment->client->clientInfo->COMPANY; @endphp
                                                                            @endif
                                                                         @endif


                                                                        <b> ITEM : </b>{{ $i->item->ITEM_MAKE }} {{ $i->item->ITEM_MODEL }} {{ $i->item->ITEM_YEAR }} {{ $i->item->ITEM_SERIAL }} <br>
                                                                        @if(isset($i->item->expense) && !empty($i->item->expense))
                                                                            @foreach($i->item->expense as $e)
                                                                                @if(isset($e->expenseAuth) && !empty($e->expenseAuth))
                                                                                    @if(isset($e->expenseAuth->invoice) && !empty($e->expenseAuth->invoice))
                                                                                        <span class="text-info">Expense Type : {{ $e->expenseAuth->expense_type }} Amount : ${{  round($e->expenseAuth->expense_amount,2)  }} Invoice Status : @if($e->expenseAuth->invoice->paid === 1) <span class="text-success">PAID</span> @else <span class="text-danger">UNPAID</span> @endif</span> <br>
                                                                                    @endif
                                                                                @endif                                                                                                                                                                       @endforeach
                                                                        @endif
                                                                    @endforeach
                                                                      View Assignment : <a class="text-danger" href="{{ route('showAssignment', $assignmentId) }}" target="_blank"> {{ $assignmentId }}</a>
                                                                @endif

                                                            </td>
                                                            <td>
                                                              {{ $assignmentCompanyName }}
                                                            </td>
                                                            <td>
                                                               {{ $clientCompanyName }}
                                                            </td>
                                                            <td> ${{ round($r->paid_amount,2) }}</td>
                                                            <td>
                                                                @if(!empty($r->paid_dt))
                                                                   {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $r->paid_dt)->format('j F, Y') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($r->SENT !== 1)
                                                                    <a href="javascript:void(0)" class="remitClient"
                                                                       data-amount="{{ round($r->paid_amount,2) }}"
                                                                       data-id="{{ $r->invoice_auth_id }}"
                                                                       data-toggle="modal"
                                                                       data-target="#clientRemittanceModal">Remit Amount</a>
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
                                                <th>Customer Invoice#</th>
                                                <th>Assignment Company Name</th>
                                                <th>Client</th>
                                                <th>Amount Paid</th>
                                                <th>Paid Date</th>
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

    <!-- Client Remittance Modal Box -->
    <div class="modal fade text-left" id="clientRemittanceModal" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="clientRemittanceModal" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <form id="remitPaymentForm" action="{{ route('customerAmountRemitted') }}" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Client Remittance ( <b> PAID AMOUNT: </b><span id="originalCustomerPayment"></span> )</h4>
                    <input type="hidden" id="originalCustomerPaymentInput">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row" id="extraExpense">
                                        <!-- Will be updated via Ajax -->
                                    </div>
                                </div>
                                <div class="expense-repeater col-12 mt-2">
                                    <div data-repeater-list="expenseCommission">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <button class="btn btn-icon rounded-circle btn-primary" type="button" data-repeater-create>
                                                    <i class="bx bx-plus"></i>
                                                </button>
                                                <span class="ml-1 font-weight-bold text-primary">ADD EXPENSE</span>
                                            </div>
                                            <div class="col-md-3 col-4 mb-50">
                                                <label class="text-nowrap">Expense Type</label>
                                            </div>
                                            <div class="col-md-6 col-4 mb-50">
                                                <label class="text-nowrap">Amount</label>
                                            </div>
                                            <div class="col-md-3 col-4 mb-50">
                                                <!-- Action label -->
                                            </div>
                                        </div>
                                        <!-- Form Repeater -->
                                        <div class="row justify-content-between" data-repeater-item>
                                            <div class="col-md-3 col-12 form-group d-flex align-items-center">
                                                <i class="bx bx-menu mr-1"></i>
                                                <input type="text" class="form-control" name="expenseType" placeholder="Expense">
                                            </div>
                                            <div class="col-md-6 col-12 form-group">
                                                <input type="text" class="form-control expenseAmount" name="expenseAmount" placeholder="Amount">
                                            </div>
                                            <div class="col-md-3 col-12 form-group">
                                                <button class="btn btn-icon btn-danger rounded-circle" type="button" data-repeater-delete>
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- End Form Repeater -->
                                    </div>
                                </div>
                                <div class="col-12">
                                    <input type="hidden" id="totalFee" value="0">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="remittance_amount_paid">Amount Remitted</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="number" class="form-control" name="remittance_amount_paid"
                                           id="remittance_amount_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="remittance_date_paid">Date paid</label>
                                    <input type="hidden" name="remittance_invoice_id" id="remittance_invoice_id" value="">
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="remittance_date_paid" id="remittance_date_paid" placeholder="" value="">
                                </div>
                                <div class="col-md-3 col-12">
                                    <label for="remittance_type_paid">Check/Wire</label>
                                </div>
                                <div class="col-md-9 form-group col-12">
                                    <input type="text" class="form-control" name="remittance_type_paid" id="remittance_type_paid" placeholder="" value="">
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
                            data-action="{{ route('customerAmountRemitted') }}" id="remitPaidBtn">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Remit Amount</span>
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
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
<script src="{{ asset('app-assets/vendors/js/forms/repeater/jquery.repeater.min.js') }}"></script>
@endpush
@push('page-js')
<script type="text/javascript">


    var remitPaidBtn = $('#remitPaidBtn');
    var clientRemittanceModal = $('#clientRemittanceModal');
    var remittance_invoice_id = $('#remittance_invoice_id');
    var extraExpense = $('#extraExpense');
    var totalFee = $('#totalFee');
    var body = $('body');


    $(document).ready(function(){

        /*$('#getClientRemittanceDataTable').dataTable({
                pageLength: 50
        });*/


        body.on("focusout", '.expenseAmount', function () {
            calculateRemitAmount();
        });

        function calculateRemitAmount(){

            let customerPaid = parseInt($('#originalCustomerPaymentInput').val());
            let feeInputs = $('input[name^="expenseCommission"][name$="[expenseAmount]"]');
            let amountRemit = $('#remittance_amount_paid');

            let feeCalculate = 0;

            feeInputs.each(function(){
                //console.log('Expense'); console.log($(this).val());
                if($(this).val().length !== 0){
                    feeCalculate = parseInt(feeCalculate) + parseInt($(this).val());
                }
            });

            //console.log(feeCalculate);
            totalFee.val(feeCalculate);
            let total = parseInt(customerPaid) - parseInt(feeCalculate);
            //console.log(total);
            amountRemit.val(total);

        }


        $('.expense-repeater').repeater({
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if (confirm('Are you sure you want to delete this element?')) {
                    $(this).slideUp(deleteElement);
                    setTimeout( calculateRemitAmount, 1000);
                }
            }
        });

        $('#remittance_date_paid').pickadate();

        clientRemittanceModal.on('show.bs.modal', function (e) {

            blockExt(clientRemittanceModal, $('#waitingMessage'));

            $('#remitPaymentForm')[0].reset();
            let btn = $(e.relatedTarget);
            let id = btn.data('id');
            let amount = btn.data('amount');
            remittance_invoice_id.val(id);

            $('#originalCustomerPaymentInput').val(amount);
            $('#originalCustomerPayment').html('<span class="text-info">$' + amount + '</span>');

            $.ajax({
                url: "{{ route('getClientRemittanceDetails') }}",
                type: "GET",
                dataType: "json",
                data: {
                    'invoice_auth_id': remittance_invoice_id.val()
                },
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        extraExpense.html(response.html);
                        unBlockExt( clientRemittanceModal );

                    } else {
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                        unBlockExt( clientRemittanceModal );

                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt( clientRemittanceModal );
                }
            });

        });


        /**** Remit Client ******/
        remitPaidBtn.click(function(e) {

            e.preventDefault();
            console.log('Remittance button clicked');
            var action = $(this).attr('data-action');
            var formId = $('#remitPaymentForm');

            let remittance_date_paid = $('#remittance_date_paid');
            let remittance_amount_paid = $('#remittance_amount_paid');
            let remittance_type_paid = $('#remittance_type_paid');

            blockExt(clientRemittanceModal, $('#waitingMessage'));

            $.ajax({
                url: action,
                type: "POST",
                dataType: "json",
                data: formId.serialize(),
                /*{
                    'invoice_auth_id': remittance_invoice_id.val(),
                    'paid_date': remittance_date_paid.val(),
                    'amount': remittance_amount_paid.val(),
                    'type': remittance_type_paid.val(),
                },*/
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            title: "Good job!",
                            text: "Client is successfully remitted",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                //window.location.reload();
                                unBlockExt( clientRemittanceModal );
                            } else {
                                unBlockExt( clientRemittanceModal );
                            }
                            clientRemittanceModal.modal('hide');

                            //resetting the values
                            remittance_invoice_id.val('');
                            remittance_type_paid.val('');
                            remittance_amount_paid.val('');
                            remittance_type_paid.val('');

                            window.location.reload();


                        });
                    } else {
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                        unBlockExt( clientRemittanceModal );
                        //clientRemittanceModal.modal('hide');

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
