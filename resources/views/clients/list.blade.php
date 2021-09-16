@extends('layouts.masterHorizontal')

@section('title','List Clients - InPlace Auction')

@push('page-style')
<style>
    /*.table-responsive{
        overflow-x: hidden;
    }*/
    #getClientsDataTable a{
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
                        <h5 class="content-header-title float-left pr-1 mb-0">Clients</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Clients
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
                            <div class="card-header">
                                <h4 class="card-title">Clients</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table dataTable" id="getClientsDataTable">
                                            <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Address</th>
                                                <th>Email</th>
                                                <th>Invoice Notification</th>
                                                <th>Contact Info</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($clients) && !empty($clients))
                                                    @foreach($clients as $client)
                                                        <tr>
                                                            <td><a href="{{ route('showClient', $client->CLIENT_ID) }}"> {{ $client->FIRSTNAME }} {{ $client->LASTNAME }}</a></td>
                                                            <td>{{ $client->COMPANY }}</td>
                                                            <td>{{ $client->ADDRESS1 }} {{ $client->CITY }} {{ $client->STATE }} {{ $client->ZIP }}</td>
                                                            <td><a href="mailto:{{ $client->EMAIL }}">{{ $client->EMAIL }}</a></td>
                                                            <td>
                                                                <a class="nav-link nav-link-label emailInvoiceToggle" @if($client->invoice_email === 0) data-email="1" @elseif($client->invoice_email === 1) data-email="0" @endif data-clientId="{{ $client->CLIENT_ID }}" href="javascript:void(0)" style="text-decoration: none">
                                                                    <span class="user-name">Off</span>
                                                                    @if($client->invoice_email === 0)
                                                                        <i class="bx bxs-toggle-left text-danger" style="font-size: 20px;"></i>
                                                                    @elseif($client->invoice_email === 1)
                                                                        <i class="bx bx-toggle-right text-success" style="font-size: 20px;"></i>
                                                                    @endif
                                                                    <span class="user-name">On</span>
                                                                </a>
                                                            </td>
                                                            <td>{{ $client->PHONE }}</td>
                                                            <td>
                                                                @if(!empty( $client->DT_STMP))
                                                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->DT_STMP)->format('j F, Y')}}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($client->STATUS === 1)
                                                                    <p class="text-success">Approved</p>
                                                                @else
                                                                    <p class="text-danger">Not Approved</p>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('loginAsClient', $client->CLIENT_ID) }}">Login</a>
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
                                                <th>Contact Info</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
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

    var body = $('body');

    $(document).ready(function() {

        $('#getClientsDataTable').DataTable( {
            pageLength: 100,
            /*buttons: [
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
            ]*/
        });

       // Update Notification Click function
        body.on('click', '.emailInvoiceToggle', function(){

            var clientId = $(this).attr('data-clientId');
            var typeNotification = $(this).attr('data-email');

            blockExt($('#getClientsDataTable'), $('#waitingMessage'));

            $.ajax({
                url: "{{ route('updateInvoiceNotificationAjax') }}",
                type: "POST",
                dataType: "json",
                data: {
                    'client_id': clientId,
                    'notification': typeNotification,
                },
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        Swal.fire({
                            title: "Good job!",
                            text: "Client updated successfully!",
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
                        unBlockExt($('#getClientsDataTable'));
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt($('#getClientsDataTable'));
                }
            });

        });

    });

</script>
@endpush
@endsection
