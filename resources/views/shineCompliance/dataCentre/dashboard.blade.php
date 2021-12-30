@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' =>'data_center', 'color' => 'red'])
<div class="container prism-content pad-up">
    <div class="row">
        <h3 style="margin: 0">Data Centre</h3>
    </div>

    <div class="row">
        @include('shineCompliance.dataCentre.partials._data_centre_sidebar')

        <div class="col-md-9 pl-0" style="padding: 0">
            <div  class="card-data mar-up">
                <!-- Compliance chart -->
                <div class="card card-img card-img-deco" style="width:600px; height: 300px;">
                    <input type="hidden" class="chart_type" value="compliance_chart">
                    <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                        <div style="width: 100px; float:right;">
                            <select id="select1" name="compliance_select" class="form-control sl-dropdown">
                                <option value="overview">Overview</option>
                                <option value="asbestos">Asbestos</option>
                                <option value="fire">Fire</option>
                                @if(env('WATER_MODULE'))
                                <option value="water">Water</option>
                                @endif
                                <option value="hs">H&S</option>
                                {{-- <option value="me">M&E</option> --}}
                            </select>
                        </div>
                    </div>
                    <div id="complianceChart" class="chart_element" style="position: relative"></div>
                </div>
                <!-- Accessibility chart -->
                <div class="card card-img card-img-deco" style="width:600px; height: 300px;">
                    <input type="hidden" class="chart_type" value="accessibility_chart">
                    <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                        <div style="width: 100px; float:right;">
                            <select id="select2" name="accessibility_select" class="form-control sl-dropdown">
                                <option value="overview">Overview</option>
                                <option value="acm">ACM</option>
                                <option value="area_floor">Area/floor</option>
                                <option value="assembly_point">Assembly Point</option>
                                <option value="fire_exit">Fire Exit</option>
                                <option value="equipment">Equipment</option>
                                <option value="room_location">Room/location</option>
                                <option value="vehicle_parking">Vehicle Parking</option>
                                <option value="voids">Voids</option>
                            </select>
                        </div>
                    </div>
                    <div id="accessibilityChart" class="chart_element" style="position: relative"></div>
                </div>
            </div>

            <div  class="card-data mar-up">
                <!-- Risk chart -->
                <div class="card card-img card-img-deco" style="width:600px;  height: 300px;">
                    <input type="hidden" class="chart_type" value="risk_chart">
                    <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                        <div style="width: 100px; float:right;">
                            <select id="select3" name="risk_select" class="form-control sl-dropdown">
                                <option value="overview">Overview</option>
                                <option value="asbestos">Asbestos</option>
                                <option value="fire">Fire</option>
                                @if(env('WATER_MODULE'))
                                <option value="water">Water</option>
                                @endif
                                <option value="hs">H&S</option>
                            </select>
                        </div>
                    </div>
                    <div id="riskChart" class="chart_element" style="position: relative"></div>
                </div>
                <!-- Action/recommendation chart -->
                <div class="card card-img card-img-deco" style="width:600px; height: 300px;">
                    <input type="hidden" class="chart_type" value="action_recommendation_chart">
                    <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                        <div style="width: 100px; float:right;">
                            <select id="select4" name="action_recommendation_select" class="form-control sl-dropdown">
                                <option value="overview">Overview</option>
                                <option value="asbestos">Asbestos</option>
                                <option value="fire">Fire</option>
                                @if(env('WATER_MODULE'))
                                <option value="water">Water</option>
                                @endif
                                <option value="hs">H&S</option>
                            </select>
                        </div>
                    </div>
                    <div id="actionRecommendationChart" class="chart_element" style="position: relative"></div>
                </div>
            </div>

            <div  class="card-data mar-up">
                <!-- Re-inspection chart -->
                <div class="card card-img card-img-deco" style="width:600px;  height: 300px;">
                    <input type="hidden" class="chart_type" value="reinspection_chart">
                    <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                        <div style="width: 100px; float:right;">
                            <select id="select5" name="reinspection_select" class="form-control sl-dropdown">
                                <option value="overview">Overview</option>
                                <option value="asbestos">Asbestos</option>
                                <option value="fire">Fire</option>
                                @if(env('WATER_MODULE'))
                                <option value="water">Water</option>
                                @endif
                                <option value="hs">H&S</option>
                            </select>
                        </div>
                    </div>
                    <div id="reinspectionChart" class="chart_element" style="position: relative"></div>
                </div>
                <!-- Pre-Planned chart -->
                <div class="card card-img card-img-deco" style="width:600px; height: 300px;">
                    <input type="hidden" class="chart_type" value="pre_planned_chart">
                    <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                        <div style="width: 100px; float:right;">
                            <select id="select6" name="pre_planned_select" class="form-control sl-dropdown">
                                <option value="overview">Overview</option>
                            </select>
                        </div>
                    </div>
                    <div id="prePlanchart" class="chart_element" style="position: relative"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
<script>
    $(document).ready(function(){
        var data_accessibility_chart = '{!! $data_accessibility_chart->toJson() !!}';
        data_accessibility_chart = $.parseJSON(data_accessibility_chart);
        var data_risk_chart = '{!! $data_risk_chart->toJson() !!}';
        data_risk_chart = $.parseJSON(data_risk_chart);
        var data_action_recommendation_chart = '{!! $data_action_recommendation_chart->toJson() !!}';
        data_action_recommendation_chart = $.parseJSON(data_action_recommendation_chart);
        var data_reinspection = '{!! $data_reinspection->toJson() !!}';
        data_reinspection = $.parseJSON(data_reinspection);
        var data_pre_planned = '{!! $data_pre_planned->toJson() !!}';
        data_pre_planned = $.parseJSON(data_pre_planned);
        var data_compliant = '{!! $data_duty_manage_chart->toJson() !!}';
        data_compliant = $.parseJSON(data_compliant);

        var compliance_chart = $('#complianceChart').highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: 'Duty to Manage Compliance',
                align: 'left',
                style: {'font-weight': 'bold', 'padding-left': '15px', 'color': '#5e5f61', 'font-size': '13.5px'}
            },
            chart: {
                height: 280,
                width: 540,
                marginRight: 300,
                animation: false,
                events: {
                    load: function() {
                        var chart = this,
                            offsetLeft = -25,
                            offsetTop = 10,
                            x = chart.plotLeft + chart.plotWidth / 2 + offsetLeft,
                            y = chart.plotTop + chart.plotHeight / 2 + offsetTop,
                            value = 0;
                    }
                }
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false,
                        color: '#fff',
                        format: '{point.y}',
                        distance: -25,
                        style: {
                            fontSize: '10px',
                            textOutline: 0
                        },
                    },
                    animation: false,
                    showInLegend: true,
                    innerSize: '80%',
                },
            },
            legend: {
                layout: 'vertical',
                verticalAlign: 'middle',
                align: 'right',
                symbolHeight: 15,
                symbolRadius: 0,
                symbolPadding: 5,
                itemMarginTop: 5,
                itemMarginBottom: 5,
                x: -45,
                y: 15,
                itemStyle: {
                    fontSize: '10px'
                }
            },
            navigation: {
                buttonOptions: {
                    enabled: true
                }
            },
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ["viewFullscreen", "printChart", "separator", "downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG"],
                        x: -20,
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
                pointFormat: '<b>Total</b> {point.y} ({point.percentage:.0f}%)<br/>',
                shared: true
            }
        }, function (chart) {
            initChart(chart, data_compliant, 'complianceChart', 'percent')
        });

        $('#accessibilityChart').highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: 'Accessibility',
                align: 'left',
                style: {'font-weight': 'bold', 'padding-left': '15px', 'color': '#5e5f61', 'font-size': '13.5px'}
            },
            chart: {
                height: 280,
                width: 540,
                marginRight: 300,
                animation: false,
                events: {
                    load: function() {
                        var chart = this,
                            offsetLeft = -25,
                            offsetTop = 10,
                            x = chart.plotLeft + chart.plotWidth / 2 + offsetLeft,
                            y = chart.plotTop + chart.plotHeight / 2 + offsetTop,
                            value = 0;
                    }
                }
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false,
                        color: '#fff',
                        format: '{point.y}',
                        distance: -25,
                        style: {
                            fontSize: '10px',
                            textOutline: 0
                        },
                    },
                    animation: false,
                    showInLegend: true,
                    innerSize: '80%',
                },
            },
            legend: {
                layout: 'vertical',
                verticalAlign: 'middle',
                align: 'right',
                symbolHeight: 15,
                symbolRadius: 0,
                symbolPadding: 5,
                itemMarginTop: 5,
                itemMarginBottom: 5,
                x: -118,
                y: 25,
                itemStyle: {
                    fontSize: '10px'
                }
            },
            navigation: {
                buttonOptions: {
                    enabled: true
                }
            },
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ["viewFullscreen", "printChart", "separator", "downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG"],
                        x: -20,
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
                pointFormat: '<b>Total</b> {point.y} ({point.percentage:.0f}%)<br/>',
                shared: true
            },
        }, function (chart) {
            initChart(chart, data_accessibility_chart, 'accessibilityChart', 'percent')
        });

        $('#riskChart').highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: 'Risks',
                align: 'left',
                style: {'font-weight': 'bold', 'padding-left': '15px', 'color': '#5e5f61', 'font-size': '13.5px'}
            },
            chart: {
                height: 280,
                width: 540,
                marginRight: 300,
                animation: false,
                events: {
                    load: function() {
                        var chart = this,
                            offsetLeft = -25,
                            offsetTop = 10,
                            x = chart.plotLeft + chart.plotWidth / 2 + offsetLeft,
                            y = chart.plotTop + chart.plotHeight / 2 + offsetTop,
                            value = 0;
                    }
                }
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false,
                        color: '#fff',
                        format: '{point.y}',
                        distance: -25,
                        style: {
                            fontSize: '10px',
                            textOutline: 0
                        },
                    },
                    animation: false,
                    showInLegend: true,
                    innerSize: '80%',
                },
            },
            legend: {
                layout: 'vertical',
                verticalAlign: 'middle',
                align: 'right',
                symbolHeight: 15,
                symbolRadius: 0,
                symbolPadding: 5,
                itemMarginTop: 5,
                itemMarginBottom: 5,
                x: -45,
                y: 25,
                itemStyle: {
                    fontSize: '10px'
                }
            },
            navigation: {
                buttonOptions: {
                    enabled: true
                }
            },
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ["viewFullscreen", "printChart", "separator", "downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG"],
                        x: -20,
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
                pointFormat: '<b>Total</b> {point.y} ({point.percentage:.0f}%)<br/>',
                shared: true
            }
        }, function (chart) {
            initChart(chart, data_risk_chart, 'riskChart', 'total')
        });

        $('#actionRecommendationChart').highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: 'Action/recommendations',
                align: 'left',
                style: {'font-weight': 'bold', 'padding-left': '15px', 'color': '#5e5f61', 'font-size': '13.5px'}
            },
            chart: {
                height: 280,
                width: 540,
                marginRight: 300,
                animation: false,
                events: {
                    load: function() {
                        var chart = this,
                            offsetLeft = -25,
                            offsetTop = 10,
                            x = chart.plotLeft + chart.plotWidth / 2 + offsetLeft,
                            y = chart.plotTop + chart.plotHeight / 2 + offsetTop,
                            value = 0;
                    }
                }
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false,
                        color: '#fff',
                        format: '{point.y}',
                        distance: -25,
                        style: {
                            fontSize: '10px',
                            textOutline: 0
                        },
                    },
                    animation: false,
                    showInLegend: true,
                    innerSize: '80%',
                },
            },
            legend: {
                layout: 'vertical',
                verticalAlign: 'middle',
                align: 'right',
                symbolHeight: 15,
                symbolRadius: 0,
                symbolPadding: 5,
                itemMarginTop: 5,
                itemMarginBottom: 5,
                x: -25,
                y: 25,
                itemStyle: {
                    fontSize: '10px'
                }
            },
            navigation: {
                buttonOptions: {
                    enabled: true
                }
            },
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ["viewFullscreen", "printChart", "separator", "downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG"],
                        x: -20,
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
                pointFormat: '<b>Total</b> {point.y} ({point.percentage:.0f}%)<br/>',
                shared: true
            },
        }, function (chart) {
            initChart(chart, data_action_recommendation_chart, 'actionRecommendationChart', 'total');
        });

        $('#reinspectionChart').highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: 'Re-Inspection Programme',
                align: 'left',
                style: {'font-weight': 'bold', 'padding-left': '15px', 'color': '#5e5f61', 'font-size': '13.5px'}
            },
            chart: {
                height: 280,
                width: 540,
                marginRight: 300,
                animation: false,
                events: {
                    load: function() {
                        var chart = this,
                            offsetLeft = -25,
                            offsetTop = 10,
                            x = chart.plotLeft + chart.plotWidth / 2 + offsetLeft,
                            y = chart.plotTop + chart.plotHeight / 2 + offsetTop,
                            value = 0;
                    }
                }
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false,
                        color: '#fff',
                        format: '{point.y}',
                        distance: -25,
                        style: {
                            fontSize: '10px',
                            textOutline: 0
                        },
                    },
                    animation: false,
                    showInLegend: true,
                    innerSize: '80%',
                },
            },
            legend: {
                layout: 'vertical',
                verticalAlign: 'middle',
                align: 'right',
                symbolHeight: 15,
                symbolRadius: 0,
                symbolPadding: 5,
                itemMarginTop: 5,
                itemMarginBottom: 5,
                x: -25,
                y: 25,
                itemStyle: {
                    fontSize: '10px'
                }
            },
            navigation: {
                buttonOptions: {
                    enabled: true
                }
            },
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ["viewFullscreen", "printChart", "separator", "downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG"],
                        x: -20,
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
                pointFormat: '<b>Total</b> {point.y} ({point.percentage:.0f}%)<br/>',
                shared: true
            },
        }, function (chart) {
            initChart(chart, data_reinspection, 'reinspectionChart', 'total');
        });

        $('#prePlanchart').highcharts({
            credits: {
                enabled: false
            },
            title: {
                text: 'Pre-Planned Maintenance Schedule',
                align: 'left',
                style: {'font-weight': 'bold', 'padding-left': '15px', 'color': '#5e5f61', 'font-size': '13.5px'}
            },
            chart: {
                height: 280,
                width: 540,
                marginRight: 300,
                animation: false,
                events: {
                    load: function() {
                        var chart = this,
                            offsetLeft = -25,
                            offsetTop = 10,
                            x = chart.plotLeft + chart.plotWidth / 2 + offsetLeft,
                            y = chart.plotTop + chart.plotHeight / 2 + offsetTop,
                            value = 0;
                    }
                }
            },
            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: false,
                        color: '#fff',
                        format: '{point.y}',
                        distance: -25,
                        style: {
                            fontSize: '10px',
                            textOutline: 0
                        },
                    },
                    animation: false,
                    showInLegend: true,
                    innerSize: '80%',
                },
            },
            legend: {
                layout: 'vertical',
                verticalAlign: 'middle',
                align: 'right',
                symbolHeight: 15,
                symbolRadius: 0,
                symbolPadding: 5,
                itemMarginTop: 5,
                itemMarginBottom: 5,
                x: -80,
                y: 25,
                itemStyle: {
                    fontSize: '10px'
                }
            },
            navigation: {
                buttonOptions: {
                    enabled: true
                }
            },
            exporting: {
                buttons: {
                    contextButton: {
                        menuItems: ["viewFullscreen", "printChart", "separator", "downloadPNG", "downloadJPEG", "downloadPDF", "downloadSVG"],
                        x: -20,
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
                pointFormat: '<b>Total</b> {point.y} ({point.percentage:.0f}%)<br/>',
                shared: true
            },
        }, function (chart) {
            initChart(chart, data_pre_planned, 'prePlanchart', 'total');
        });

    });

    $('body').on('change', '.sl-dropdown', function(){
        var option = $(this).val();
        var chart_name = $(this).closest('.card').find('.chart_type').val();
        var chart_id = $(this).closest('.card').find('.chart_element').attr('id');
        $('#'+chart_id).hide()
        var chart = $('#'+chart_id).highcharts();
        if (chart.series != null && chart.series.length != 0) {
            while (chart.series.length > 0) {
                chart.series[0].remove(false);
            }
        }
        $("#rendered_text"+chart_id).remove();
        $('#overlay').fadeIn();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax
        ({
            type: "POST",
            url: '{{route('shineCompliance.chart.generate')}}',
            data: {chart_name : chart_name, option : option},
            cache: false,
            success: function (point) {
                var displayType = '';
                if(chart_id == 'accessibilityChart' || chart_id == 'complianceChart'){
                    displayType = 'percent';
                } else {
                    displayType = 'total';
                }
                $('#overlay').fadeOut();
                initChart(chart, point, chart_id, displayType);
                chart.redraw();
            },
        });
        $('#'+chart_id).show();
    });

    function initChart(chart, data_chart, chart_id, displayType){
        var yTitle = data_chart[2];
        if (yTitle != 'Pie') {

        } else {
            console.log(chart, data_chart);
            // console.log(data_chart);
            chart.addSeries(data_chart[1]);
            var offsetLeft = 0,
                offsetTop = 10,
                x = chart.plotLeft + chart.plotWidth / 2 + offsetLeft,
                y = chart.plotTop + chart.plotHeight / 2 + offsetTop;

            var total = 0, partValue = 0;

            data_chart[1]['data'].forEach(function(i, idx, array) {
                if(idx === array.length - 1) {
                    total = i.total;
                }
                if (idx === 1) {
                    partValue = i.total;
                }
            });

            var displayText = '';
            if (displayType == 'total') {
                displayText = total;
            } else if (displayType == 'percent') {
                displayText = Math.round((partValue / total) * 100) + '%';
            }

            chart.renderer.text(displayText, x, y).add().css({
                fontSize: '25px'
            }).attr({
                align: 'center',
                id :'rendered_text'+chart_id
            }).toFront();
        }
        // chart.redraw();
    }
</script>
@endpush
