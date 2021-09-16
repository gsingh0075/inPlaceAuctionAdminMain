@extends('clientDashboard.layouts.masterHorizontal')

@section('title','List Client Invoices - InPlace Auction')

@push('page-style')
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Items</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">Items
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
                                        <table class="table dataTable" id="getItemListDataTable">
                                            <thead>
                                            <tr>
                                                <th>Lease#</th>
                                                <th>Assign#</th>
                                                <th>Lease Company</th>
                                                <th>Serial#</th>
                                                <th>Info</th>
                                                <th>Recovered Date</th>
                                                <th>Status</th>
                                                <th>FMV <br> Asking Price</th>
                                                <th>Sale Price</th>
                                                <th>Location</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($items) && !empty($items))
                                                @foreach($items as $item)
                                                    <tr>
                                                        <td>
                                                            @if(isset($item->assignment))
                                                                {{ $item->assignment->lease_nmbr }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($item->assignment))
                                                               {{ $item->assignment->assignment_id }} - {{ $item->ITEM_NMBR }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($item->assignment))
                                                                {{ $item->assignment->ls_company }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                          {{ $item->ITEM_SERIAL }}
                                                        </td>
                                                        <td>
                                                            {{ $item->ITEM_YEAR }} <br> {{ $item->ITEM_MAKE }} <br>{{ $item->ITEM_MODEL }}
                                                        </td>
                                                        <td>
                                                            @if(!empty($item->ITEM_RECOVERY_DT))
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->ITEM_RECOVERY_DT)->format('j F, Y')}}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($item->SOLD_FLAG === 1)
                                                                <span class="text-success">SOLD</span>
                                                            @elseif($item->HOLD_FLAG === 1)
                                                                <span class="text-info">HOLD</span>
                                                            @else
                                                                <span class="text-danger">AVAILABLE</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="text-info">${{ round($item->FMV,2) }}</span><br>
                                                            <span class="text-danger"> ${{ round($item->ASKING_PRICE,2) }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-success">${{ round($item->SALE_PRICE,2) }}</span>
                                                        </td>
                                                        <td>
                                                            {{ $item->LOC_CITY }},{{ $item->LOC_STATE }}
                                                        </td>
                                                        <td>
                                                            @if(!empty($item->ASSIGNMENT_ID))
                                                             <a href="{{ route('showAssignmentClient', $item->ASSIGNMENT_ID) }}">Assignment</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Lease#</th>
                                                <th>Assign#</th>
                                                <th>Lease Company</th>
                                                <th>Serial#</th>
                                                <th>Info</th>
                                                <th>Recovered Date</th>
                                                <th>Status</th>
                                                <th>FMV <br> Asking Price</th>
                                                <th>Sale Price</th>
                                                <th>Location</th>
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
        <script type="text/javascript">
            $(document).ready(function(){
                $('#getItemListDataTable').dataTable({
                    pageLength: 20,
                    order : [["3","desc"]],
                });
            });
        </script>
    @endpush
@endsection
