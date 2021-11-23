$(document).ready(function () {

    var $primary = '#5A8DEE',
        $success = '#39DA8A',
        $danger = '#FF5B5C',
        $warning = '#FDAC41',
        $info = '#00CFDD',
        $label_color_light = '#E6EAEE',
        $white = '#ffffff';

    var themeColors = [$primary, $warning, $danger, $success, $info];

    // Lets define type of the deals to 0
    var lowFmvData = [];
    var medFmvData = [];
    var HighFmvData = [];
    var assignmentFmvData = [];
    var clientInvoiceOut = [];
    var customerInvoiceOut = [];
    var fmvGenerated = [];
    var assignmentGenerated = [];
    var profit = [];
    var yearComparisonMonths = [];

    var yearComparison = [2016,2017,2018,2019,2020,2021];
    var columnMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    Promise.all(yearComparison.map(function(y) {
        return loadFmvTypeAnalysis(y);
    })).then(function(results) {

        results.forEach(function(d){
            console.log(d);
            if (d.status) {

                columnMonths.forEach(function(m){
                    yearComparisonMonths.push(m+' '+d.filterYear);
                    lowFmvData.push(d.lowFmv[m]/1000);
                    medFmvData.push(d.medFmv[m]/1000);
                    HighFmvData.push(d.highFmv[m]/1000);
                    assignmentFmvData.push(d.assignmentFmv[m]/1000);
                    clientInvoiceOut.push(d.clientInvoicesOut[m]/1000);
                    customerInvoiceOut.push(d.customerInvoiceOut[m]/1000);
                    fmvGenerated.push(d.FmvGenerated[m]);
                    assignmentGenerated.push(d.assignmentGenerated[m]);
                    profit.push(d.profit[m]/1000);
                });

            } else{
                $.each(d.errors, function (key, value) {
                    console.log(value);
                });
            }
            //console.log(lowFmvData);
        });

        generateFmvItemAnalysis(yearComparisonMonths, lowFmvData, medFmvData, HighFmvData, assignmentFmvData, clientInvoiceOut, customerInvoiceOut, profit);

    });


    console.log('All good here');

// Function Ajax to get FMV Data
function loadFmvTypeAnalysis( year ){

    var filterYear = year;

    if( year === undefined ){
        filterYear = '';
    }

    var token = $('meta[name="csrf-token"]').attr("content");

    return $.ajax({
            url: '/loadFmvTypeAnalysis',
            type: "POST",
            dataType: "json",
            data:{
                'year' : filterYear
            },
            headers : { "X-CSRF-TOKEN":token}
        });

}

function generateFmvItemAnalysis(categories, lowFmvData, MedFmvData, higFmvData, assignmentFmvData, clientInvoiceOut, customerInvoiceOut, profit ) {

    var columnChartOptions = {
        chart: {
            height: 550,
            type: 'bar',
            zoom: {
                enabled: true,
                type: 'x',
                autoScaleYaxis: true,
            },
            width: '100%'
        },
        colors: themeColors,
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: 'rounded',
                columnWidth: '100%',
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
        },{
           name: 'Profit',
           data: profit
        }],
        legend: {
            offsetY: -10
        },
        xaxis: {
            categories: categories,
            tickPlacement:'on'
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
            },
            theme:'dark'
        },
        grid: {
            padding: {
                left: 50, // or whatever value that works
                right: 50 // or whatever value that works
            }
        }
    };

    var columnChart = new ApexCharts(
        document.querySelector("#column-chart-fmv"),
        columnChartOptions
    );
    columnChart.render();
}

});



