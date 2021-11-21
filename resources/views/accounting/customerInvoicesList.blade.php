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
                        <h5 class="content-header-title float-left pr-1 mb-0">Customer Invoices</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Customer Invoices
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
                                                <th>Sent Date</th>
                                                <th>Status</th>
                                                <th>Assignment</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if( (!empty($customerInvoices)) && (count($customerInvoices) > 0))
                                                @foreach( $customerInvoices as $customerInvoice )
                                                    <tr>
                                                        @php $assignmentId = '';@endphp
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif><a href="{{ route('viewCustomerInvoice', $customerInvoice->invoice_auth_id ) }}">{{ $customerInvoice->invoice_number }}</a></td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>${{ round($customerInvoice->invoice_amount,2) }}</td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>{{ $customerInvoice->customer->COMPANY }} </td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>{{ count($customerInvoice->items) }}</td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>
                                                            @if(!empty($customerInvoice->items))
                                                                @foreach( $customerInvoice->items as $i)
                                                                    @if(!empty($i->item))
                                                                        @php $assignmentId = $i->item->ASSIGNMENT_ID; @endphp
                                                                        {{ $i->item['ITEM_MAKE'] }} {{ $i->item['ITEM_MODEL'] }} {{ $i->item['ITEM_YEAR'] }} ( <b>{{ $i->item['ITEM_SERIAL'] }}</b> ) <br>
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif><a href="{{ route('viewCustomerInvoice', $customerInvoice->invoice_auth_id ) }}">View</a></td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->create_dt)->format('j F, Y') }} </td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>
                                                          @if(!empty($customerInvoice->sent_date))
                                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->sent_date)->format('j F, Y') }}
                                                          @else
                                                                <span class="text-info">!!!! NOT SENT YET !!!!</span>
                                                          @endif

                                                        </td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>
                                                            @if( $customerInvoice->paid === 1)
                                                                <span class="text-success">PAID</span> <br>
                                                                <b>Paid On:</b> {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $customerInvoice->paid_dt)->format('j F, Y')}} <br>
                                                                <b>Paid Amount:</b> ${{ round($customerInvoice->paid_amount,2) }} <br>
                                                                <b>Reference:</b> {{ $customerInvoice->check_num }}
                                                            @else
                                                                <span class="text-info">!!! NOT PAID !!!</span>
                                                            @endif
                                                        </td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>
                                                            <a target="_blank" href="{{ route('showAssignment',$assignmentId) }}">{{ $assignmentId }}</a>
                                                        </td>
                                                        <td @if(empty($customerInvoice->sent_date)) class="inWeekOld" @endif>
                                                            <a href="javascript:void(0)" data-action="{{ route('deleteCustomerInvoice') }}" class="deleteCustomerInvoice" data-id="{{$customerInvoice->invoice_auth_id }}">Delete</a>
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
                                                <th>Sent Date</th>
                                                <th>Status</th>
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


    $(document).ready(function() {

        var deleteCustomerInvoice = $('.deleteCustomerInvoice');

        $('#getCustomerInvoiceDataTable').DataTable({
            pageLength : 50,
            order :[["0","desc"]]
         });

        deleteCustomerInvoice.click(function(){

            var invoiceId = $(this).attr('data-id');
            var action = $(this).attr('data-action');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                //console.log(result);
                if (result.value) {

                    $.ajax({
                        url: action,
                        type: "POST",
                        dataType: "json",
                        data: {
                            'invoice_id': invoiceId,
                        },
                        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                unBlockExt($('.addContainer'));
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Invoice was deleted!",
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
                }
            })

        });
    });
</script>
@endpush
@endsection
