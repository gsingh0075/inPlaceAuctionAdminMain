@extends('layouts.masterHorizontal')

@section('title','Closed Assignment - InPlace Auction')

@push('page-style')
<style>
</style>
@endpush
@section('content')

    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-12 mb-2 mt-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h5 class="content-header-title float-left pr-1 mb-0">Closed Assignments</h5>
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb p-0 mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li class="breadcrumb-item active">Closed Assignments
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
                <div class="row justify-content-center">
                    <div class="col-11 p-2">
                        <div class="card">
                            <div class="card-content">
                                <div class="card-body" id="closedAssignmentGeo" style="height: 800px">
                                    <!-- Map Loads here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Zero configuration table -->
        </div>
    </div>

@push('page-js')
<script src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyDOSZ6FRxGMp9PN_6TDuiY7mfa0CQZlXJg"></script>
<script>

    var markers = [];
    var center = new google.maps.LatLng(39.5, -98.35);
    var map = new google.maps.Map(document.getElementById('closedAssignmentGeo'), {
        zoom: 3,
        center: center,
        minZoom : 3,
        maxZoom : 15,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        /*styles: [{
            stylers: [{
                saturation: -100
            }]
        }]*/
    });

    var infoWindow = new google.maps.InfoWindow;

    // Ajax loads the Items
    $.ajax({
        url: '/getAllClosedAssignments',
        type: "GET",
        dataType: "json",
        data : { },
        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
        success: function (response) {
            if (response.status) {
                //console.log(response.data);
                var bounds  = new google.maps.LatLngBounds();
                var mapData = response.data;

                if(mapData.length >= 1) {
                    for (var i = 0; i < mapData.length; i++) {
                        //console.log(mapData[i]);
                        if(mapData[i].items.length >= 1) {
                            if(mapData[i].items[0].lat !== null && mapData[i].items[0].lng !== null) {
                                //console.log(mapData.data[i].address_code.latitude);
                                var item_LatLng = new google.maps.LatLng(parseFloat(mapData[i].items[0].lat), parseFloat(mapData[i].items[0].lng));
                                bounds.extend(item_LatLng);
                                assignmentMarker( item_LatLng, mapData[i].assignment_id, mapData[i].lease_nmbr );
                            }

                        }

                    }

                }

                map.fitBounds(bounds);

            }else {
                $.each(response.errors, function (key, value) {
                    toastr.error(value)
                });
            }
        },
        error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
            toastr.error(text);
            //unBlockFMVContainer();
        }
    });

    function assignmentMarker( latLng,assignmentId, title ){


        var html = '<div id="assignmentMarkerContent"><p>Loading........</p><div>';

        var marker = new google.maps.Marker({
            map: map,
            position: latLng,
            title: title,
            assignmentId : assignmentId,
        });

        //console.log(marker);
        google.maps.event.addListener(marker, 'click', function() {

            infoWindow.setContent(html);
            infoWindow.open(map, marker);
            map.setCenter(marker.getPosition());

            console.log('markerClicked');

            $.ajax({
                url: '/assignmentMarker',
                type: "POST",
                dataType: "json",
                data: {
                    assignment_id:marker.assignmentId,
                },
                headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
                success: function (result) {
                    if (result.success) {
                        $('#assignmentMarkerContent').html(result.html);
                    } else {
                        $.each(result.errors, function (key, value) {
                            toastr.error('Marker Loading Failed '+value);
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

</script>
@endpush
@endsection
