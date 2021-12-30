
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
                     src="{{ CommonHelpers::getFile(1, CLIENT_LOGO, $isPDF) }}"/>
            </td>
            <td>
                <span class="spanMiddle"><strong><?= $titleSummary; ?></strong></span>
            </td>
        </tr>
    </table>
    <input type="hidden" id="summary_type" value="{{$summary_type}}" />
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

<script src="{{$isPDF ? \CommonHelpers::getAssetFile('js/jquery_kpi.min.js') : asset('js/jquery_kpi.min.js') }}"></script>
<script src="{{$isPDF ? \CommonHelpers::getAssetFile('js/highcharts.js') : asset('js/highcharts.js') }}"></script>
<script>
        $(document).ready(function () {
            Highcharts.setOptions({
//                global: {
//                    //Highchart uses UTC time by default
//                    useUTC: false
//                },
                chart: {
                    width: 1100
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
            // console.log(Highcharts)
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
                    type: 'column'
                },
                title: {
                    text: 'Re-Inspection Programme'
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

            $('#containerPD').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Project Deadlines'
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

            $('#containerPDD').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Project Document Deadlines'
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

            // USER LOGIN
            $('#containerUL').highcharts({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: 'User Login'
                }
            }, function (chart) {
                oneLegendOnly(chart);
            });

            // Project Sponsor
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

            // Project DEADLINES Chart
            $('#containerPD').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Project Deadlines'
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

            // Project DOCUMENTS DEADLINES Chart
            $('#containerPDD').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Project Document Deadlines'
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

        // set chart data
        setChartData();
    });

    function setChartData(){
        var chart_type = $('#summary_type').val();
        //hide all chart
        $('.chartContainer').hide();
        if(chart_type){
            // Manager Overview chart has two more charts i.e containerPD and containerPDD
            var chart_ms    = $('#containerMS').highcharts();
            var dataArray = <?php echo json_encode($data_MS, JSON_PRETTY_PRINT); ?>;
            setSpecificChart(chart_ms, 'containerMS', dataArray);
            //for reinspection chart
            var chart_rip    = $('#containerPfA').highcharts();
            var dataArrayRip = <?php echo json_encode($data_RIP, JSON_PRETTY_PRINT); ?>;
            setSpecificChart(chart_rip, 'containerPfA', dataArrayRip);

            //for Product debris type chart
            var chart_pd    = $('#containerPT').highcharts();
            var dataArrayPD = <?php echo json_encode($data_PD, JSON_PRETTY_PRINT); ?>;
            setSpecificChart(chart_pd, 'containerPT', dataArrayPD);

            //for User Login chart
            var chart_ul    = $('#containerUL').highcharts();
            var dataArrayUL = <?php echo json_encode($data_UL, JSON_PRETTY_PRINT); ?>;
            setSpecificChart(chart_ul, 'containerUL', dataArrayUL);

            //for project sponsor
            var chart_ps    = $('#containerPS').highcharts();
            var dataArrayPS = <?php echo json_encode($data_PS, JSON_PRETTY_PRINT); ?>;
            setSpecificChart(chart_ps, 'containerPS', dataArrayPS);

            //set data for manager overview chart
            if(chart_type != 'directorOverview'){
                //for project deadline chart
                var chart_pdc    = $('#containerPD').highcharts();
                var dataArrayPDC = <?php echo json_encode($data_PDC, JSON_PRETTY_PRINT); ?>;
                setSpecificChart(chart_pdc, 'containerPD', dataArrayPDC);

                //for project document deadline chart
                var chart_pddc    = $('#containerPDD').highcharts();
                var dataArrayPDDC = <?php echo json_encode($data_PDDC, JSON_PRETTY_PRINT); ?>;
                setSpecificChart(chart_pddc, 'containerPDD', dataArrayPDDC);
            }
        }
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

    function oneLegendOnly(chart) {
        if (chart.series != null && chart.series.length != 0) {
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
            // console.log(numberOfDaysToAdd);
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
