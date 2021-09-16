@extends('clientDashboard.layouts.masterHorizontal')

@section('title','List Assignment - InPlace Auction')

@push('page-style')
<style>
    /*.table-responsive{
        overflow-x: hidden;
    }*/
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Assignments</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Assignments
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
                                <h4 class="card-title">Assignments</h4>
                            </div>-->
                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table" id="getAssignmentDataTable">
                                            <thead>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Lease</th>
                                                <th>State</th>
                                                <th>Items</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Approved</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                 @if(isset($assignment) && !empty($assignment))
                                                        @foreach( $assignment as $as )
                                                              <tr>
                                                                  <td>
                                                                       IPA # {{ $as->assignment_id }}
                                                                  </td>
                                                                  <td>
                                                                      <a href="{{ route('showAssignmentClient', $as->assignment_id) }}">{{ $as->lease_nmbr }}</a>
                                                                      <br> <br>
                                                                      {{ $as->ls_company }} {{ $as->ls_full_name }} <br>
                                                                      {{ $as->ls_address1 }} {{ $as->ls_city }} {{ $as->ls_state }} {{ $as->ls_zip }}
                                                                  </td>
                                                                  <td>
                                                                      @if(!empty($as->ls_state)){{ $as->ls_state }} @else - @endif
                                                                  </td>
                                                                  <td>
                                                                      @if(isset($as->items) && !empty($as->items))
                                                                          @foreach( $as->items as $item )
                                                                              @if($item->SOLD_FLAG === 1) <span class="text-info"> !! SOLD !!</span>@endif
                                                                                  #{{ $item->ITEM_NMBR }} {{ $item->ITEM_MAKE }} {{ $item->ITEM_MODEL }} {{ $item->ITEM_SERIAL }} <br>
                                                                          @endforeach
                                                                      @endif
                                                                  </td>
                                                                  <td>
                                                                       {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $as->dt_stmp)->format('j F, Y') }}
                                                                  </td>
                                                                  <td>
                                                                      @if( $as->isopen === 1 )  <span class="text-success"> OPEN </span> @else <span class="text-danger"> CLOSE </span>@endif
                                                                  </td>
                                                                  <td>
                                                                      <span class="text-info"> @if($as->approved === 1) APPROVED @else NOT APPROVED @endif </span>
                                                                  </td>
                                                              </tr>
                                                        @endforeach
                                                 @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>#ID</th>
                                                <th>Lease</th>
                                                <th>State</th>
                                                <th>Items</th>
                                                <th>Date</th>
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

    $(document).ready(function() {

        $('#getAssignmentDataTable').DataTable( {
            dom: 'Blfrtip',
            order : [[ 0, "desc" ]],
            pageLength : 100,
            buttons: [
                /*{
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },*/
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ]
        });

    });
</script>
@endpush
@endsection
