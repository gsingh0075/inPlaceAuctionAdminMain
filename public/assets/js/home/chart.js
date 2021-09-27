$(document).ready(function () {

    var $primary = '#5A8DEE',
        $success = '#39DA8A',
        $danger = '#FF5B5C',
        $warning = '#FDAC41',
        $info = '#00CFDD',
        $label_color_light = '#E6EAEE',
        $white = '#ffffff';

    var themeColors = [$primary, $warning, $danger, $success, $info];

    //Filters Stuff
    var clientReceivableMonth = $('#clientReceivableMonth');
    var clientReceivableYear = $('#clientReceivableYear');
    var yearHeading = $('.yearHeading');
    //Initial Load
    loadFmvTypeAnalysis();
    new PerfectScrollbar("#homeAnalytics", { wheelPropagation: false });

    // Date picker for Modal Box
    $('#client_date_paid').pickadate(); // Date Picker
    $('#customer_date_paid').pickadate();
    $('#remittance_date_paid').pickadate();

    // Client Invoice Paid

    var clientInvoicePaidBtn = $('#clientInvoicePaidBtn');
    var clientInvoicePaidModal = $('#clientInvoicePaidModal');
    var clientInvoiceId = $('#client_invoice_id');

    // Customer Invoice Paid

    var customerInvoicePaidBtn = $('#customerInvoicePaidBtn');
    var customerInvoicePaidModal = $('#customerInvoicePaidModal');
    var customerInvoiceId = $('#customer_invoice_id');

    // Client Remittance Paid

    var remitPaidBtn = $('#remitPaidBtn');
    var clientRemittanceModal = $('#clientRemittanceModal');
    var remittance_id = $('#remittance_id');

/** Modal Boxes for Payment **/

customerInvoicePaidModal.on('show.bs.modal', function (e) {
    let btn = $(e.relatedTarget);
    let id = btn.data('id');
    let amount = btn.data('amount');
    customerInvoiceId.val(id);
    $('#originalCustomerInvoice').html('<span class="text-info">$ ' + amount + '</span>');
});


clientInvoicePaidModal.on('show.bs.modal', function (e) {
    let btn = $(e.relatedTarget);
    let id = btn.data('id');
    let amount = btn.data('amount');
    clientInvoiceId.val(id);
    $('#originalClientInvoice').html('<span class="text-info">$' + amount + '</span>');
});

clientRemittanceModal.on('show.bs.modal', function (e) {
    let btn = $(e.relatedTarget);
    let id = btn.data('id');
    let amount = btn.data('amount');
    remittance_id.val(id);
    $('#originalCustomerPayment').html('<span class="text-info">$' + amount + '</span>');
});

// Get current Month
 function getMonth() {
   var dt = new Date();
   var month = dt.getMonth() + 1;
   return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
 }
// Remit Client
remitPaidBtn.click(function () {

    console.log('Remittance button clicked');
    var action = $(this).attr('data-action');

    let remittance_date_paid = $('#remittance_date_paid');
    let remittance_amount_paid = $('#remittance_amount_paid');
    let remittance_type_paid = $('#remittance_type_paid');

    blockExt(clientRemittanceModal, $('#waitingMessage'));

    $.ajax({
        url: action,
        type: "POST",
        dataType: "json",
        data: {
            'remittance_id': remittance_id.val(),
            'paid_date': remittance_date_paid.val(),
            'amount': remittance_amount_paid.val(),
            'type': remittance_type_paid.val(),
        },
        headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
        success: function (response) {
            if (response.status) {
                Swal.fire({
                    title: "Good job!",
                    text: "Client is successfully remitted",
                    type: "success",
                    confirmButtonClass: 'btn btn-primary',
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.value) {
                        //window.location.reload();
                        unBlockExt( clientRemittanceModal );
                    } else {
                        unBlockExt( clientRemittanceModal );
                    }
                    clientRemittanceModal.modal('hide');

                    //resetting the values
                    remittance_id.val('');
                    remittance_type_paid.val('');
                    remittance_amount_paid.val('');
                    remittance_type_paid.val('');

                    blockClientRemittance();
                    loadClientRemittance(clientReceivableYear.val(), clientReceivableMonth.val());


                });
            } else {
                $.each(response.errors, function (key, value) {
                    toastr.error(value)
                });
                unBlockExt( clientRemittanceModal );
                clientRemittanceModal.modal('hide');

            }
        },
        error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
            toastr.error(text);
            //unBlockFMVContainer();
        }
    });

});


// Mark Invoice Paid as Customer
customerInvoicePaidBtn.click(function () {

        console.log('customer invoice button clicked');
        var action = $(this).attr('data-action');

        let customer_date_paid = $('#customer_date_paid');
        let customer_amount_paid = $('#customer_amount_paid');
        let customer_type_paid = $('#customer_type_paid');
        let customer_memo_paid = $('#customer_memo_paid');

        blockExt(customerInvoicePaidModal, $('#waitingMessage'));

        $.ajax({
            url: action,
            type: "POST",
            dataType: "json",
            data: {
                'invoice_id': customerInvoiceId.val(),
                'paid_date': customer_date_paid.val(),
                'amount': customer_amount_paid.val(),
                'type': customer_type_paid.val(),
                'memo': customer_memo_paid.val()
            },
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: "Good job!",
                        text: "Invoice is marked as paid",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            //window.location.reload();
                            unBlockExt( customerInvoicePaidModal );
                        } else {
                            unBlockExt( customerInvoicePaidModal );
                        }
                        customerInvoicePaidModal.modal('hide');

                        // Resetting the values
                        customerInvoiceId.val('');
                        customer_date_paid.val('');
                        customer_amount_paid.val('');
                        customer_type_paid.val('');
                        customer_memo_paid.val('');

                        blockCustomerReceivables();
                        loadCustomerReceivables( clientReceivableYear.val(), clientReceivableMonth.val());


                    });
                } else {
                    $.each(response.errors, function (key, value) {
                        toastr.error(value)
                    });
                    unBlockExt( customerInvoicePaidModal );
                    customerInvoicePaidModal.modal('hide');
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
                toastr.error(text);
                //unBlockFMVContainer();
            }
        });

});
// Mark Invoice as Paid Client
clientInvoicePaidBtn.click(function () {

        console.log('client invoice clicked');
        var action = $(this).attr('data-action');

        let client_date_paid = $('#client_date_paid');
        let client_amount_paid = $('#client_amount_paid');
        let client_type_paid = $('#client_type_paid');
        let client_memo_paid = $('#client_memo_paid');

        blockExt(clientInvoicePaidModal, $('#waitingMessage'));

        $.ajax({
            url: action,
            type: "POST",
            dataType: "json",
            data: {
                'invoice_id': clientInvoiceId.val(),
                'paid_date': client_date_paid.val(),
                'amount': client_amount_paid.val(),
                'type': client_type_paid.val(),
                'memo': client_memo_paid.val()
            },
            headers: {"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")},
            success: function (response) {
                if (response.status) {
                    Swal.fire({
                        title: "Good job!",
                        text: "Invoice is marked as paid",
                        type: "success",
                        confirmButtonClass: 'btn btn-primary',
                        buttonsStyling: false,
                    }).then(function (result) {
                        if (result.value) {
                            //window.location.reload();
                            unBlockExt( clientInvoicePaidModal );
                        } else {
                            unBlockExt( clientInvoicePaidModal );
                        }
                        clientInvoicePaidModal.modal('hide');

                        // resetting the values
                        clientInvoiceId.val('');
                        client_date_paid.val('');
                        client_amount_paid.val('');
                        client_type_paid.val('');
                        client_memo_paid.val('');

                        blockClientReceivables();
                        loadClientReceivables( clientReceivableYear.val(), clientReceivableMonth.val());
                    });
                } else {
                    $.each(response.errors, function (key, value) {
                        toastr.error(value)
                    });
                    unBlockExt( clientInvoicePaidModal );
                    clientInvoicePaidModal.modal('hide');
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
                toastr.error(text);
            }
        });

});

var currMonth = getMonth();
//console.log(currMonth);
// Multiple Select Month
clientReceivableMonth.select2({
    placeholder: "Month",
});
clientReceivableMonth.val(currMonth);
clientReceivableMonth.trigger('change');

// Filter Year Month Change
clientReceivableYear.change(function(){

    let year = $(this).val();
    let month = clientReceivableMonth.val();

    loadFmvTypeAnalysis(year, month);
    //console.log(year);
    //console.log(month);
    //blockClientReceivables();
    //loadClientReceivables(year, month);

    //blockCustomerReceivables();
    //loadCustomerReceivables(year, month);

    //blockClientRemittance();
    //loadClientRemittance(year, month);

    //blockHomeAnalytics();
    //loadAnalytics(year, month);


});

clientReceivableMonth.change(function(){

    let month = $(this).val();
    let year = clientReceivableYear.val();
    console.log(year);
    console.log(month);

    blockClientReceivables();
    loadClientReceivables(year, month);

    blockCustomerReceivables();
    loadCustomerReceivables(year, month);

    blockClientRemittance();
    loadClientRemittance(year, month);

    blockHomeAnalytics();
    loadAnalytics(year, month);

});

// Block UI and Non Block UI
function blockClientReceivables(){

    $('#clientReceivables').block({
        message: '<div class="bx bx-revision icon-spin font-medium-2"></div>',
        showOverlay: false,
        css: {
            width: 50,
            height: 50,
            lineHeight: 1,
            color: $white,
            border: 0,
            padding: 15,
            backgroundColor: '#333'
        }
    });
}
function unBlockClientReceivables(){

    $('#clientReceivables').unblock();
}

function blockCustomerReceivables(){

        $('#customerInvoices').block({
            message: '<div class="bx bx-revision icon-spin font-medium-2"></div>',
            showOverlay: false,
            css: {
                width: 50,
                height: 50,
                lineHeight: 1,
                color: $white,
                border: 0,
                padding: 15,
                backgroundColor: '#333'
            }
        });
}

function unBlockCustomerReceivables(){

        $('#customerInvoices').unblock();
}

function blockHomeAnalytics(){

    $('#homeAnalytics').block({
            message: '<div class="bx bx-revision icon-spin font-medium-2"></div>',
            showOverlay: false,
            css: {
                width: 50,
                height: 50,
                lineHeight: 1,
                color: $white,
                border: 0,
                padding: 15,
                backgroundColor: '#333'
            }
    });
}

function UnBlockHomeAnalytics(){

    $('#homeAnalytics').unblock();
}

function blockClientRemittance(){

   $('#clientRemittance').block({
        message: '<div class="bx bx-revision icon-spin font-medium-2"></div>',
        showOverlay: false,
            css: {
                width: 50,
                height: 50,
                lineHeight: 1,
                color: $white,
                border: 0,
                padding: 15,
                backgroundColor: '#333'
            }
   });

}
function unBlockClientRemittance(){

 $('#clientRemittance').unblock();
}

// Load Pending Items

var markers = [];
    var center = new google.maps.LatLng(39.5, -98.35);
    var map = new google.maps.Map(document.getElementById('itemsPendingAuthorization'), {
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
            url: '/getUnsoldItemsAssignments',
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
                                        assignmentMarker(mapData[i].assign_status, item_LatLng, mapData[i].assignment_id, mapData[i].lease_nmbr);
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

 // Items Marker

 function assignmentMarker(status, latLng,assignmentId, title){

     var iconType = 'http://maps.google.com/mapfiles/ms/icons/purple-dot.png';

     if(status === 'ItemRecovery'){
         iconType =  'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
     }
     if(status === 'ItemSold'){
         iconType =  'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
     }
     if(status === 'CustomerPaid'){
         iconType =  'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
     }
     if(status === 'ClientPaid'){
         iconType =  'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
     }

     var html = '<div id="assignmentMarkerContent"><p>Loading........</p><div>';

     var marker = new google.maps.Marker({
         map: map,
         position: latLng,
         title: title,
         assignmentId : assignmentId,
         icon: {
             url: iconType
         }
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

// Function Ajax to get FMV Data

function loadFmvTypeAnalysis( year, month ){

    var filterYear = year;
    var filterMonth = month;

    if( year === undefined ){
        filterYear = '';
    }

    if( month === undefined){
        filterMonth = '';
    }

    if( year === undefined ){
        let d = new Date();
        let mn = d.getFullYear();
        yearHeading.html(mn);
    } else {
        yearHeading.html(year);
    }


    blockClientReceivables();
    blockCustomerReceivables();
    blockHomeAnalytics();
    blockClientRemittance();
    // Lets define type of the deals to 0
    let lowFmvData = [];
    let medFmvData = [];
    let HighFmvData = [];
    let assignmentFmvData = [];
    let clientInvoiceOut = [];
    let customerInvoiceOut = [];
    let fmvGenerated = [];
    let assignmentGenerated = [];

    let columnMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    var token = $('meta[name="csrf-token"]').attr("content");
    //console.log(token);
    // generate teh charts
    $.ajax({
        url: '/loadFmvTypeAnalysis',
        type: "POST",
        dataType: "json",
        data:{
            'year' : filterYear
        },
        headers : { "X-CSRF-TOKEN":token},
        success: function (result) {
            if (result.status) {

                columnMonths.forEach(function(m){
                    lowFmvData.push(result.lowFmv[m]/1000);
                    medFmvData.push(result.medFmv[m]/1000);
                    HighFmvData.push(result.highFmv[m]/1000);
                    assignmentFmvData.push(result.assignmentFmv[m]/1000);
                    clientInvoiceOut.push(result.clientInvoicesOut[m]/1000);
                    customerInvoiceOut.push(result.customerInvoiceOut[m]/1000);
                    fmvGenerated.push(result.FmvGenerated[m]);
                    assignmentGenerated.push(result.assignmentGenerated[m]);
                });

                generateFmvItemAnalysis(columnMonths, lowFmvData, medFmvData, HighFmvData, assignmentFmvData, clientInvoiceOut, customerInvoiceOut);
                generateFmvToAssignmentAnalysis(columnMonths,fmvGenerated,assignmentGenerated);

            } else {
                $.each(result.errors, function (key, value) {
                    //toastr.error(value);
                    console.log(value);
                });
            }
        },
        error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
            //toastr.error(text);
        }
    });

    // Load the Client & Customer Receivables
    loadClientReceivables( filterYear, filterMonth);
    loadCustomerReceivables( filterYear, filterMonth);
    loadAnalytics( filterYear, filterMonth);
    loadClientRemittance( filterYear, filterMonth);

}

// Load Home Analytics
function loadAnalytics( year, month){

    //Catch the Variables.
    var filterYear = year;
    var filterMonth = month;

    if( year === undefined ){
        filterYear = '';
    }
    if( month === undefined){
        filterMonth = '';
    }


    $.ajax({
        url: '/homeAnalytics',
        type: "POST",
        dataType: "json",
        data:{
            'month': filterMonth,
            'year' : filterYear
        },
        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
        success: function (result) {
            if (result.status) {
                // Update the Div
                $('#homeAnalytics').html(result.html);
                UnBlockHomeAnalytics();
            } else {
                $.each(result.errors, function (key, value) {
                    //toastr.error(value);
                    console.log(value);
                });
                UnBlockHomeAnalytics();
            }
        },
        error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
            //toastr.error(text);
        }
    });

}
//Load Client Receivables
function loadClientReceivables( year, month){

    //Catch the Variables.
    var filterYear = year;
    var filterMonth = month;

    if( year === undefined ){
        filterYear = '';
    }
    if( month === undefined){
        filterMonth = '';
    }

    $.ajax({
        url: '/getEquipmentInvoices',
        type: "POST",
        dataType: "json",
        data:{
            'month': filterMonth,
            'year' : filterYear
        },
        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
        success: function (result) {
            if (result.status) {
                // Update the Div
                $('#clientReceivables').html(result.html);
                unBlockClientReceivables();
            } else {
                $.each(result.errors, function (key, value) {
                    //toastr.error(value);
                    console.log(value);
                });
                unBlockClientReceivables();
            }
        },
        error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
            //toastr.error(text);
        }
    });

}

// Load Customer Receivables

function loadCustomerReceivables( year, month){

    //Catch the Variables.
    var filterYear = year;
    var filterMonth = month;

    if( year === undefined ){
        filterYear = '';
    }
    if( month === undefined){
        filterMonth = '';
    }

    $.ajax({
        url: '/getCustomerInvoices',
        type: "POST",
        dataType: "json",
        data:{
            'month': filterMonth,
            'year' : filterYear
        },
        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
        success: function (result) {
            if (result.status) {
                // Update the Div
                $('#customerInvoices').html(result.html);
                unBlockCustomerReceivables();
            } else {
                $.each(result.errors, function (key, value) {
                    //toastr.error(value);
                    console.log(value);
                });
                unBlockCustomerReceivables();
            }
        },
        error: function (xhr, resp, text) {
            console.log(xhr, resp, text);
            //toastr.error(text);
        }
    });

}

    // Load Client Remittance

    function loadClientRemittance( year, month){

        //Catch the Variables.
        var filterYear = year;
        var filterMonth = month;

        if( year === undefined ){
            filterYear = '';
        }
        if( month === undefined){
            filterMonth = '';
        }

        $.ajax({
            url: '/getClientRemittance',
            type: "POST",
            dataType: "json",
            data:{
                'month': filterMonth,
                'year' : filterYear
            },
            headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
            success: function (result) {
                if (result.status) {
                    $('#clientRemittance').html(result.html);
                    unBlockClientRemittance();
                } else {
                    $.each(result.errors, function (key, value) {
                        console.log(value);
                    });
                    unBlockClientRemittance();
                }
            },
            error: function (xhr, resp, text) {
                console.log(xhr, resp, text);
                //toastr.error(text);
            }
        });

    }
// Function generate FMV Item Analysis

function generateFmvItemAnalysis(categories, lowFmvData, MedFmvData, higFmvData, assignmentFmvData, clientInvoiceOut, customerInvoiceOut ) {

    var columnChartOptions = {
        chart: {
            height: 350,
            type: 'bar',
        },
        colors: themeColors,
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: 'rounded',
                columnWidth: '55%',
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        series: [{
            name: 'Sum of Sent FMV value(LOW)',
            data: lowFmvData
        }, {
            name: 'Sum of Sent FMV value (MED)',
            data: MedFmvData
        }, {
            name: 'Sum of Sent FMV Value(HIGH)',
            data: higFmvData
        },{
            name: 'Sum of Assignment Item FMV Value',
            data: assignmentFmvData
        },{
            name: 'Client Invoices Sent',
            data: clientInvoiceOut
         },{
           name: 'Customer Invoice Sent',
           data: customerInvoiceOut
        }],
        legend: {
            offsetY: -10
        },
        xaxis: {
            categories: categories,
        },
        yaxis: {
            title: {
                text: '$ (thousands)'
            }
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands"
                }
            }
        }
    };

    var columnChart = new ApexCharts(
        document.querySelector("#column-chart-fmv"),
        columnChartOptions
    );
    columnChart.render();
}

// Function generate FMV to Assignment Analysis

function generateFmvToAssignmentAnalysis(columnMonths,fmvGenerated,assignmentGenerated) {

        var mixedChartOptions = {
            chart: {
                height: 350,
                type: 'line',
                stacked: false,
            },
            colors: themeColors,
            stroke: {
                width: [0, 2, 5],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%'
                }
            },
            series: [{
                name: 'FMV',
                type: 'column',
                data: fmvGenerated
            }, {
                name: 'Assignment',
                type: 'area',
                data: assignmentGenerated
            }],
            fill: {
                opacity: [0.85, 0.25, 1],
                gradient: {
                    inverseColors: false,
                    shade: 'light',
                    type: "vertical",
                    opacityFrom: 0.85,
                    opacityTo: 0.55,
                    stops: [0, 100, 100, 100]
                }
            },
            labels:columnMonths,
            markers: {
                size: 0
            },
            legend: {
                offsetY: -10
            },
            yaxis: {
                min: 0,
                tickAmount: 5,
                title: {
                    text: 'Number#'
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function (y) {
                        if (typeof y !== "undefined") {
                            return y.toFixed(0) + " views";
                        }
                        return y;

                    }
                }
            }
        }
        var mixedChart = new ApexCharts(
            document.querySelector("#bar-chart-fmv-assignment"),
            mixedChartOptions
        );
        mixedChart.render();

    }


});



