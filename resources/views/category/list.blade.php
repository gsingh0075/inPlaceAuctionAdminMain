@extends('layouts.masterHorizontal')

@section('title','List Categories- InPlace Auction')

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
                        <h5 class="content-header-title float-left pr-1 mb-0">All Categories</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">Categories
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
                                                data-target="#itemAddCategoryModal">Add New Category
                                        </button>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table dataTable" id="getCategoryListDataTable">
                                            <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th>Number of Items</th>
                                                <th>Active/Inactive</th>
                                                <th>Update Status</th>
                                                <th>Edit</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                              @if(isset($categories) && !empty($categories))
                                                  @foreach($categories as $c)
                                                      <tr>
                                                          <td>
                                                              {{ $c->category_name }}
                                                          </td>
                                                          <td>
                                                              {{ count($c->items) }}
                                                          </td>
                                                          <td>
                                                            @if($c->active === 1)
                                                                  <span class="text-success">Active</span>
                                                            @elseif($c->active === 0)
                                                                  <span class="text-danger">InActive</span>
                                                            @endif
                                                          </td>
                                                          <td>
                                                              @if($c->active === 1)
                                                                <a href="javascript:void(0)" data-id="{{ $c->category_id }}" data-name="{{ $c->category_name }}" data-status="0" class="text-danger updateStatus">Make it Inactive</a>
                                                              @elseif($c->active === 0)
                                                                  <a href="javascript:void(0)"  data-id="{{ $c->category_id }}" data-name="{{ $c->category_name }}" data-status="1" class="text-success updateStatus">Make it Active</a>
                                                              @endif
                                                          </td>
                                                          <td>
                                                              <a href="javascript:void(0)" class="editCategory" data-id="{{ $c->category_id }}" data-name="{{ $c->category_name }}">Edit</a>
                                                          </td>
                                                      </tr>
                                                  @endforeach
                                              @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Category</th>
                                                <th>Number of Items</th>
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
    <div class="modal fade text-left" id="itemAddCategoryModal" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="itemAddCategoryModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">Add New Category</h4>
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
                                            <label for="category_name">Category Name</label>
                                        </div>
                                        <div class="col-md-7 form-group required col-12">
                                                <input type="text" name="category_name" id="category_name" class="form-control">
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
                    <button type="button" class="btn btn-light-secondary" id="addCategoryBtn">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Add Category</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add New Category Modal -->


    <!-- Edit Category Modal -->
    <div class="modal fade text-left" id="itemEditCategoryModal" data-backdrop="static" data-keyboard="false"
         tabindex="-1" role="dialog" aria-labelledby="itemEditCategoryModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">EditCategory</h4>
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
                                        <input type="hidden" name="category_id_edit" id="category_edit_id" value="">
                                        <div class="col-md-5 col-12">
                                            <label for="category_name_edit">Category Name</label>
                                        </div>
                                        <div class="col-md-7 form-group required col-12">
                                            <input type="text" name="category_name_edit" id="category_name_edit" class="form-control">
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
                    <button type="button" class="btn btn-light-secondary" id="updateCategoryBtn">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Update Category</span>
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
    var AddNewContractorBtn = $('#AddNewContractorBtn');
    var addCategoryBtn = $('#addCategoryBtn');
    var itemAddCategoryModal = $('#itemAddCategoryModal');
    var editCategory = $('.editCategory');
    var itemEditCategoryModal = $('#itemEditCategoryModal');
    var categoryEditId = $('#category_edit_id');
    var categoryNameEdit = $('#category_name_edit');
    var updateCategoryBtn = $('#updateCategoryBtn');
    var updateStatus = $('.updateStatus');
    var getCategoryListDataTable = $('#getCategoryListDataTable');


    $(document).ready(function(){

        getCategoryListDataTable.dataTable({
                "pageLength": 100
        });

        // Update Status
        updateStatus.click(function(){

            let cId = $(this).attr('data-id');
            let cName = $(this).attr('data-name');
            let cStatus = $(this).attr('data-status');

            if(cId === ''){
                toastr.error('Category ID Empty');
                return false;
            }
            if(cName === ''){
                toastr.error('Category Name Empty');
                return false;
            }
            if(cStatus === ''){
                toastr.error('Empty Status');
                return false;
            }

            console.log(cId);
            console.log(cName);
            console.log(cStatus);

            blockExt(getCategoryListDataTable, $('#waitingMessage'));

            $.ajax({
                url: "{{route('updateCategory')}}",
                type: "POST",
                dataType: "json",
                data:{
                    category_name : cName,
                    category_id : cId,
                    status : cStatus
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        //console.log(response)
                        Swal.fire({
                            title: "Good job!",
                            text: "Category updated successfully!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                            unBlockExt(getCategoryListDataTable);
                        });
                    } else {
                        unBlockExt(getCategoryListDataTable);
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt(getCategoryListDataTable);
                }
            });



        });

        //Edit Click
        editCategory.click(function(){

            let cId = $(this).attr('data-id');
            let cName = $(this).attr('data-name');

            if(cId === ''){
                toastr.error('Category ID Empty');
                return false;
            }
            if(cName === ''){
                toastr.error('Category Name Empty');
                return false;
            }

            categoryEditId.val(cId);
            categoryNameEdit.val(cName);
            itemEditCategoryModal.modal('show');

        });

        // Update Category Click
        updateCategoryBtn.click(function(e){

            e.preventDefault();
            let category_name = categoryNameEdit.val();
            let category_id = categoryEditId.val();
            blockExt(itemEditCategoryModal, $('#waitingMessage'));

            $.ajax({
                url: "{{route('updateCategory')}}",
                type: "POST",
                dataType: "json",
                data:{
                    category_name : category_name,
                    category_id : category_id
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        //console.log(response)
                        Swal.fire({
                            title: "Good job!",
                            text: "Category updated successfully!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                            unBlockExt(itemEditCategoryModal);
                        });
                    } else {
                        unBlockExt(itemEditCategoryModal);
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt(itemEditCategoryModal);
                }
            });



        });
        // Add New Category
        addCategoryBtn.click(function(e){

            e.preventDefault();
            let category_name = $('#category_name').val();
            blockExt(itemAddCategoryModal, $('#waitingMessage'));

            $.ajax({
                url: "{{route('addNewCategory')}}",
                type: "POST",
                dataType: "json",
                data:{
                    category_name : category_name
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        //console.log(response)
                        Swal.fire({
                            title: "Good job!",
                            text: "Category added successfully!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                            unBlockExt(itemAddCategoryModal);
                        });
                    } else {
                        unBlockExt(itemAddCategoryModal);
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt(itemAddCategoryModal);
                }
            });


        });
    });
</script>
@endpush
@endsection
