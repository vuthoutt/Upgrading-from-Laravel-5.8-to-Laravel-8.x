@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'programme_equipment_detail', 'color' => 'red', 'data' =>  $equipment])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">Pre-planned Maintenance</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._system_button', [
            'backRoute' =>  route('shineCompliance.property.equipment',['property_id' => $equipment->property_id ?? 0]),
            'route_decommission' =>  route('shineCompliance.equipment.decommission',['id' => $equipment->id ?? 0]),
            'route_recommission' =>  route('shineCompliance.equipment.decommission',['id' => $equipment->id ?? 0]),
            'decommission' => $equipment->decommissioned ?? 0,
            'editRoute'  => $can_update ? route('shineCompliance.equipment.get_edit_equipment',['id' => $equipment->id ?? 0]) : false
            ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_system_programme_sidebar',
            ['image' =>  asset(\ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO)),
            'id' => $equipment->id ?? 0,
            'route' => 'shineCompliance.register_equipment.detail',
            'route_document' => route('shineCompliance.equipment.document.list', ['id'=>$equipment->id ?? 0, 'type'=> DOCUMENT_EQUIPMENT_TYPE]),
            'display_type' => EQUIPMENT_TYPE
            ])
            <div class="col-md-9">
                <div  class="card-data mar-up">
                    <!-- Compliance chart -->
                    <div class="card card-img card-img-deco" style="width:1200px; height: 400px;">
{{--                        <img src="{{asset('img/reinspection_programme_static.png')}}"/>--}}
                        <input type="hidden" class="chart_type" value="PRP">
{{--                        <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">--}}
{{--                            <div style="width: 100px; float:right;">--}}
{{--                                <select id="select1" name="compliance_select" class="form-control sl-dropdown">--}}
{{--                                    <option value="overview">Overview</option>--}}
{{--                                    @foreach($active['active_field'] as $dataRow)--}}
{{--                                        @if($equipment->getActiveTextAttribute($dataRow))--}}
{{--                                            <option value="{{$dataRow}}">{{$equipment->getActiveTextAttribute($dataRow)}}</option>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div id="reinspectionChart" class="chart_element" style="position: relative"></div>
                    </div>
                </div>
                @include('shineCompliance.tables.equipment_pre_planned_maintenance', [
                    'title' => 'Pre-planned Maintenance',
                    'tableId' => 'pre_planned_maintenance_equipment',
                    'over_all_text' => '',
                    'collapsed' => false,
                    'plus_link' => false,
                    'data' => $programmes,
                    'order_table' => "[]"
                    ])
            </div>

        </div>
    </div>
</div>
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            var data_reinspection_chart = '{!! $data_reinspection_chart->toJson() !!}';
            console.log(data_reinspection_chart);
            data_reinspection_chart = $.parseJSON(data_reinspection_chart);
            Highcharts.setOptions({
                chart: {
                    width: 1000
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
            $('#reinspectionChart').highcharts({
                chart: {
                    type: 'bar'
                },
                title: {
                    text: ''
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
                                str = getExtraDate(point.series.userOptions.data[index]);
                            }
                        });
                        return result  + str + ' </span>';
                    },
                    shared: true
                },
            }, function (chart) {
                initChart(chart, data_reinspection_chart, 'reinspectionChart')
            });
        });

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

        function initChart(chart, data_chart){
            console.log(chart.xAxis[0], data_chart[1], data_chart[0])
            chart.xAxis[0].setCategories(data_chart[0]);
            $.each(data_chart[1], function (idx, rec) {
                chart.addSeries(rec);
            });
            chart.redraw();
        }
    </script>
@endpush
