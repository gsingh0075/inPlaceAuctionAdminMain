$(document).ready(function () {

    var $primary = '#5A8DEE',
        $success = '#39DA8A',
        $danger = '#FF5B5C',
        $warning = '#FDAC41',
        $info = '#00CFDD',
        $label_color_light = '#E6EAEE',
        $white = '#ffffff';

    var themeColors = [$primary, $warning, $danger, $success, $info];
    var barChartContainerFmv =  $('#container-bar-chart-fmv');
    var columnChartContainerFmv = $('#container-column-chart-fmv');

    loadFmvTypeAnalysis();

    // Load the scroll bar
    new PerfectScrollbar("#client-receivable-table", { wheelPropagation: false });
    new PerfectScrollbar("#customer-receivables", { wheelPropagation: false });

/******************************* Block/Unblock FMV TYPE Analysis *******************************************************************/
function blockBarChart(){

    barChartContainerFmv.block({
        message: '<span class="semibold"> Loading...</span>',
        overlayCSS: {
            backgroundColor: $white,
            opacity: 0.8,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
        }
    });
}
function unBlockBarChart(){
    barChartContainerFmv.unblock();
}

function blockColumnChart(){

    columnChartContainerFmv.block({
            message: '<span class="semibold"> Loading...</span>',
            overlayCSS: {
                backgroundColor: $white,
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
   });
}

function unBlockColumnChart(){
    columnChartContainerFmv.unblock();
}



/********************************* Function Ajax to get FMV Data ***********************************************************/
function loadFmvTypeAnalysis(){

    blockBarChart();
    //blockColumnChart();
    // Lets define type of the deals to 0
    let repo = 0;
    let newDeal = 0;
    let lre = 0;
    let internal = 0;
    let eol = 0;
    let desktop = 0;
    let collection = 0;
    let noReason = 0;
    let lowFmvData = [];
    let medFmvData = [];
    let HighFmvData = [];
    let fmvGenerated = [];
    let assignmentGenerated = [];

    let columnCategories = ['New Deals', 'Loans Loss Reserve Estimates', 'Potential Repossessions', 'Lease Negotiations', 'Internal Appraisals', 'Desktop Appraisals', 'Collection Negotiations', 'No Reason'];

    let columnMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    //console.log(columnMonths);
    $.ajax({
        url: '/loadFmvTypeAnalysis',
        type: "GET",
        dataType: "json",
        headers : { "X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},
        success: function (result) {
            if (result.status) {

                /***** lets Calculate the data for the Column Chart *********/
                columnMonths.forEach(function(m){
                    lowFmvData.push(result.lowFmv[m]/1000);
                    medFmvData.push(result.medFmv[m]/1000);
                    HighFmvData.push(result.highFmv[m]/1000);
                    fmvGenerated.push(result.FmvGenerated[m]);
                    assignmentGenerated.push(result.assignmentGenerated[m]);
                });

                /***** lets Calculate the data for the Bar Chart *********/
                for (let i = 0; i < result.data.length; i++) {
                    switch (result.data[i].reason_for_fmv) {
                        case "Repo":
                            repo++;
                            break;
                        case "New Deal":
                            newDeal++;
                            break;
                        case "LLRE":
                            lre++;
                            break;
                        case "Internal":
                            internal++;
                            break;
                        case "EOL":
                            eol++;
                            break;
                        case "Desktop":
                            desktop++;
                            break;
                        case "Collection":
                            collection++;
                            break;
                        case "":
                            noReason++;
                            break;
                        default:
                            noReason++;
                    }
                }

                //let columnSeriesData = [newDeal,lre,repo,eol,internal,desktop,collection,noReason];
                //generateFmvColumnTypeAnalysis(columnCategories,columnSeriesData);
                generateFmvItemAnalysis(columnMonths, lowFmvData, medFmvData, HighFmvData);
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

}
/******************************** Function generate FMV TYPE Analysis ******************************************************/

function generateFmvColumnTypeAnalysis(categories,data){

    //console.log(data);
    let barChartOptions = {
        chart: {
            height: 350,
            type: 'bar',
        },
        colors: themeColors,
        plotOptions: {
            bar: {
                horizontal: true,
            }
        },
        dataLabels: {
            enabled: false
        },
        series: [{
            data: data
        }],
        xaxis: {
            categories: categories,
            tickAmount: 5
        }
    };
    let barChart = new ApexCharts(
        document.querySelector("#bar-chart-fmv"),
        barChartOptions
    );
    barChart.render();
    unBlockBarChart();

}

/******************************** Function generate FMV Item Analysis ******************************************************/

function generateFmvItemAnalysis(categories, lowFmvData, MedFmvData, higFmvData ) {

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
    unBlockColumnChart();
}

    /******************************** Function generate FMV to Assignment Analysis ******************************************************/

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



