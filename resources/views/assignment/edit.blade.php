@extends('layouts.masterHorizontal')

@section('title','Edit Assignment - InPlace Auction')

@push('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/plugins/file-uploaders/dropzone.css') }}">
    <style>
        #contractorMap {
            height: 500px;
        }

        #contractorMarkerContent p {
            color: #000;
            margin-bottom: 10px;
        }

        .progress {
            height: 1.4rem;
        }

        .progress .progress-bar {
            border-radius: 0;
        }
    </style>
@endpush
@section('content')
    <div class="content-wrapper">
        <div class="content-header container">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Assignment - {{ $assignment->assignment_id }} </h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('getAssignment') }}">List</a>
                                </li>
                                <li class="breadcrumb-item active">Edit
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body container">
            <div class="col-12 p-0 mb-2">
                <div class="progress">
                    @if(isset($assignment->assignment_id) && !empty($assignment->assignment_id))
                        <div class="progress-bar" role="progressbar" style="width: 20%" aria-valuenow="15" aria-valuemin="0"
                             aria-valuemax="100"> Assignment Created
                        </div>
                       @php $recoveryStatus = false; $soldStatus = false; $customerPaid = false; $clientPaid = false; @endphp
                       @if(isset($assignment->items) && count($assignment->items) > 0 )
                           @foreach($assignment->items as $item)
                               @if(!empty($item->itemContractor))
                                   @php $recoveryStatus = true; @endphp
                               @endif
                               @if(!empty($item->invoiceAuth))
                                       @php $soldStatus = true; @endphp
                                    @if(isset($item->invoiceAuth->invoice) && !empty($item->invoiceAuth->invoice))
                                        @if($item->invoiceAuth->invoice->paid === 1)
                                            @php $customerPaid = true; @endphp
                                        @endif
                                        @if(isset($item->invoiceAuth->invoice->remittance) && !empty($item->invoiceAuth->invoice->remittance))
                                             @if($item->invoiceAuth->invoice->remittance->SENT === 1)
                                                   @php $clientPaid = true; @endphp
                                             @endif
                                        @endif
                                   @endif
                               @endif
                           @endforeach
                            @if($recoveryStatus)
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 20%" aria-valuenow="30"
                                 aria-valuemin="0" aria-valuemax="100">Item Recovery
                            </div>
                                 @if($soldStatus)
                                       <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" aria-valuenow="30"
                                            aria-valuemin="0" aria-valuemax="100">Sold Items
                                       </div>
                                       @if($customerPaid)
                                           <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20"
                                                aria-valuemin="0" aria-valuemax="100">Customer Paid
                                           </div>
                                           @if($clientPaid)
                                               <div class="progress-bar bg-success" role="progressbar" style="width: 20%" aria-valuenow="20"
                                                    aria-valuemin="0" aria-valuemax="100">Remitted Client
                                               </div>
                                           @endif
                                       @endif
                                 @endif

                            @endif
                       @endif
                    @endif
                </div>
            </div>
            <!-- Edit Assignment Form -->

            <!-- Lets make the Tabs here -->
               <div class="col-12 p-0">
                   <ul class="nav nav-pills my-1 mx-1" id="pills-tab" role="tablist">
                       <li class="nav-item" role="presentation">
                           <a class="nav-link active" id="v-pills-lease-tab" data-toggle="pill" href="#v-pills-lease" role="tab" aria-controls="v-lease-home" aria-selected="true">Lease Details</a>
                       </li>
                       <li class="nav-item" role="presentation">
                           <a  class="nav-link" id="v-pills-files-tab" data-toggle="pill" href="#v-pills-files" role="tab" aria-controls="v-pills-files" aria-selected="false">Files</a>
                       </li>
                       <li class="nav-item" role="presentation">
                           <a class="nav-link" id="v-pills-chat-tab" data-toggle="pill" href="#v-pills-chat" role="tab" aria-controls="v-pills-chat" aria-selected="false">Chats</a>
                       </li>
                       <li class="nav-item" role="presentation">
                           <a class="nav-link" id="v-pills-items-tab" data-toggle="pill" href="#v-pills-items" role="tab" aria-controls="v-pills-items" aria-selected="false">Items</a>
                       </li>
                       <li class="nav-item" role="presentation">
                           <a class="nav-link" id="v-pills-authorization-tab" data-toggle="pill" href="#v-pills-authorization" role="tab" aria-controls="v-pills-settings" aria-selected="false">Authorizations</a>
                       </li>
                       <li class="nav-item" role="presentation">
                           <a class="nav-link" id="v-pills-clientInvoice-tab" data-toggle="pill" href="#v-pills-clientInvoice" role="tab" aria-controls="v-pills-clientInvoice" aria-selected="false">Client Invoices</a>
                       </li>
                       <li class="nav-item" role="presentation">
                           <a class="nav-link" id="v-pills-customerInvoice-tab" data-toggle="pill" href="#v-pills-customerInvoice" role="tab" aria-controls="v-pills-customerInvoice" aria-selected="false">Customer Invoice</a>
                       </li>
                       <li class="nav-item" role="presentation">
                           <a class="nav-link" id="v-pills-clientRemittance-tab" data-toggle="pill" href="#v-pills-clientRemittance" role="tab" aria-controls="v-pills-clientRemittance" aria-selected="false">Client Remittance</a>
                       </li>
                   </ul>
               </div>
            <!-- Lets End the Tabs here -->


            <section id="floating-label-layouts">
                <div class="row match-height">
                    <div class="col-12 addContainer" id="fmv-update-container">
                        <div class="card">

                            <!-- Lets Start the Tab Layout -->
                            <div class="tab-content" id="v-pills-tabContent">
                                <div class="tab-pane fade show active" id="v-pills-lease" role="tabpanel" aria-labelledby="v-pills-lease-tab">
                                    @include('assignment.editLeaseDetails')

                                </div>
                                <div class="tab-pane fade" id="v-pills-files" role="tabpanel" aria-labelledby="v-pills-files-tab">
                                    @include('assignment.editFiles')

                                </div>
                                <div class="tab-pane fade" id="v-pills-chat" role="tabpanel" aria-labelledby="v-pills-chat-tab">

                                    @include('assignment.editChats')
                                </div>
                                <div class="tab-pane fade" id="v-pills-items" role="tabpanel" aria-labelledby="v-pills-items-tab">

                                    @include('assignment.editItems')
                                </div>
                                <div class="tab-pane fade" id="v-pills-authorization" role="tabpanel" aria-labelledby="v-pills-authorization-tab">

                                    @include('assignment.editItemsAuthorizations')

                                </div>
                                <div class="tab-pane fade" id="v-pills-clientInvoice" role="tabpanel" aria-labelledby="v-pills-clientInvoice-tab">

                                    @include('assignment.editClientInvoice')

                                </div>
                                <div class="tab-pane fade" id="v-pills-customerInvoice" role="tabpanel" aria-labelledby="v-pills-customerInvoice-tab">

                                    @include('assignment.editCustomerInvoice')
                                </div>
                                <div class="tab-pane fade" id="v-pills-clientRemittance" role="tabpanel" aria-labelledby="v-pills-clientRemittance-tab">
                                    @include('assignment.editCustomerRemittance')

                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>


    @push('page-vendor-js')
        <script src="{{ asset('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
        <script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDOSZ6FRxGMp9PN_6TDuiY7mfa0CQZlXJg"></script>
    @endpush
    @push('page-js')
        <script>

            // Ready Function
            var addItemBidModal = $('#addItemBidModal');
            var itemBidId = $('#item_bid_id');
            var addItemExpenseModal = $('#addItemExpenseModal');
            var itemExpenseId = $('#item_expense_id');
            var authorizeContractorInput = $('#authorize_contractor_id');
            var authorizeContractorModal = $('#authorizeModal');

            var customerInvoicePaidBtn = $('#customerInvoicePaidBtn');
            var customerInvoicePaidModal = $('#customerInvoicePaidModal');
            var customerInvoiceId = $('#customer_invoice_id');

            var clientInvoicePaidBtn = $('#clientInvoicePaidBtn');
            var clientInvoicePaidModal = $('#clientInvoicePaidModal');
            var clientInvoiceId = $('#client_invoice_id');

            var visibilityReport = $('.visibilityReport');
            var itemExpenseDescModal = $('#itemExpenseDescModalHeading');
            var itemBidDescModal = $('#itemBidDescModalHeading');

            $('#bid_date').pickadate(); // Date Picker

            // Visibility Click
            visibilityReport.click(function(){

                var fileId = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                var visibilityUrl = $(this).attr('data-url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Report Visibility will be affected on the client portal",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        blockExt($('.content-wrapper'), $('#waitingMessage'));
                        $.ajax({
                            url: visibilityUrl,
                            type: "POST",
                            dataType: "json",
                            data : {
                                status: parseInt(status),
                                file_id : parseInt(fileId)
                            },
                            headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Updated!",
                                        text: "Report visibility was successfully updated!",
                                        type: "success",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.reload();
                                        }
                                    });
                                    //unBlockExt($('.content-wrapper'));
                                } else {
                                    $.each(response.errors, function (key, value) {
                                        toastr.error(value)
                                    });
                                    unBlockExt($('.content-wrapper'));
                                }
                            },
                            error: function (xhr, resp, text) {
                                console.log(xhr, resp, text);
                                toastr.error(text);
                                unBlockExt($('.content-wrapper'));
                            }
                        });
                    }
                });

            });


            $(document).ready(function () {

                $('#filterContractors').select2({
                    placeholder: "Search Contractor",
                    dropdownParent : $('#findContractorMap')
                });

                $('#customer_id').select2({
                    placeholder: "Customer",
                    dropdownParent: addItemBidModal
                });

                $('#expense_type').select2({
                    placeholder: "Expense Type",
                    dropdownParent: addItemExpenseModal
                });

                $('#customer_date_paid').pickadate(); // Date Picker
                $('#client_date_paid').pickadate(); // Date Picker

                /****** Bid Modal Show **************/
                addItemBidModal.on('show.bs.modal', function (e) {
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    let itemDesc = btn.data('item');
                    //console.log(id);
                    itemBidId.val(id);
                    itemBidDescModal.html(itemDesc);
                });

                /******* Expense Modal Show ********/
                addItemExpenseModal.on('show.bs.modal', function (e) {
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    let itemDesc = btn.data('item');
                    //console.log(itemDesc);
                    //console.log(id);
                    itemExpenseId.val(id);
                    itemExpenseDescModal.html(itemDesc)
                });

                /******** Authorize Modal Show ***********/
                authorizeContractorModal.on('show.bs.modal', function (e) {
                    $('#findContractorMap').modal('hide');
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    let contractorEmail = btn.data('email');
                    console.log(id);
                    authorizeContractorInput.val(id);
                    $('#contractorSendEmail').val(contractorEmail);
                });

                /****** Customer Paid Modal Show **/
                clientInvoicePaidModal.on('show.bs.modal', function (e) {
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    let amount = btn.data('amount');
                    clientInvoiceId.val(id);
                    $('#originalClientInvoice').html('$' + amount);
                });

                /****** Client Paid Modal Show **/
                customerInvoicePaidModal.on('show.bs.modal', function (e) {
                    let btn = $(e.relatedTarget);
                    let id = btn.data('id');
                    let amount = btn.data('amount');
                    customerInvoiceId.val(id);
                    $('#originalCustomerInvoice').html('$' + amount);
                })


            });

            // Update Assignment
            $('#updateAssignmentBtn').click(function(e){

                let assignmentUpdateData = $('#assignmentUpdateData');
                e.preventDefault();
                blockExt(assignmentUpdateData, $('#waitingMessage'));

                $.ajax({
                    url: "{{route('updateAssignment')}}",
                    type: "POST",
                    dataType: "json",
                    data: assignmentUpdateData.serialize(),
                    headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            //console.log(response)
                            Swal.fire({
                                title: "Good job!",
                                text: "Assignment updated!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                if (result.value) {
                                    console.log('Assignment Added. We will redirect to assignment');
                                    window.location.reload();
                                }
                                unBlockExt(assignmentUpdateData);
                            });
                        } else {
                            unBlockExt(assignmentUpdateData);
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        unBlockExt(assignmentUpdateData);
                    }
                });


            });

            // Delete Assignment Files

            // Delete Files

            $('.deleteAssFile').click(function(e){

                var deleteLink = $(this).attr('data-attr-link');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {

                        blockExt($('.addContainer'), $('#waitingMessage'));

                        $.ajax({
                            url: deleteLink,
                            type: "GET",
                            dataType: "json",
                            headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    unBlockExt($('.addContainer'));
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Assignment file was deleted!",
                                        type: "success",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    unBlockExt($('.addContainer'));
                                    $.each(response.errors, function (key, value) {
                                        toastr.error(value)
                                    });
                                }
                            },
                            error: function (xhr, resp, text) {
                                console.log(xhr, resp, text);
                                toastr.error(text);
                                unBlockExt($('.addContainer'));
                            }
                        });
                    }
                })

            });


            /*** Mark Invoice as Paid Client **/
            clientInvoicePaidBtn.click(function () {

                console.log('Button clicked');
                var action = $(this).attr('data-action');

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'invoice_id': clientInvoiceId.val(),
                        'paid_date': $('#client_date_paid').val(),
                        'amount': $('#client_amount_paid').val(),
                        'type': $('#client_type_paid').val(),
                        'memo': $('#client_memo_paid').val()
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
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                    }
                });

            });

            /*** Mark Invoice as Paid Customer **/
            customerInvoicePaidBtn.click(function () {

                console.log('Button clicked');
                var action = $(this).attr('data-action');

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'invoice_id': customerInvoiceId.val(),
                        'paid_date': $('#customer_date_paid').val(),
                        'amount': $('#customer_amount_paid').val(),
                        'type': $('#customer_type_paid').val(),
                        'memo': $('#customer_memo_paid').val()
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
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        //unBlockFMVContainer();
                    }
                });

            });

            /*** Generate Customer Invoice **/
            $('#generateCustomerInvoice').click(function () {

                console.log('Button clicked');
                var action = $(this).attr('data-action');

                let items = [];
                let customerId = $('#customer_invoice').val();
                let notes = $('#notes_invoice').val();

                $('input[name="invoice_itemIds[]"]:checked').each(function () {
                    items.push($(this).val());
                });
                console.log(items);

                if(items.length === 0 ){
                    Swal.fire({
                        title: "Missing Items",
                        text: "Please select items",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                    return false;
                }

                if( customerId === ''){
                    Swal.fire({
                        title: "Missing Customer",
                        text: "Please select customer",
                        type: "error",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    });
                    return false;
                }

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'customer_id': parseInt(customerId),
                        'items': items,
                        'notes' : notes
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Invoice was successfully generated!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        //unBlockFMVContainer();
                    }
                });


            });

            /*** Authorize Contractors **/
            $('#authorizeContractorBtn').click(function () {

                //console.log('Button clicked');
                var action = $(this).attr('data-action');

                let items = [];
                $('input[name="authorize_item[]"]:checked').each(function () {
                    items.push($(this).val());
                });
                console.log(items);

                blockExt($('#authorizeModal'), $('#waitingMessage'));

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'contractor_id': parseInt(authorizeContractorInput.val()),
                        'items': items,
                        'send_email': $('#contractorSendEmail').val(),
                        'type_of_pickup': $('#v_or_i').val(),
                        'special_instruction': $('#special_instructions').val(),
                        'additional_information': $('#additional_info').val(),
                        'terms': $('#terms').val(),
                        'method': $('#method').val(),
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Contractor is authorized added successfully!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                            unBlockExt($('#authorizeModal'));
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        unBlockExt($('#authorizeModal'));
                    }
                });


            });
            /******* Save Expense ********/
            $('#addItemExpense').click(function () {

                let form = $('#addItemExpenseAjax');
                let action = form.attr('action');

                blockExt($('#addItemExpenseModal'), $('#waitingMessage'));

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'item_id': parseInt(itemExpenseId.val()),
                        'client_id': parseInt($('#client_id').val()),
                        'amount': parseInt($('#expense_amount').val()),
                        'expense_type': $('#expense_type').val(),
                        'chargeable': $('#expense_chargeable').val(),
                        'comments': $('#expense_comment').val(),
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Expense was added successfully!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                form[0].reset();
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value);
                            });
                            unBlockExt($('#addItemExpenseModal'));
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        //unBlockFMVContainer();
                        unBlockExt($('#addItemExpenseModal'));
                    }
                });

            });

            /*** Send Contractor Authorization *******/
            $('.sendContractorAuthorization').click(function () {

                var sendContractorLink = $(this).attr('data-attr-link');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send Authorization to Contractor",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {

                        $.ajax({
                            url: sendContractorLink,
                            type: "GET",
                            dataType: "json",
                            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Sent!",
                                        text: "Authroization was sent!",
                                        type: "success",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    $.each(response.errors, function (key, value) {
                                        toastr.error(value)
                                    });
                                }
                            },
                            error: function (xhr, resp, text) {
                                console.log(xhr, resp, text);
                                toastr.error(text);
                                //unBlockFMVContainer();
                            }
                        });
                    }
                })

            });
            /*** Send Client Invoice *******/
            $('.sendClientInvoice').click(function () {

                var sendInvoiceLink = $(this).attr('data-attr-link');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send invoice to client",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {

                        $.ajax({
                            url: sendInvoiceLink,
                            type: "GET",
                            dataType: "json",
                            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Sent!",
                                        text: "Invoice was sent!",
                                        type: "success",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    $.each(response.errors, function (key, value) {
                                        toastr.error(value)
                                    });
                                }
                            },
                            error: function (xhr, resp, text) {
                                console.log(xhr, resp, text);
                                toastr.error(text);
                                //unBlockFMVContainer();
                            }
                        });
                    }
                })

            });

            /*** Send Customer Invoice *******/
            $('.sendCustomerInvoice').click(function () {

                var sendInvoiceLink = $(this).attr('data-attr-link');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to send invoice to customer",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!',
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-danger ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {

                        $.ajax({
                            url: sendInvoiceLink,
                            type: "GET",
                            dataType: "json",
                            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Sent!",
                                        text: "Invoice was sent!",
                                        type: "success",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    $.each(response.errors, function (key, value) {
                                        toastr.error(value)
                                    });
                                }
                            },
                            error: function (xhr, resp, text) {
                                console.log(xhr, resp, text);
                                toastr.error(text);
                                //unBlockFMVContainer();
                            }
                        });
                    }
                })

            });


            /****** Accept Bid *********/
            $('.accept_bid-item').click(function () {

                var bidDeleteUrl = $(this).attr('data-id');
                Swal.fire({
                    title: "Accept",
                    text: "This will enable to generate invoice",
                    type: "question",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-secondary ml-1',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        console.log(bidDeleteUrl);

                        $.ajax({
                            url: bidDeleteUrl,
                            type: "GET",
                            dataType: "json",
                            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                            success: function (response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Good job!",
                                        text: "Bid was accepted successfully!",
                                        type: "success",
                                        confirmButtonClass: 'btn btn-primary',
                                        buttonsStyling: false,
                                    }).then(function (result) {
                                        if (result.value) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    $.each(response.errors, function (key, value) {
                                        toastr.error(value)
                                    });
                                }
                            },
                            error: function (xhr, resp, text) {
                                console.log(xhr, resp, text);
                                toastr.error(text);
                                //unBlockFMVContainer();
                            }
                        });
                    } else {
                        console.log('Cancelled hit');
                    }
                });

            });

            /******* Save Bid ********/
            $('#addItemBid').click(function () {

                let form = $('#addItemBidAjax');
                let action = form.attr('action');

                blockExt(form, $('#waitingMessage'));

                $.ajax({
                    url: action,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'item_id': parseInt(itemBidId.val()),
                        'customer_id': parseInt($('#customer_id').val()),
                        'amount': parseInt($('#bid_amount').val()),
                        'bid_comments': $('#bid_comment').val(),
                        'bid_date':$('#bid_date').val(),
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Bid was added successfully!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                form[0].reset();
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                            unBlockExt(form);
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        unBlockExt(form);
                    }
                });

            });

            /****** Save Notes **/
            $('.saveCommunication').click(function () {

                var notes = '';
                var type = $(this).attr('data-type');
                if (type === 'public') {
                    notes = $('#public_notes').val();
                } else {
                    notes = $('#private_notes').val();
                }

                blockExt($('#chats'), $('#waitingMessage'));

                $.ajax({
                    url: "{{ route('saveCommunicationAssignment') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        'assignment_id': $('#assignment_id').val(),
                        'client_id': $('#client_id').val(),
                        'type': type,
                        'note': notes
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (response) {
                        if (response.status) {
                            Swal.fire({
                                title: "Good job!",
                                text: "Note added successfully!",
                                type: "success",
                                confirmButtonClass: 'btn btn-primary',
                                buttonsStyling: false,
                            }).then(function (result) {
                                if (result.value) {
                                    window.location.reload();
                                }
                            });
                        } else {
                            $.each(response.errors, function (key, value) {
                                toastr.error(value)
                            });
                            unBlockExt($('#chats'));
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                        unBlockExt($('#chats'));
                    }
                });


            });
            var markers = [];
            var center = new google.maps.LatLng(30.29461050801138, 15.360816686284807);
            var map = new google.maps.Map(document.getElementById('contractorMap'), {
                zoom: 3,
                center: center,
                minZoom: 3,
                maxZoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            var infoWindow = new google.maps.InfoWindow;

            function deleteMarkers() {

                if( markers.length > 0) {
                    for (var i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                    }
                    markers = [];
                }

            }


            // Function Load Contractors
             function loadContractorsNearBy( assignmentId, contractorIds, b){

                 deleteMarkers();

                 $.ajax({
                     url: "{{ route('findNearByContractors') }}",
                     type: "POST",
                     dataType: "json",
                     data: {
                         'assignment_id': assignmentId,
                         'contractor_ids' : contractorIds
                     },
                     headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                     success: function (response) {
                         if (response.status) {
                             console.log(response.data);
                             var bounds = new google.maps.LatLngBounds();
                             var mapData = response;

                             if (mapData.data.length >= 1) {
                                 for (var i = 0; i < mapData.data.length; i++) {

                                     if (mapData.data[i].address_code.latitude !== '' && mapData.data[i].address_code.longitude !== '') {
                                         //console.log(mapData.data[i].address_code.latitude);
                                         var contr_LatLng = new google.maps.LatLng(parseFloat(mapData.data[i].address_code.latitude), parseFloat(mapData.data[i].address_code.longitude));
                                         //if( mapData.data[i].type === 'A') {
                                         //bounds.extend(contr_LatLng);
                                         //}
                                         bounds.extend(contr_LatLng);
                                         contractorMarker(contr_LatLng, mapData.data[i].contractor_id, mapData.data[i].name, mapData.data[i].type, $('#assignment_id').val());
                                     }
                                 }

                             }

                             map.fitBounds(bounds);

                             unBlockExt(b);

                         } else {
                             $.each(response.errors, function (key, value) {
                                 toastr.error(value)
                             });
                             unBlockExt(b);
                         }
                     },
                     error: function (xhr, resp, text) {
                         console.log(xhr, resp, text);
                         toastr.error(text);
                         unBlockExt(b);
                     }
                 });

             }
            //Filter Click Function
            $('#filterContractorButton').click(function(){

                let contractorIds =  $('#filterContractors').val();
                let findContractorMapModal = $('#findContractorMap');
                console.log(contractorIds);
                blockExt(findContractorMapModal, $('#waitingMessage'));
                loadContractorsNearBy(  $('#assignment_id').val(), contractorIds, findContractorMapModal);

            });
            //Contractor Modal on show
            $('#findContractorMap').on('show.bs.modal', function (event) {

                let contractorIds =  $('#filterContractors').val();
                let findContractorMapModal = $('#findContractorMap');
                blockExt(findContractorMapModal, $('#waitingMessage'));
                loadContractorsNearBy(  $('#assignment_id').val(), contractorIds, findContractorMapModal);

            });

            function contractorMarker(latLng, contractorID, title, type, assignmentId) {

                var iconType = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';

                if (type === 'A') {
                    iconType = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
                }

                var html = '<div id="contractorMarkerContent"><p>Loading........</p><div>';

                var marker = new google.maps.Marker({
                    map: map,
                    position: latLng,
                    title: title,
                    contractorID: contractorID,
                    assignmentId: assignmentId,
                    icon: {
                        url: iconType
                    }
                });

                console.log(marker);

                google.maps.event.addListener(marker, 'click', function () {

                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                    map.setCenter(marker.getPosition());

                    $.ajax({
                        url: "{{ route('contractorMarker') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            contractor_id: marker.contractorID,
                            assignment_id: marker.assignmentId,
                        },
                        headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                        success: function (result) {
                            if (result.success) {
                                $('#contractorMarkerContent').html(result.html);
                            } else {
                                $.each(result.errors, function (key, value) {
                                    toastr.error('Marker Loading Failed ' + value);
                                });
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                        }
                    });

                });
                markers.push(marker);


            }

            new PerfectScrollbar("#private-chat", {wheelPropagation: false});
            new PerfectScrollbar("#public-chat", {wheelPropagation: false});
            Dropzone.autoDiscover = false;

            /******* Drop Zone ********************************************/

            $('#addFileFormModal').click(function () {
                myDropzone.processQueue(); // If files are there.
            });
            $('#assignmentFiles').dropzone({
                url: "{{route('addAssignmentFiles')}}",
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 100,
                maxFiles: 100,
                addRemoveLinks: true,
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                init: function () {
                    myDropzone = this;
                },
                sending: function (file, xhr, formData) {
                    //blockFMVContainer();
                    formData.append('assignment_id', $('#assignment_id').val());
                },
                success: function (file, response) {
                    if (response.status) {
                        //console.log(response)
                        //unBlockFMVContainer();
                        Swal.fire({
                            title: "Good job!",
                            text: "Files added!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                        });
                    } else {
                        $.each(response.errors, function (key, value) {
                            //console.log(value)
                            toastr.error(value);
                        });
                        myDropzone.removeFile(file);
                        //unBlockFMVContainer();
                    }
                }
            });

        </script>
    @endpush
@endsection
