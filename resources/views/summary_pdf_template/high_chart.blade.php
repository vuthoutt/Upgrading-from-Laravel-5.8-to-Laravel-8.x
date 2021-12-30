<?php

if (isset($summary_type) && $summary_type == "managerOverview") {
    $titleSummary = 'Managers Overview - Asbestos';
} else {
    $titleSummary = 'Directors Overview - Asbestos';
}
$isPDF = (isset($_GET['isPDF']) && $_GET['isPDF']) ? TRUE : FALSE;

$clientid = 1;
$fromTime = $month;
$toTime = $next_month;
//echo $_GET['breadcrumbBottom'];

$chartFixedWidth = 1100;
$cTime = time();
// without time quarter
//if(!$fromTime) exit();
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= $titleSummary; ?></title>
    <style type="text/css">
        body {
            /*padding: 10px 10px 10px 20px;*/
            color: #666;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-size: 10pt;
        }

        p {
            margin: 1px 0;
            text-align: left;
        }

        .spanMiddle {
            font-weight: normal;
            font-size: 17pt;
            margin: 9px 0px 5px 0px;
        }

        .chartContainer {
            margin-top: <?= ($isPDF) ? "200px" : "40px"?>;
            width: 100%;
            height: 400px;
            position: relative;
        }
    </style>
</head>
<body>


<div id="content" style="position: relative;">
    <table border="0" style="border:none;height: 115px;">
        <tr>
            <td>
                <img height="115"
                     src=""/>
            </td>
            <td>
                <span class="spanMiddle"><strong><?= $titleSummary; ?></strong></span>
            </td>
        </tr>
    </table>
    <div id="containerMS" class="chartContainer"></div>
    <div id="containerPfA" class="chartContainer"></div>
    <div style="page-break-before: always; <?= ($isPDF) ? "height: 100px;" : ""; ?>"></div>
    <div id="containerPT" class="chartContainer"></div>
    <div id="containerUL" class="chartContainer"></div>
    <div style="page-break-before: always; <?= ($isPDF) ? "height: 100px;" : ""; ?>"></div>
    <div id="containerPS" class="chartContainer"></div>
    <?php if (isset($summary_type) && $summary_type == "managerOverview") { ?>

        <div id="containerPD" class="chartContainer"></div>
        <div style="page-break-before: always; <?= ($isPDF) ? "height: 100px;" : ""; ?>"></div>
        <div id="containerPDD" class="chartContainer"></div>
    <?php } ?>
    <br/><br/> <strong>IMPORTANT:</strong> The asbestos management information is live, meaning that the system is
                                           constantly being updated and amended. Summaries created from information in
                                           either the asbestos register or management system will therefore only be
                                           valid for the time and date they are created. Summaries provide a limited
                                           overview of the data available, they are not designed to be used by those
                                           carrying out major refurbishment or works involving alterations to the fabric
                                           of a building or its services. Contact the Asbestos Management Team for a
                                           more detailed overview of the data available, they are not designed to be
                                           used by those carrying out major refurbishment or works involving alterations
                                           to the fabric of a building
</div><!-- PAGE 01 -->

      <!--Container - set width -->
</body>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="{{ asset('js/highcharts.js') }}"></script>
<script>
    $(document).ready(function () {
        Highcharts.setOptions({

            chart: {
                width: <?= $chartFixedWidth ?>
            },
            xAxis: {
                labels: {
                    style: {

                        fontWeight: 'bold'
                    }
                }
            },
            yAxis: {
                min: 0,
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            tooltip: {
                useHTML: true,
                backgroundColor: {
                    linearGradient: [0, 0, 0, 60],
                    stops: [
                        [0, '#FFFFFF'],
                        [1, '#E0E0E0']
                    ]
                },
                borderColor: '#000000',
                headerFormat: '<strong>{point.key}</strong><br/>',
                pointFormat: '<span style="color:{series.color}; text-shadow: 1px 1px 1px rgba(0,0,0,0.3);">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                shared: true
            },
            legend: {
                useHTML: true,
                itemStyle: {
                    fontWeight: 'normal'
                },
                align: 'right',
                verticalAlign: 'top',
                layout: 'vertical',
                x: 0,
                y: 100,
                labelFormatter: function () {
                    return '<div style="width:220px"><span style="float:left">' + this.name + '</span><span style="float:right">' + this.options.total + '</span></div>';
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: <?= ($isPDF) ? "true" : "false"; ?>,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                },
                column: {
                    dataLabels: {
                        enabled: true,
                        overflow: false,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black'
                        },
                        formatter: function () {
                            if (this.percentage >= 7) return this.y;
                        }
                    }
//                    }
                },
                series: {
                    enableMouseTracking: <?= (!$isPDF) ? "true" : "false"; ?>, animation: <?= (!$isPDF) ? "true"
                        : "false"; ?> }
            },
            title: {
                align: 'left',
                x: 70,
                style: {
                    fontWeight: 'bold'
                }
            }
        });
        // MS CHART
        $('#containerMS').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Management Surveys'
            }
        }, function (chart) {
            oneLegendOnly(chart);
        });

        // PfA Chart
        $('#containerPfA').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: $("#display-property-group option:selected" ).text() + '  Re-Inspection Programme'
            },
            yAxis: {
                title: {
                    text: ''
                }
            },
            xAxis: {
                labels: {
                    useHTML:true,
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            tooltip: {
                borderWidth: 1,
                backgroundColor: 'rgba(0,0,0,1)',
                borderColor: 'black',
                formatter: function() {
                    var result = "<span class='' style='color:white;font-weight:bold;'> Re Inspection Due Date : ";
                    var str = "No Data";
                    $.each(this.points, function(i, point) {
                        var index = point.series.xData.indexOf(this.point.x);
                        if(point.series.userOptions.total && point.series.userOptions.data[index]){
                            console.log(point.series.userOptions.data[index])
                            str = getExtraDate(point.series.userOptions.data[index]);
                        }
                    });
                    return result  + str + ' </span>';
                },
                shared: true
            },
        }, function (chart) {
            columnChartLegendGroupFix(chart);
    });

        $('#containerPS').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Project Sponsor'
            },
            yAxis: {
                labels: {
                    format: '{value}%'
                },
                title: {
                    text: 'Percentage'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'percent'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });

        $('#containerPT').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Product/debris Type'
            }
        }, function (chart) {
            oneLegendOnly(chart);
        });




    });


    function oneLegendOnly(chart) {
        $(chart.series[0].data).each(function (i, e) {
            e.legendGroup.on('click', function (event) {
                if (e.visible) {
                    e.setVisible(false);
                }
                else {
                    e.setVisible(true);
                }

            });
        });
    }


    function columnChartLegendGroupFix(chart) {
        $(chart.series).each(function (i, e) {
            e.legendGroup.on('click', function (event) {
                if (e.visible) {
                    e.setVisible(false);
                }
                else {
                    e.setVisible(true);
                }

            });
        });
    }

    //get extra date
    function getExtraDate(numberOfDaysToAdd){
        console.log(numberOfDaysToAdd);
        var someDate = new Date();
        someDate.setDate(someDate.getDate() + numberOfDaysToAdd);
        var dd = someDate.getDate();
        dd = dd < 10 ? '0'+ dd : dd;
        var mm = someDate.getMonth() + 1;
        mm = mm < 10 ? '0'+ mm : mm;
        var y = someDate.getFullYear();
        return  dd + '/'+ mm + '/'+ y;
    }
</script>
</html>
