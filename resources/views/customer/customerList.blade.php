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
                        <h5 class="content-header-title float-left pr-1 mb-0">Customer List</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Customers
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
                                        <table class="table" id="getCustomerListDataTable">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Address</th>
                                                <th>Email</th>
                                                <th>Invoice Notification</th>
                                                <th>Phone</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                              @if(isset($customer) && !empty($customer))
                                                  @foreach($customer as $c)
                                                      <tr>
                                                          <td>
                                                              <a href="{{ route('showCustomer', $c->CUSTOMER_ID) }}"> {{ $c->FIRSTNAME }} {{ $c->LASTNAME }}</a>
                                                          </td>
                                                          <td>
                                                             {{ $c->COMPANY }}
                                                          </td>
                                                          <td>
                                                              {{ $c->ADDRESS1 }} {{ $c->CITY }} {{ $c->STATE }}
                                                          </td>
                                                          <td>
                                                             <a href="mailto:{{ $c->EMAIL }}">{{ $c->EMAIL }}</a>
                                                          </td>
                                                          <td>
                                                              <a class="nav-link nav-link-label emailInvoiceToggle" @if($c->invoice_email === 0) data-email="1" @elseif($c->invoice_email === 1) data-email="0" @endif data-customerId="{{ $c->CUSTOMER_ID }}" href="javascript:void(0)" style="text-decoration: none">
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
                                                              {{ $c->PHONE }}
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
                                                <th>Email</th>
                                                <th>Invoice Notification</th>
                                                <th>Phone</th>
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
<script type="text/javascript">

    var body = $('body');

    $(document).ready(function(){

        $('#getCustomerListDataTable').dataTable({
                "pageLength": 100,
                "order": [["0","asc"]]
        });

        // Update Notification Click function
        body.on('click', '.emailInvoiceToggle', function(){

            console.log('clicked');

            var customerId = $(this).attr('data-customerId');
            var typeNotification = $(this).attr('data-email');

            blockExt($('#getCustomerListDataTable'), $('#waitingMessage'));

            $.ajax({
                url: "{{ route('updateCustomerInvoiceNotificationAjax') }}",
                type: "POST",
                dataType: "json",
                data: {
                    'customer_id': customerId,
                    'notification': typeNotification,
                },
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            title: "Good job!",
                            text: "Customer updated successfully!",
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
                        unBlockExt($('#getCustomerListDataTable'));
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt($('#getCustomerListDataTable'));
                }
            });

        });
    });
</script>
@endpush
@endsection
