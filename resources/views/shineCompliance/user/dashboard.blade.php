@extends('shineCompliance.layouts.app')

@section('content')
    @if( request()->route()->getName() == 'shineCompliance.home_shineCompliance')
        @include('shineCompliance.partials.nav',['breadCrumb' => 'home_shineCompliance', 'color' => 'red'])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'profile_shineCompliance', 'color' => 'red', 'data' =>$data ])
    @endif

<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $data->full_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        <div class="row mt-4">
            @include('shineCompliance.user.partials._data_centre_sidebar')
            <div class="col-md-9 pl-0" style="padding: 0">
                <div class="card-data mar-up">
                    <!-- Re-inspection chart -->
                    <div class="card card-img card-img-deco" style="width:600px;  height: 300px;">
                        <input type="hidden" class="chart_type" value="document_management_chart">
                        <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                            <div style="width: 100px; float:right;">
                                <select id="select5" name="document_management_select" class="form-control sl-dropdown">
                                    <option value="overview">Overview</option>
                                    <option value="project_documents">Project Documents</option>
                                    <option value="assessments">Assessments</option>
                                    <option value="certificates">Certificates</option>
                                    <option value="incident_reports">Incident Reports</option>
                                    <option value="surveys">Surveys</option>
                                    <option value="work_requests">Work Requests</option>
                                </select>
                            </div>
                        </div>
                        <div id="documentManagementChart" class="chart_element" style="position: relative"></div>
                    </div>
                    <!-- Pre-Planned chart -->
                    <div class="card card-img card-img-deco" style="width:600px; height: 300px;">
                        <input type="hidden" class="chart_type" value="quality_assurance_chart">
                        <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                            <div style="width: 100px; float:right;">
                                <select id="select6" name="quality_assurance_select" class="form-control sl-dropdown">
                                    <option value="overview">Overview</option>
                                    <option value="project_documents">Project Documents</option>
                                    <option value="assessments">Assessments</option>
                                    <option value="certificates">Certificates</option>
                                    <option value="incident_reports">Incident Reports</option>
                                    <option value="surveys">Surveys</option>
                                    <option value="work_requests">Work Requests</option>
                                </select>
                            </div>
                        </div>
                        <div id="qualityAssuranceChart" class="chart_element" style="position: relative"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            var data_document_management = '{!! $data_document_management->toJson() !!}';
            data_document_management = $.parseJSON(data_document_management);
            var data_quality_assurance = '{!! $data_quality_assurance->toJson() !!}';
            data_quality_assurance = $.parseJSON(data_quality_assurance);

            $('#documentManagementChart').highcharts({
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Document Management',
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
                initChart(chart, data_document_management, 'documentManagementChart', 'total')
            });

            $('#qualityAssuranceChart').highcharts({
                credits: {
                    enabled: false
                },
                title: {
                    text: 'Quality Assurance',
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
                initChart(chart, data_quality_assurance, 'qualityAssuranceChart', 'total')
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
                    var displayType = 'total';
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
                // console.log(chart, data_chart);
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
                    if (idx === 0) {
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
