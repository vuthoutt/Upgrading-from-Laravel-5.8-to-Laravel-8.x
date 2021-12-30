<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN" >
<html lang=en>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Contractor KPI Summary</title>
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

        span {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
        }

        tspan{
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
        }

        .spanMiddle {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            font-weight: normal;
            font-size: 17pt;
            margin: 9px 0 5px 0;
        }

        .chartContainer {
            margin-top: 40px;
            width: 100%;
            height: 400px;
        }

        .chartContainerSI {
            /*margin-top: 40px;*/
            width: <?= 100/$countCharts - 1;?>%;
            height: 400px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div id="content">
    <table border="0" style="border:none;height: 115px;">
        <tr>
            <td>
                <img height="115"
                     src="{{ CommonHelpers::getFile(1, CLIENT_LOGO, $isPDF) }}"/>
            </td>
            <td>
                <span class="spanMiddle"><strong>Contractor KPI Summary</strong></span>
            </td>
        </tr>
    </table>
    <div id="containerPC" class="chartContainer"></div>
    <div id="containerDP" class="chartContainer"></div>
    <div id="containerTP" class="chartContainer"></div>
    <div id="containerTechP" class="chartContainer"></div>
    <div id="containerCS" class="chartContainer"></div>
    <h4 style='
        margin-left: 80px;
        margin-top: 40px;
        color: #333333;
        font-size: 18px;
        font-weight: bold;'>Survey Information</h4>
    <?php
    foreach ($contractor_name as $key => $contractorName) {
        echo '<div id="containerSI' . $key . '" class="chartContainerSI"></div>';
    }
    ?>

    <br/> <br/>
    <?php
    $count = 0;
    foreach ($surveyInformationCategories as $key => $category) {
        $count += $total_SI[$key];
        echo '<div style="display: inline-block; margin-left: 30px;"><span style="float:left">' . $category . ' = ' . $surveyInformationLegendInfo[$key] . '</span><span style="padding-left:15px;">Total ' . $total_SI[$key] . '</span></div>';
    }
    echo '<div style="display: inline-block; margin-left: 30px;"><span style="float:left">Total Survey Information</span><span style="padding-left:15px;">Total ' . $count . '</span></div>';
    echo '<br /><br />';
    ?>
    <div id="customLegend"></div>
    <br/><br/> <strong>IMPORTANT:</strong> The asbestos management information is live, meaning that the system is
    constantly being updated and amended. Summaries created from information
    in either the asbestos register or management system will therefore only
    be valid for the time and date they are created. Summaries provide a
    limited overview of the data available, they are not designed to be used
    by those carrying out major refurbishment or works involving alterations
    to the fabric of a building or its services. Contact the Asbestos
    Management Team for a more detailed overview of the data available, they
    are not designed to be used by those carrying out major refurbishment or
    works involving alterations to the fabric of a building
</div><!-- PAGE 01 -->

</body>

<script src="{{$isPDF ? \CommonHelpers::getAssetFile('js/jquery_kpi.min.js') : asset('js/jquery_kpi.min.js') }}"></script>
<script src="{{$isPDF ? \CommonHelpers::getAssetFile('js/highcharts.js') : asset('js/highcharts.js') }}"></script>
<script>
    var contractorsName = <?= json_encode($contractor_name) ?>;
    $(function () {
        $(document).ready(function () {

            var surveyInformation = <?= json_encode($data_SI) ?>;
            Highcharts.setOptions({
                chart: {
                    width: <?= $chartFixedWidth ?>
                },
                xAxis: {
                    labels: {
                        style: {
                            //                            fontSize: '14px',
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
                    labelFormatter: function () {
                        return '<div style="width:200px"><span style="float:left">' + this.name + '</span><span style="float:right">' + this.options.total + '</span></div>';
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: <?= ($isPDF) ? "true" : "false"; ?>,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            overflow: false,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                            distance: -50,
                            style: {
                                fontSize: "20px",
                                fontWeight: 'bold',
                                textShadow: '0 0 3px black'
                            },
                            formatter: function () {
                                if (this.percentage >= 7) return this.y;
                            }
                        },
                        showInLegend: true
                    },
                    column: {
                        dataLabels: {
                            enabled: true,
                            overflow: false,
                            color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                            style: {
                                fontSize: "18px",
                                fontWeight: 'bold',
                                textShadow: '0 0 3px black'
                            },
                            formatter: function () {
                                if (this.percentage >= 7) return this.y;
                            }
                        }
                    },
                    series: {
                        enableMouseTracking: <?= (!$isPDF) ? "true" : "false";; ?>, animation: <?= (!$isPDF)
                            ? "true" : "false";; ?> }
                },
                title: {
                    align: 'left',
                    x: 70,
                    style: {
                        fontWeight: 'bold'
                    }
                }
            });

            //SURVEY INFORMATION Chart
            $.each(contractorsName, function (i, e) {
                $('#containerSI' + i).highcharts({
                    chart: {
                        type: 'column',
                        width: <?= $chartSIWidth; ?>
                    },
                    title: {
                        text: e,
                        align: 'center',
                        x: 23,
                        style: {
                            fontSize: '14px',
                            fontWeight: 'normal'
                        }
                    },
                    xAxis: {
                        categories: <?= json_encode($surveyInformationCategories); ?>
                    },
                    yAxis: {
                        labels: {
                            format: '{value}%'
                        },
                        title: {
                            text: 'Percentage'
                        }
                    },
                    legend: {
//                      enabled: (i == 0)
                        enabled: false
                    },
                    plotOptions: {
                        column: {
                            stacking: 'percent'
                        }
                    },
                    series: surveyInformation[i]
                }, function (chart) {
                    if (i == 0) {
                        $.each(chart.series, function (j, data) {
                            if (j != (chart.series.length - 1)) {
                                $('#customLegend').append('<div class="item" style="margin-left: 30px;width:240px; display: inline-block;"><div class="symbol" style="display: inline-block;width: 16px;height: 12px;background-color:' + data.color + '"></div><span class="serieName" style="margin-left: 5px;">' + data.name + '</span></div>');
                            }
                        });
                    }
                    $('#customLegend .item').click(function () {
                        var inx = $(this).index(),
                            point = chart.series[inx];
//                            point = chart.get(inx);

                        if (point.visible)
                            point.setVisible(false);
                        else
                            point.setVisible(true);
                    });
                });
            });



            // PROJECT COMPLETED
            $('#containerPC').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: 'Projects Completed'
                },
                series: <?= json_encode($data_PC); ?>
            }, function (chart) {
                oneLegendOnly(chart);


            });

            // DOCUMENTS PROVIDED
            $('#containerDP').highcharts({
                title: {
                    text: 'Documents Provided'
                },
                series: <?= json_encode($data_DP); ?>
            }, function (chart) {
                oneLegendOnly(chart);
            });

            // TENDER PERFORMANCE PROVIDED
            $('#containerTP').highcharts({
                title: {
                    text: 'Tender Performance'
                },
                series: <?= json_encode($data_TP); ?>
            }, function (chart) {
                oneLegendOnly(chart);
            });

            // COMPLETED SURVEY PROVIDED
            $('#containerCS').highcharts({
                title: {
                    text: 'Completed Survey'
                },
                series: <?= json_encode($data_CS); ?>
            }, function (chart) {
                oneLegendOnly(chart);
            });
        });
    });


    function oneLegendOnly(chart) {

        $(chart.series).each(function (i, serie) {
            chart.renderer.text(contractorsName[i] + " Total " + serie.total, serie.center[0], chart.plotTop)
                .css({
                    fontSize: '14px',
                    textAnchor: 'middle'
                })
                .add();
        });

        $(chart.series[<?= $maxIndexCharts; ?>].data).each(function (i, e) {
            e.legendGroup.on('click', function (event) {

                event.stopPropagation();
                oneLegendOnlyClicked(e, chart);
            });

            e.legendItem.on('click', function (event) {

                event.stopPropagation();
                oneLegendOnlyClicked(e, chart);
            });
        });
    }

    function oneLegendOnlyClicked(e, chart) {
        var legendItem = e.name;
        $(chart.series).each(function (j, f) {
            $(this.data).each(function (k, z) {
                if (z.name == legendItem) {
                    if (z.visible) {
                        z.setVisible(false);
                    }
                    else {
                        z.setVisible(true);
                    }
                }
            });
        });
    }

    function columnChartLegendGroupFix(chart) {
        $(chart.series).each(function (i, e) {
            e.legendGroup.on('click', function (event) {
                event.stopPropagation();
                if (e.visible) {
                    e.setVisible(false);
                }
                else {
                    e.setVisible(true);
                }

            });
//
//            e.legendItem.on('click', function (event) {
//                if (e.visible) {
//                    e.setVisible(false);
//                }
//                else {
//                    e.setVisible(true);
//                }
//
//            });
        });
    }

    function setSpecificChart(chart, chart_id, dataArray){
        if (chart.series != null && chart.series.length != 0) {
            while (chart.series.length > 0) {
                chart.series[0].remove(false);
            }
        }
        // console.log(chart)
        // console.log(chart_id)
        // console.log(point)
        //set chart data
        // var dataArray = jQuery.parseJSON(point);
        var yTitle = dataArray[2];
        if (yTitle != 'Pie') {
            var stacking = 'normal';
            // add news
            chart.xAxis[0].setCategories(dataArray[0]);

            $.each(jQuery.parseJSON(dataArray[1]), function (idx, rec) {
                chart.addSeries(rec);
            });
        } else {

            chart.addSeries(jQuery.parseJSON(dataArray[1]));
        }
        chart.redraw();

        $('#'+chart_id).show();
    }
</script>
</html>
