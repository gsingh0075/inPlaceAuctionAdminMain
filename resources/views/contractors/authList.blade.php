@extends('layouts.masterHorizontal')

@section('title','List Contractors - InPlace Auction')

@push('page-style')
<style>
    /*.table-responsive{
        overflow-x: hidden;
    }*/
    #getContractorsAuthDataTable a{
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
                        <h5 class="content-header-title float-left pr-1 mb-0">Contractor Authorization List</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Authorization List
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
                                        <table class="table dataTable" id="getContractorsAuthDataTable">
                                            <thead>
                                            <tr>
                                                <th>Auth Id</th>
                                                <th>Created Date</th>
                                                <th>Contractor</th>
                                                <th>Total Items</th>
                                                <th>View</th>
                                                <th>Sent Date</th>
                                                <th>Action</th>
                                                <th>Assignment</th>
                                                <th>Del</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                              @if(isset($contractorAuth) && !empty($contractorAuth))
                                                  @foreach( $contractorAuth as $c)
                                                      <tr>
                                                          <td>{{ $c->contractor_auth_id }} </td>
                                                          <td>
                                                              @if(!empty( $c->create_dt))
                                                                  {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $c->create_dt)->format('j F, Y')}}
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if(isset($c->contractor) && !empty($c->contractor))
                                                                  {{  $c->contractor->first_name }} {{ $c->contractor->last_name }}
                                                               @endif
                                                          </td>
                                                          <td>
                                                             {{ count($c->authItems) }}
                                                          </td>
                                                          <td>
                                                              @php $assigment_id = '';  @endphp
                                                              @if(isset($c->authItems) && !empty($c->authItems))
                                                                  @php $itemExtract = $c->authItems[0]->item; @endphp
                                                                  @if(!empty($itemExtract))
                                                                      @php $assigment_id = $itemExtract->ASSIGNMENT_ID; @endphp
                                                                  @endif
                                                              @endif
                                                              <a href="{{ route('viewContractorAuthorization', [$c->contractor_auth_id, $assigment_id] ) }}">View</a>
                                                          </td>
                                                          <td>
                                                              @if(!empty( $c->sent_date))
                                                                  {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $c->sent_date)->format('j F, Y')}}
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if($c->email_sent === 1)
                                                                  <a href="javascript:void(0)"
                                                                     class="sendContractorAuthorization"
                                                                     data-attr-link="{{ route('sendContractorAuthorization', [$c->contractor_auth_id, $assigment_id] ) }}">ReSend</a>
                                                              @else
                                                                  <a href="javascript:void(0)"
                                                                     class="sendContractorAuthorization"
                                                                     data-attr-link="{{ route('sendContractorAuthorization', [$c->contractor_auth_id, $assigment_id] ) }}">Send</a>
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if(!empty($assigment_id))
                                                              <a target="_blank" href="{{ route("showAssignment",$assigment_id) }}">View Assignment</a>
                                                              @endif
                                                          </td>
                                                          <td>
                                                              <a href="#">Delete</a>
                                                          </td>
                                                      </tr>
                                                  @endforeach
                                              @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Auth Id</th>
                                                <th>Created Date</th>
                                                <th>Contractor</th>
                                                <th>Total Items</th>
                                                <th>View</th>
                                                <th>Sent Date</th>
                                                <th>Action</th>
                                                <th>Assignment</th>
                                                <th>Del</th>
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

        //Get Contractors list.
        $('#getContractorsAuthDataTable').DataTable( {
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

        // Send Contractor Authorization
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

                    blockExt($('#getContractorsAuthDataTable'),$('#waitingMessage'));

                    $.ajax({
                        url: sendContractorLink,
                        type: "GET",
                        dataType: "json",
                        headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    title: "Sent!",
                                    text: "Authorization was sent!",
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
                                unBlockExt($('#getContractorsAuthDataTable'));
                            }
                        },
                        error: function (xhr, resp, text) {
                            console.log(xhr, resp, text);
                            toastr.error(text);
                            unBlockExt($('#getContractorsAuthDataTable'));
                        }
                    });
                }
            })

        });


    });

</script>
@endpush
@endsection
