@extends('layouts.masterHorizontal')

@section('title','List Contractors - InPlace Auction')

@push('page-style')
<style>
  #getContractorsDataTable a{
      text-decoration: underline;
  }
  #contractorMap {
      height: 500px;
  }
  #contractorMarkerContent p {
      color: #000;
      margin-bottom: 10px;
  }
  .pac-container{
      z-index: 9999;
  }
  .pac-container .pac-item span{
      color: #000!important;
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
                                <li class="breadcrumb-item">
                                <button type="button" class="btn btn-primary mr-1 mb-1"
                                        id="findContractor"
                                        data-toggle="modal"
                                        data-target="#findContractorMap">Find Contractor
                                </button>
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
                                                <th>Type</th>
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
                                                              @if($c->is_equipment_contractor == 1)
                                                                  <a class="btn btn-primary" style="text-decoration: none" href="#" role="button">Equipment</a>
                                                                  <br>
                                                                  <br>
                                                              @endif
                                                              @if($c->is_appraisal_contractor == 1)
                                                                  <a class="btn btn-success" style="text-decoration: none" href="#" role="button">Appraisal</a>
                                                                  <br>
                                                                  <br>
                                                              @endif
                                                              @if($c->is_inspection_contractor == 1)
                                                                  <a class="btn btn-warning" href="#" style="text-decoration: none" role="button">Inspection</a>
                                                              @endif
                                                          </td>
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
                                                <th>Type</th>
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

            <!-- Find Contractor Near By  -->
            <div class="modal fade text-left" id="findContractorMap" data-backdrop="static"
                 data-keyboard="false" tabindex="-1" role="dialog"
                 aria-labelledby="findContractorMap" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered"
                     role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel33">Find Contractors</h4>
                            <button type="button" class="close"
                                    data-dismiss="modal" aria-label="Close">
                                <i class="bx bx-x"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 py-2 filtersContainer">
                                    <div class="row">
                                        <div class="col-md-2 col-12">
                                            <label for="filterContractors">Address</label>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <input class="form-control" type="text" id="findAddress" name="findAddress" style="width: 100%">
                                        </div>
                                        <div class="col-md-2 col-12">
                                            <button type="button" class="btn btn-primary mr-1 mb-1" id="filterContractorButton">Find</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <p><b>NOTE: Yellow marker shows Item locations. Blue markers shows contractor locations.</b></p>
                                    <div class="map" id="contractorMap">
                                        <!-- Loads via Ajax-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-secondary"
                                    data-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Contractor Near By Modal Box -->

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
<script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDOSZ6FRxGMp9PN_6TDuiY7mfa0CQZlXJg&libraries=places"></script>
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

        //Contractor Modal on show
        $('#findContractorMap').on('show.bs.modal', function (event) {

            //let findContractorMapModal = $('#findContractorMap');
            //blockExt(findContractorMapModal, $('#waitingMessage'));
            //loadContractors( findContractorMapModal);

        });

        // Auto Complete Stuff

        // Google Address Auto Complete
        let placeSearch;
        let autocomplete;
        const componentForm = {
            locality: "long_name",
            administrative_area_level_1: "short_name",
            postal_code: "short_name",
        };
        initAutocomplete( "findAddress" );

        function initAutocomplete( fieldName ) {
            // Create the autocomplete object, restricting the search predictions to
            // geographical location types.
            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById(fieldName),
                //document.getElementsByName('equip_address'),
                { types: ["geocode"] }
            );
            // Avoid paying for data that you don't need by restricting the set of
            // place fields that are returned to just the address components.
            autocomplete.setFields(["address_component","geometry"]);
            // When the user selects an address from the drop-down, populate the
            // address fields in the form.
            autocomplete.addListener("place_changed", function(){

                const place = autocomplete.getPlace();
                let findContractorMapModal = $('#findContractorMap');

                if (!place.geometry) {
                    return;
                }
                console.log( place.geometry.location.lat());
                console.log( place.geometry.location.lng());

                loadContractors( findContractorMapModal, place.geometry.location.lat(), place.geometry.location.lng());

            });
        }

        var markers = [];
        var center = new google.maps.LatLng(30.29461050801138, 15.360816686284807);
        var map = new google.maps.Map(document.getElementById('contractorMap'), {
            zoom: 3,
            center: center,
            minZoom: 3,
            maxZoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var infoWindow = new google.maps.InfoWindow;

        function deleteMarkers() {

            if( markers.length > 0) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
                markers = [];
            }

        }


        // Function Load Contractors
        function loadContractors(b, lat, lng){

            deleteMarkers();

            $.ajax({
                url: "{{ route('findContractors') }}",
                type: "POST",
                dataType: "json",
                data: {
                    'lat' : lat,
                    'lng' : lng
                },
                headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                success: function (response) {
                    if (response.status) {
                        console.log(response.data);
                        var bounds = new google.maps.LatLngBounds();
                        var mapData = response;

                        if (mapData.data.length >= 1) {
                            for (var i = 0; i < mapData.data.length; i++) {

                                if (mapData.data[i].address_code.latitude !== '' && mapData.data[i].address_code.longitude !== '') {
                                    //console.log(mapData.data[i].address_code.latitude);
                                    var contr_LatLng = new google.maps.LatLng(parseFloat(mapData.data[i].address_code.latitude), parseFloat(mapData.data[i].address_code.longitude));
                                    //if( mapData.data[i].type === 'A') {
                                    //bounds.extend(contr_LatLng);
                                    //}
                                    bounds.extend(contr_LatLng);
                                    contractorMarker(contr_LatLng, mapData.data[i].contractor_id, mapData.data[i].name, mapData.data[i].type, lat, lng);
                                }
                            }

                        }

                        map.fitBounds(bounds);

                        unBlockExt(b);

                    } else {
                        $.each(response.errors, function (key, value) {
                            toastr.error(value)
                        });
                        unBlockExt(b);
                    }
                },
                error: function (xhr, resp, text) {
                    console.log(xhr, resp, text);
                    toastr.error(text);
                    unBlockExt(b);
                }
            });

        }


        function contractorMarker(latLng, contractorID, title, type, addressLat, addressLng) {

            var iconType = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';

            if (type === 'B') {
                iconType = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png'
            }

            if (type === 'C') {
                iconType = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
            }

            var html = '<div id="contractorMarkerContent"><p>Loading........</p><div>';

            var marker = new google.maps.Marker({
                map: map,
                position: latLng,
                title: title,
                contractorID: contractorID,
                addressLat : addressLat,
                addressLng : addressLng,
                icon: {
                    url: iconType
                }
            });

            console.log(marker);

            google.maps.event.addListener(marker, 'click', function () {

                infoWindow.setContent(html);
                infoWindow.open(map, marker);
                map.setCenter(marker.getPosition());

                $.ajax({
                    url: "{{ route('viewContractorMarker') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        contractor_id: marker.contractorID,
                        lat: marker.addressLat,
                        lng: marker.addressLng
                    },
                    headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
                    success: function (result) {
                        if (result.success) {
                            $('#contractorMarkerContent').html(result.html);
                        } else {
                            $.each(result.errors, function (key, value) {
                                toastr.error('Marker Loading Failed ' + value);
                            });
                        }
                    },
                    error: function (xhr, resp, text) {
                        console.log(xhr, resp, text);
                        toastr.error(text);
                    }
                });

            });
            markers.push(marker);
        }



    });

</script>
@endpush
@endsection
