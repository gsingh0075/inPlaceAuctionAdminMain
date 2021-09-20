@extends('layouts.masterHorizontal')

@section('title','List Expense- InPlace Auction')

@push('page-style')
<style>
    .table#getClientInvoiceDataTable td a{
        text-decoration: underline;
        color: #bdd1f8;
    }
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">All Expense Type</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">Expense Type
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
                                    <div class="col-12 py-2 text-right">
                                        <button type="button" class="btn btn-primary mr-1 mb-1"
                                                id="AddNewContractorBtn"
                                                data-toggle="modal"
                                                data-target="#itemAddExpenseModal">Add New Expense
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table dataTable" id="getExpenseListDataTable">
                                            <thead>
                                            <tr>
                                                <th>Expense Type</th>
                                                <th>Active/Inactive</th>
                                                <th>Update Status</th>
                                                <th>Edit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                              @if(isset($expense) && !empty($expense))
                                                  @foreach($expense as $e)
                                                      <tr>
                                                          <td>
                                                              {{ $e->name }}
                                                          </td>
                                                          <td>
                                                            @if($e->status === 1)
                                                                  <span class="text-success">Active</span>
                                                            @elseif($e->status === 0)
                                                                  <span class="text-danger">InActive</span>
                                                            @endif
                                                          </td>
                                                          <td>
                                                              @if($e->status === 1)
                                                                <a href="javascript:void(0)" data-id="{{ $e->id }}" data-name="{{ $e->name }}" data-status="0" class="text-danger updateStatus">Make it Inactive</a>
                                                              @elseif($e->status === 0)
                                                                  <a href="javascript:void(0)"  data-id="{{ $e->id }}" data-name="{{ $e->name }}" data-status="1" class="text-success updateStatus">Make it Active</a>
                                                              @endif
                                                          </td>
                                                          <td>
                                                              <a href="javascript:void(0)" class="editExpense" data-id="{{ $e->id }}" data-name="{{ $e->name }}">Edit</a>
                                                          </td>
                                                      </tr>
                                                  @endforeach
                                              @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Expense Type</th>
                                                <th>Active/Inactive</th>
                                                <th>Update Status</th>
                                                <th>Edit</th>
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

    <!-- Add New Category Modal -->
    <div class="modal fade text-left" id="itemAddExpenseModal" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="itemAddExpenseModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add New Expense Type</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-5 col-12">
                                            <label for="expense_name">Expense Name</label>
                                        </div>
                                        <div class="col-md-7 form-group required col-12">
                                                <input type="text" name="expense_name" id="expense_name" class="form-control">
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
                    <button type="button" class="btn btn-light-secondary" id="addExpenseBtn">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Add Expense</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Category Modal -->


    <!-- Edit Category Modal -->
    <div class="modal fade text-left" id="itemEditExpenseModal" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="itemEditExpenseModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Edit Expense</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mt-2">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <input type="hidden" name="category_id_edit" id="expense_edit_id" value="">
                                        <div class="col-md-5 col-12">
                                            <label for="category_name_edit">Expense Name</label>
                                        </div>
                                        <div class="col-md-7 form-group required col-12">
                                            <input type="text" name="expense_name_edit" id="expense_name_edit" class="form-control">
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
                    <button type="button" class="btn btn-light-secondary" id="updateExpenseBtn">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Update Expense</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Category Modal -->

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
<script type="text/javascript">

    //Expense Modal Add
    var addExpenseBtn = $('#addExpenseBtn');
    var itemAddExpenseModal = $('#itemAddExpenseModal');

    //Expense Edit Modal
    var editExpense = $('.editExpense');
    var itemEditExpenseModal = $('#itemEditExpenseModal');
    var expenseEditId = $('#expense_edit_id');
    var expenseNameEdit = $('#expense_name_edit');
    var updateExpenseBtn = $('#updateExpenseBtn');

    // Update Expense Status
    var updateStatus = $('.updateStatus');

    // Datatables
    var getExpenseListDataTable = $('#getExpenseListDataTable');

    $(document).ready(function(){

        getExpenseListDataTable.dataTable({
                "pageLength": 100
        });

        // Update Status
        updateStatus.click(function(){

            let cId = $(this).attr('data-id');
            let cName = $(this).attr('data-name');
            let cStatus = $(this).attr('data-status');

            if(cId === ''){
                toastr.error('Expense ID Empty');
                return false;
            }
            if(cName === ''){
                toastr.error('Expense Name Empty');
                return false;
            }
            if(cStatus === ''){
                toastr.error('Expense Status');
                return false;
            }

            console.log(cId);
            console.log(cName);
            console.log(cStatus);

            blockExt(getExpenseListDataTable, $('#waitingMessage'));

            $.ajax({
                url: "{{route('updateExpense')}}",
                type: "POST",
                dataType: "json",
                data:{
                    expense_name : cName,
                    expense_id : cId,
                    status : cStatus
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        //console.log(response)
                        Swal.fire({
                            title: "Good job!",
                            text: "Expense updated successfully!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                            unBlockExt(getExpenseListDataTable);
                        });
                    } else {
                        unBlockExt(getExpenseListDataTable);
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt(getExpenseListDataTable);
                }
            });



        });

        //Edit Click
        editExpense.click(function(){

            let cId = $(this).attr('data-id');
            let cName = $(this).attr('data-name');

            if(cId === ''){
                toastr.error('Expense ID Empty');
                return false;
            }
            if(cName === ''){
                toastr.error('Expense Name Empty');
                return false;
            }

            expenseEditId.val(cId);
            expenseNameEdit.val(cName);
            itemEditExpenseModal.modal('show');

        });

        // Update Expense Click
        updateExpenseBtn.click(function(e){

            e.preventDefault();
            let expense_name = expenseNameEdit.val();
            let expense_id = expenseEditId.val();
            blockExt(itemEditExpenseModal, $('#waitingMessage'));

            $.ajax({
                url: "{{route('updateExpense')}}",
                type: "POST",
                dataType: "json",
                data:{
                    expense_name : expense_name,
                    expense_id : expense_id
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        //console.log(response)
                        Swal.fire({
                            title: "Good job!",
                            text: "Expense updated successfully!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                            unBlockExt(itemEditExpenseModal);
                        });
                    } else {
                        unBlockExt(itemEditExpenseModal);
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt(itemEditExpenseModal);
                }
            });



        });
        // Add New Category
        addExpenseBtn.click(function(e){

            e.preventDefault();
            let expense_name = $('#expense_name').val();
            blockExt(itemAddExpenseModal, $('#waitingMessage'));

            $.ajax({
                url: "{{route('addNewExpense')}}",
                type: "POST",
                dataType: "json",
                data:{
                    expense_name : expense_name
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        //console.log(response)
                        Swal.fire({
                            title: "Good job!",
                            text: "Expense Type added successfully!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                            unBlockExt(itemAddExpenseModal);
                        });
                    } else {
                        unBlockExt(itemAddExpenseModal);
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt(itemAddExpenseModal);
                }
            });


        });
    });
</script>
@endpush
@endsection
