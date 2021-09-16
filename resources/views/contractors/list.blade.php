@extends('layouts.masterHorizontal')

@section('title','List Contractors - InPlace Auction')

@push('page-style')
<style>
  #getContractorsDataTable a{
      text-decoration: underline;
  }
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Contractors</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Contractors
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
                            <!--<div class="card-header">
                                <h4 class="card-title">FMV</h4>
                            </div>-->
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table dataTable" id="getContractorsDataTable">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Address</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Zip</th>
                                                <th>email</th>
                                                <th>Invoice Notification</th>
                                                <th>Status</th>
                                                <th>Approved</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                              @if(isset($contractors) && !empty($contractors))
                                                  @foreach( $contractors as $c)
                                                      <tr>
                                                          <td><a href="{{ route('editContractor', $c->contractor_id) }}">{{ $c->first_name }} {{ $c->last_name }}</a></td>
                                                          <td>{{ $c->company }}</td>
                                                          <td>{{ $c->address1 }}</td>
                                                          <td>{{ $c->city }}</td>
                                                          <td>{{ $c->state }}</td>
                                                          <td>{{ $c->zip }}</td>
                                                          <td>{{ $c->email }}</td>
                                                          <td>
                                                              <a class="nav-link nav-link-label emailInvoiceToggle" @if($c->invoice_email === 0) data-email="1" @elseif($c->invoice_email === 1) data-email="0" @endif data-contractorId="{{ $c->contractor_id }}" href="javascript:void(0)" style="text-decoration: none">
                                                                  <span class="user-name">Off</span>
                                                                  @if($c->invoice_email === 0)
                                                                      <i class="bx bxs-toggle-left text-danger" style="font-size: 20px;"></i>
                                                                  @elseif($c->invoice_email === 1)
                                                                      <i class="bx bx-toggle-right text-success" style="font-size: 20px;"></i>
                                                                  @endif
                                                                  <span class="user-name">On</span>
                                                              </a>
                                                          </td>
                                                          <td>
                                                              @if($c->active === 1)
                                                               <span class="text-success">Active</span>
                                                              @else
                                                                <span class="text-danger">InActive</span>
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if($c->approved ===1)
                                                                <span class="text-success">Approved</span>
                                                              @else
                                                                <span class="text-danger">Not Approved</span>
                                                              @endif
                                                          </td>
                                                      </tr>
                                                  @endforeach
                                              @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Address</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Zip</th>
                                                <th>email</th>
                                                <th>Invoice Notification</th>
                                                <th>Status</th>
                                                <th>Approved</th>
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

    var body = $('body');

    $(document).ready(function() {

        //Get Contractors list.
        $('#getContractorsDataTable').DataTable( {
            pageLength : 20,
            order : [],
            buttons: [
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

        // Update Notification Click function
        body.on('click', '.emailInvoiceToggle', function(){

            var contractorId = $(this).attr('data-contractorId');
            var typeNotification = $(this).attr('data-email');

            blockExt($('#getContractorsDataTable'), $('#waitingMessage'));

            $.ajax({
                url: "{{ route('updateContractorInvoiceNotificationAjax') }}",
                type: "POST",
                dataType: "json",
                data: {
                    'contractor_id': contractorId,
                    'notification': typeNotification,
                },
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            title: "Good job!",
                            text: "Contractor updated successfully!",
                            type: "success",
                            confirmButtonClass: 'btn btn-primary',
                            buttonsStyling: false,
                        }).then(function (result) {
                            if (result.value) {
                                window.location.reload();
                            }
                        });
                    } else {
                        $.each(response.errors, function (key, value) {-90
                            toastr.error(value)
                        });
                        unBlockExt($('#getContractorsDataTable'));
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt($('#getContractorsDataTable'));
                }
            });

        });


    });

</script>
@endpush
@endsection
