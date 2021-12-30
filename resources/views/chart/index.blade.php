<style>
    #overlay {
        background: #ffffff;
        color: #666666;
        position: fixed;
        height: 100%;
        width: 100%;
        z-index: 5000;
        top: 0;
        left: 0;
        float: left;
        text-align: center;
        padding-top: 25%;
        opacity: .80;
    }
    .spinner {
        margin: 0 auto;
        height: 64px;
        width: 64px;
        animation: rotate 0.8s infinite linear;
        border: 5px solid firebrick;
        border-right-color: transparent;
        border-radius: 50%;
    }
    @keyframes rotate {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div id="overlay" style="display:none;">
    <div class="spinner"></div>
    <br/>
    Loading...
</div>
<div class="offset-top40" style="margin-bottom: -20px;">
    <div class="offset-top0">
        <div id="containerMS" class="chartContainer"></div>
        <div id="containerPfA" class="chartContainer"></div>
        <div id="containerPfA_All" class="chartContainer"></div>
        <div id="containerPT" class="chartContainer"></div>
        <div id="containerUL" class="chartContainer"></div>
        <div id="containerPD" class="chartContainer"></div>
        <div id="containerPDD" class="chartContainer"></div>
        <div id="containerCiO" class="chartContainer"></div>
        <div id="containerCiL" class="chartContainer"></div>
        <div id="containerCiL1" class="chartContainer"></div>
        <div id="containerCiL2" class="chartContainer"></div>
        <div id="containerCiL3" class="chartContainer"></div>
        <div id="containerCiL4" class="chartContainer"></div>
        <div id="containerCiI" class="chartContainer"></div>
        <div id="containerDI" class="chartContainer"></div>
        <div id="containerDPI" class="chartContainer"></div>
    </div>
</div>

@push('javascript')
    <script>
        $(document).ready(function () {
            Highcharts.setOptions({
                chart: {
                    width: 1170
                },
                xAxis: {
                    labels: {
                        style: {
                            fontWeight: 'bold'
                        }
                    }
                },
                yAxis: {

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
                        width: '220px',
                        fontWeight: 'normal'
                    },
                    align: 'right',
                    verticalAlign: 'top',
                    layout: 'vertical',
                    x: 0,
                    y: 100,
                    width: 250,
                    labelFormatter: function () {
                        return '<span class="legend-left">' + this.name + '</span><span style="top: 0; right: 0; position: absolute;">' + this.options.total + '</span>';
                    }
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
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
                    }
                },
                title: {
                    align: 'left',
                    x: 70,
                    style: {
                        fontWeight: 'bold'
                    }
                }
            });

        $('body').on('change','#chart_type, #zone', function(){
            // need to reset zone select when change chart type
            if($(this).prop('id') == 'chart_type'){
                $('#zone').val(0);
            }
            checkDisplayChildrenSelect();
            // loadChart();
        });
        $('body').on('click','#submit', function(){
            // checkDisplayChildrenSelect();
            loadChart();
        })

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
// CoS CHART
        $('#containerCoS').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Survey Programme'
            },
            yAxis: {
                title: {
                    text: 'Total properties'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });
// CoP CHART
        $('#containerCiO').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Inaccessible Overview'
            },
            yAxis: {
                title: {
                    text: 'Total records'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });
// Decommisioned Item CHART
        $('#containerDI').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Decommissioned Items'
            },
            yAxis: {
                title: {
                    text: 'Total records'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });

// Decommisioned Item CHART
        $('#containerDPI').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Decommissioned Product Type'
            },
            yAxis: {
                title: {
                    text: 'Total records'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });
// CoP CHART
        $('#containerCiL').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Inaccessible Room/locations'
            },
            yAxis: {
                title: {
                    text: 'Total locations'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });
// CiL1 CHART
        $('#containerCiL1').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Inaccessible Lofts'
            },
            yAxis: {
                title: {
                    text: 'Total locations'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });
// CiL2 CHART
        $('#containerCiL2').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Inaccessible Cupboard/voids'
            },
            yAxis: {
                title: {
                    text: 'Total locations'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });
// CiL3 CHART
        $('#containerCiL3').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Inaccessible Externals (Gardens/sides)'
            },
            yAxis: {
                title: {
                    text: 'Total locations'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });
// CiL4 CHART
        $('#containerCiL4').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Inaccessible Rooms'
            },
            yAxis: {
                title: {
                    text: 'Total locations'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });
// CoP CHART
        $('#containerCiI').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Inaccessible Items'
            },
            yAxis: {
                title: {
                    text: 'Total items'
                }
            },
            plotOptions: {
                column: {
                    stacking: 'normal'
                }
            }
        }, function (chart) {
            columnChartLegendGroupFix(chart);
        });

// PfA Chart
        $('#containerPfA_All').highcharts({
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
        $('#containerPfA').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: $("#display-property-group option:selected" ).text() + '  Re-Inspection Programme'
            },
            yAxis: {
// labels: {
//     format: '{value}%'
// },
// title: {
//     text: 'Percentage'
// }
// min: 0,
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
// column: {
//     stacking: 'normal'
// }
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

// PRODUCT TYPE
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


// DOCUMENTS DEADLINES Chart
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

        $('#chart_type').trigger('change');

    });
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
        })
    };

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
    };

    function loadChart(){
        $('.chartContainer').hide();
        var chart_type = $('#chart_type').val();
        var zone_id = $('#zone').val();
        var chart_id = $('#chart_type :selected').data('option');
        if(chart_type == 'RIP' && zone_id == 0){
            chart_id = 'containerPfA_All';
        }
        var chart = $('#'+chart_id).highcharts();

        if (chart.series != null && chart.series.length != 0) {
            while (chart.series.length > 0) {
                chart.series[0].remove(false);
            }
        }
        // if(chart_type && zone_id){
            var url = $('#form_chart').attr('action');
            $('#overlay').fadeIn();
            if(url){
                $.ajax
                ({
                    type: "POST",
                    url: url,
                    data: $('#form_chart').serialize(),
                    // dataType: "json",
                    cache: false,
                    success: function (point) {
                        $('#overlay').fadeOut();
                        var dataArray = jQuery.parseJSON(point);
                        var dataArray = jQuery.parseJSON(point);
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
                        // if(chartType == "PfA"){
                        //     chart.setTitle({
                        //         text: $("#zone option:selected" ).text() + '  Re-Inspection Programme'
                        //     });
                        //     checkCharge();
                        // }
                        chart.redraw();
                        // if(chartType == 'DI' || chartType == 'DPI'){
                        //     $('#submit-chart').show();
                        //     $('#text-waitting').hide();
                        // }
                        // call it again after one second
//                    setTimeout(requestData, 1000);
                    },
                });
                // $('#containerMS').show();
            }

        $('#'+chart_id).show();
        // }
    }

    //remove "0" value at the end of column
    function checkCharge(){
        var check = $("#containerPfA").find("text[text-anchor='start']");
        if(check.length){
            $.each(check, function(key, value){
                if($(value).is(":visible") && $(value).find('tspan').text() == '0'){
                    $(value).find('tspan').html('');
                }
            })
        }
    }

    //hide option
    function displayChildrenSelect(ids, display){
        $.each(ids, function(k,id){
            if(display){
                $('#'+id).closest('.register-form').show();
            } else {
                $('#'+id).closest('.register-form').hide();
            }
        });
    }

    function showOverView(show){
        if($('#zone').is(":visible")){
            if(show){
                $('#zone').find('option[value!=0]').show();
            } else {
                $('#zone').find('option[value!=0]').hide();
            }
        }
    }

    function checkDisplayChildrenSelect(){
        //hide all children when change chart type and show all parent dropdown
        var chart_type = $('#chart_type :selected').data('option');
        //default hide dropdown inaccessible overview
        displayChildrenSelect(['na-options-form','year-form'],false);

        if(chart_type){
            displayChildrenSelect(['zone-form'],true);
        } else {
            //hide select zone when no select any options
            displayChildrenSelect(['zone-form'],false);
        }

        showOverView(true);
        if(chart_type == 'containerCiO'){
            displayChildrenSelect(['na-options'],true);
            showOverView(false);//only overview
        } else if(chart_type == 'containerPD' || chart_type == 'containerPT' || chart_type == 'containerUL'){
            $('#zone-form').show()
        } else if(chart_type == 'containerDI' || chart_type == 'containerDPI'){
            displayChildrenSelect(['year-form'],true);
        }
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
@endpush
