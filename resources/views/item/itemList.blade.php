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
                        <h5 class="content-header-title float-left pr-1 mb-0">Items</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Items
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
                                                <th>Client Company</th>
                                                <th>Lease Company</th>
                                                <th>Lease#</th>
                                                <th>Assign#</th>
                                                <th>Assignment Date</th>
                                                <th>Recovered Date</th>
                                                <th>Status</th>
                                                <th>Serial#</th>
                                                <th>Item#</th>
                                                <th>Qty</th>
                                                <th>Info</th>
                                                <th>FMV <br> Asking Price</th>
                                                <th>Max Bids</th>
                                                <th>Sale Price</th>
                                                <th>City</th>
                                                <th>State</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @if(isset($items) && !empty($items))
                                                @foreach($items as $item)
                                                    <tr>
                                                        <td>
                                                            @if(isset($item->assignment))
                                                                @if(isset($item->assignment->client) & !empty($item->assignment->client))
                                                                    {{ $item->assignment->client->clientInfo->COMPANY }}
                                                                @endif
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($item->assignment))
                                                                {{ $item->assignment->ls_company }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($item->assignment))
                                                                {{ $item->assignment->lease_nmbr }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($item->assignment))
                                                               {{ $item->assignment->assignment_id }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(isset($item->assignment))
                                                              @if(!empty($item->assignment->dt_stmp))
                                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->assignment->dt_stmp)->format('j F, Y') }}
                                                              @endif
                                                            @endif
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
                                                          {{ $item->ITEM_SERIAL }}
                                                        </td>
                                                        <td>
                                                            {{ $item->ITEM_ID }}
                                                        </td>
                                                        <td>
                                                            {{ $item->QUANTITY }}
                                                        </td>
                                                        <td>
                                                            {{ $item->ITEM_YEAR }} <br> {{ $item->ITEM_MAKE }} <br>{{ $item->ITEM_MODEL }}
                                                        </td>
                                                        <td>
                                                            <span class="text-info">${{ round($item->FMV,2) }}</span><br>
                                                            <span class="text-danger"> ${{ round($item->ASKING_PRICE,2) }}</span>
                                                        </td>
                                                        <td>
                                                            @php $maxBidPrice = 0; @endphp
                                                            @if(isset($item->bids) && !empty($item->bids))
                                                                @foreach($item->bids as $bid)
                                                                    @if($bid->BID > $maxBidPrice)
                                                                        @php $maxBidPrice = $bid->BID; @endphp
                                                                   @endif
                                                                @endforeach
                                                            @endif
                                                            <span class="text-success">${{ round($maxBidPrice,2) }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-success">${{ round($item->SALE_PRICE,2) }}</span>
                                                        </td>
                                                        <td>
                                                            {{ $item->LOC_CITY }}
                                                        </td>
                                                        <td>
                                                            {{ $item->LOC_STATE }}
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('viewItem', $item->ITEM_ID) }}">View</a> <br>
                                                            @if(!empty($item->ASSIGNMENT_ID))
                                                             <a href="{{ route('showAssignment', $item->ASSIGNMENT_ID) }}">Assignment</a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Client Company</th>
                                                <th>Lease Company</th>
                                                <th>Lease#</th>
                                                <th>Assign#</th>
                                                <th>Assignment Date</th>
                                                <th>Recovered Date</th>
                                                <th>Status</th>
                                                <th>Serial#</th>
                                                <th>Item#</th>
                                                <th>Qty</th>
                                                <th>Info</th>
                                                <th>FMV <br> Asking Price<</th>
                                                <th>Max Bids</th>
                                                <th>Sale Price</th>
                                                <th>City</th>
                                                <th>State</th>
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
