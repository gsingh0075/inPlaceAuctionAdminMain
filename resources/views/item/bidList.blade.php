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
                        <h5 class="content-header-title float-left pr-1 mb-0">Item Bids</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">All Item Bids
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
                                        <table class="table dataTable" id="getItemBidDataTable">
                                            <thead>
                                            <tr>
                                                <th>Bid Amount</th>
                                                <th>Asking Price</th>
                                                <th>FMV</th>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Status</th>
                                                <th>Bid Date</th>
                                                <th>Item</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                              @if(isset($itemsBids) && !empty($itemsBids))
                                                  @foreach($itemsBids as $bid)
                                                      <tr>
                                                          <td>
                                                              ${{ round($bid->BID,2) }}
                                                          </td>
                                                          <td>
                                                              @if(isset($bid->item) && !empty($bid->item))
                                                                  ${{ round($bid->item->ASKING_PRICE,2) }}
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if(isset($bid->item) && !empty($bid->item))
                                                                  ${{ round($bid->item->FMV,2) }}
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if(isset($bid->customer) && !empty($bid->customer))
                                                                  {{ $bid->customer->FIRSTNAME }}   {{ $bid->customer->LASTNAME }}
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if(isset($bid->customer) && !empty($bid->customer))
                                                                  {{ $bid->customer->COMPANY }}
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if($bid->BID_ACCEPTED === 1)
                                                                  <span class="text-success">Accepted</span>
                                                              @elseif($bid->NEW_BID === 1)
                                                                  <span class="text-info">New</span>
                                                              @else
                                                                  <span class="text-danger">Rejected</span>
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if(!empty($bid->BID_DT))
                                                                  {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $bid->BID_DT)->format('j F, Y')}}
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if(isset($bid->item) && !empty($bid->item))
                                                                  {{ $bid->item->ITEM_MAKE }}  {{ $bid->item->ITEM_MODEL }}  {{ $bid->item->ITEM_YEAR }}
                                                              @endif
                                                          </td>
                                                          <td>
                                                              @if(isset($bid->item) && !empty($bid->item))
                                                                 <a href="{{ route('viewItem', $bid->item->ITEM_ID) }}">View Item</a>
                                                              @endif
                                                          </td>
                                                      </tr>
                                                  @endforeach
                                              @endif
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <th>Bid Amount</th>
                                                <th>Asking Price</th>
                                                <th>FMV</th>
                                                <th>Name</th>
                                                <th>Company</th>
                                                <th>Status</th>
                                                <th>Bid Date</th>
                                                <th>Item</th>
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
            $('#getItemBidDataTable').dataTable({
                pageLength: 100,
                order : [],
            });
    });
</script>
@endpush
@endsection
