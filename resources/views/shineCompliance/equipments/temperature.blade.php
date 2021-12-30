@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'temperature_equipment_detail', 'color' => 'red', 'data' =>  $equipment])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">Temperature & PH</h3>
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
                @if(isset($active['active_field']) and count($active['active_field']) > 0)
                    <div  class="card-data mar-up">
                        <!-- Compliance chart -->
                        <div class="card card-img card-img-deco" style="width:1200px; height: 400px;">
                            <input type="hidden" class="chart_type" value="EQR">
                            <div style="margin-top: 5px; width: 480px; display: inline-block; position: absolute; z-index: 999">
                                <div style="width: 100px; float:right;">
                                    <select id="select1" name="compliance_select" class="form-control sl-dropdown">
                                        <option value="overview">Overview</option>
                                        @foreach($active['active_field'] as $dataRow)
                                            @if($dataRow != 'ph' && $equipment->getActiveTextAttribute($dataRow))
                                                <option value="{{$dataRow}}">{{$equipment->getActiveTextAttribute($dataRow)}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="tempChart" class="chart_element" style="position: relative"></div>
                        </div>
                    </div>
                @endif


                @include('shineCompliance.tables.equipment_temperature_log', [
                    'title' => 'Temperature & PH',
                    'tableId' => 'temperature_equipment',
                    'over_all_text' => '',
                    'collapsed' => false,
                    'plus_link' => false,
                    'data' => $active,
                    'equipment' => $equipment,
                    'notCountable' => true,
                    'normalTable' => false,
                    'order_table' => "[]"
                    ])
            </div>
            <!-- History Modal -->
            <div class="parent-temperature-history-modal">
                <div class="modal fade" id="temperature-history-listing-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header red_gradient">
                                <h5 class="modal-title" id="exampleModalLabel">Temperature History</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="padding-left: 35px">

                                <table id="temperature-history-dt-table" class="display" style="border: 1px solid #e5e5e5 !important;padding: 10px;border-radius: 4px;width: 100%;">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Comment</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class="mb-4" style="text-align: center;">
                                <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="parent-edit-temperature-modal">
                <div class="modal fade" id="edit-temperature-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content"  style="width: 550px!important;">
                            <div class="modal-header red_gradient">
                                <h5 class="modal-title" id="exampleModalLabel2">Temperature Update</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <div class="modal-body" style="padding-left: 35px">
                                    <div class="offset-top40">
                                        <form action="{{route('shineCompliance.equipment.post_update_temperature')}}" method="POST" id="form-update-temperature">
                                        @csrf
                                        <input type="hidden" name="active_key" id="active_key" value="" />
                                        <input type="hidden" name="equipment_id" id="equipment_id"  value="{{$equipment->id ?? 0}}" />
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Flow Temperature:', 'name' => 'flow_temp', 'data' => $equipment->tempAndPh->flow_temp ?? '','measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Inlet Temperature:', 'name' => 'inlet_temp', 'data' => $equipment->tempAndPh->inlet_temp ?? '','measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Stored Temperature:', 'name' => 'stored_temp', 'data' => $equipment->tempAndPh->stored_temp ?? '','measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Return Temperature:', 'name' => 'return_temp', 'data' =>$equipment->tempAndPh->return_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Top Temperature:', 'name' => 'top_temp', 'data' => $equipment->tempAndPh->top_temp ?? '','measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Bottom Temperature:', 'name' => 'bottom_temp', 'data' => $equipment->tempAndPh->bottom_temp ?? '','measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Flow Temperature Gauge:', 'name' => 'flow_temp_gauge_value', 'data' => $equipment->tempAndPh->flow_temp_gauge_value ?? '','measurement' => '°C', 'class_other' => 'justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Return Temperature Gauge:', 'name' => 'return_temp_gauge_value', 'data' => $equipment->tempAndPh->return_temp_gauge_value ?? '','measurement' => '°C', 'class_other' => 'justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Ambient Area Temperature:', 'name' => 'ambient_area_temp', 'data' => $equipment->tempAndPh->ambient_area_temp ?? '','measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Incoming Main Pipework Surface Temperature:', 'name' => 'incoming_main_pipe_work_temp', 'data' => $equipment->tempAndPh->incoming_main_pipe_work_temp ?? '','measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])

                                        @include('shineCompliance.forms.form_input_small',['title' => 'Hot Flow Temperature:', 'name' => 'hot_flow_temp', 'data' => $equipment->tempAndPh->hot_flow_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 5])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Cold Flow Temperature:', 'name' => 'cold_flow_temp', 'data' => $equipment->tempAndPh->cold_flow_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 5])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Pre-TMV Cold Flow Temperature:', 'name' => 'pre_tmv_cold_flow_temp', 'data' => $equipment->tempAndPh->pre_tmv_cold_flow_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 5])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Pre-TMV Hot Flow Temperature:', 'name' => 'pre_tmv_hot_flow_temp', 'data' => $equipment->tempAndPh->pre_tmv_hot_flow_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 5])
                                        @include('shineCompliance.forms.form_input_small',['title' => 'Post-TMV Temperature:', 'name' => 'post_tmv_temp', 'data' => $equipment->tempAndPh->post_tmv_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 5])

                                        @include('shineCompliance.forms.form_input_small',['title' => 'pH:', 'name' => 'ph', 'data' => $equipment->tempAndPh->ph ?? '','measurement' => 'pH','class_other' => 'equipment_section justify-content-center','width' => '3', 'width_label' => 4])
                                        </form>
                                    </div>
                                </div>
                                <div class="mb-4" style="text-align: center;">
                                    <button type="button" class="btn light_grey_gradient" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn light_grey_gradient" id="save-temperature">Save</button>
                                </div>
                        </div>
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
            <!-- start chart -->
            var data_temperature_chart = '{!! $data_temperature_chart->toJson() !!}';
            // console.log(data_temperature_chart);
            data_temperature_chart = $.parseJSON(data_temperature_chart);
            HighchartsStock.setOptions({
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
                    'enabled' : true,
                    'text' : '°C',
                    stackLabels: {
                        enabled: true,
                        style: {
                            fontWeight: 'bold',
                            color: (HighchartsStock.theme && HighchartsStock.theme.textColor) || 'gray'
                        }
                    },
                    labels: {
                        formatter: function() {
                            return this.value+"°C";
                        }
                    },
                },
                plotOptions: {
                    series: {
                        marker: {
                            enabled: true,
                            radius: 5,
                            // fillColor: '#00ff00',
                            // {
                            // formatter: function () {
                            //     "#00ff00"
                            //         }
                            //     }
                        }
                    }
                },
                tooltip: {
                    valueDecimals: 2,
                    useHTML: true,
                    formatter: function () {
                        var s = "<div class ='tooltipContainer'>";
                        if(this.x > 0){
                            s += '<b>' + Highcharts.dateFormat('%A, %d/%m/%Y', this.x) + '</b>';
                            var x_data = this.x;
                            $.each(this.points, function (i, point) {
                                // console.log(point, x_data);
                                $.each(point.series.userOptions.other, function (k,v){
                                    // console.log(k, v);
                                    //timestamp * 1000 for js
                                    if(k*1000 == x_data){
                                        $.each(v, function(k2, v2){
                                            s += '<br/>' + v2;
                                        })
                                    }
                                });
                                return false;

                            });
                            return s;
                        } else {
                            return '';
                        }
                    },
                    // backgroundColor: 'rgba(0,0,0, 0.6)',
                    // borderWidth: 0,
                    // shadow: false,
                    // style: {
                    //     padding: 0,
                    //     color: '#fff',
                    //     fontSize: '8pt'
                    // }
                },
                scrollbar: {
                    enabled: false
                },

                legend: {
                    enabled: true,
                    align: 'top',
                    backgroundColor: '#FCFFC5',
                    borderColor: 'black',
                    borderWidth: 2,
                    layout: 'vertical',
                    // layout: 'proximate',
                    verticalAlign: 'top',
                    // y: -200,
                    shadow: true,
                    maxHeight: 200,
                },
                credits: {
                    enabled: false
                },
                // tooltip: {
                //     useHTML: true,
                //     backgroundColor: {
                //         linearGradient: [0, 0, 0, 60],
                //         stops: [
                //             [0, '#FFFFFF'],
                //             [1, '#E0E0E0']
                //         ]
                //     },
                //     borderColor: '#000000',
                //     headerFormat: '<strong>{point.key}</strong><br/>',
                //     pointFormat: '<span style="color:{series.color}; text-shadow: 1px 1px 1px rgba(0,0,0,0.3);">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>',
                //     shared: true
                // },
                // legend: {
                //     useHTML: true,
                //     itemStyle: {
                //         width: '220px',
                //         fontWeight: 'normal'
                //     },
                //     align: 'right',
                //     verticalAlign: 'top',
                //     layout: 'vertical',
                //     x: 0,
                //     y: 100,
                //     width: 250,
                //     labelFormatter: function () {
                //         return '<span class="legend-left">' + this.name + '</span><span style="top: 0; right: 0; position: absolute;">' + this.options.total + '</span>';
                //     }
                // },
                rangeSelector: {
                    // selected: 1,
                    enabled: true
                },
                // navigator:{
                //   enabled:false
                // },
                exporting: {
                    enabled: false
                }
            });

            /**
             * Create the chart when all data is loaded
             * @returns {undefined}
             */
            temp_chart = HighchartsStock.stockChart('tempChart', {

                chart: {
                    events: {
                        redraw: function () {
                            console.log('The chart is being redrawn', 1);
                        },
                        load: function () {
                        }
                    }
                },
                legend: {
                    enabled: true,
                    align: 'right',
                    backgroundColor: '#FCFFC5',
                    borderColor: 'black',
                    borderWidth: 2,
                    layout: 'vertical',
                    verticalAlign: 'top',
                    y: 100,
                    shadow: true
                },

                // series: [{
                //     name: '',
                //     data: [
                //         null
                //         // [1592544455,18],[1592600855,0]
                //     ]
                // }]
            }, function (chart) {
                initChart(chart, data_temperature_chart, 'tempChart', 'percent')
            });


            $('body').on('change', '.sl-dropdown', function(){
                var option = $(this).val();
                var chart_name = $(this).closest('.card').find('.chart_type').val();
                var chart_id = $(this).closest('.card').find('.chart_element').attr('id');
                var equipment_id = $('#equipment_id').val();
                $('#'+chart_id).hide();
                var chart = temp_chart;
                if (chart.series != null && chart.series.length != 0) {
                    while (chart.series.length > 0) {
                        chart.series[0].remove(false);
                    }
                }
                // $("#rendered_text"+chart_id).remove();
                $('#overlay').fadeIn();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                });
                // if(chart_type && zone_id){
                $('#overlay').fadeIn();
                $.ajax
                ({
                    type: "POST",
                    url: '{{route('shineCompliance.chart.generate')}}',
                    data: {chart_type : chart_name, option : option, equipment_id : equipment_id},
                    // dataType: "json",
                    cache: false,
                    success: function (point) {
                        $('#overlay').fadeOut();
                        initChart(chart, point, chart_id, '');
                        chart.redraw();
                    },
                });

                $('#'+chart_id).show();
                // }
            });

            <!-- end chart -->
            var validate_tmv_on;
            var validate_tmv_off;
            var template_id;
            $.ajax({
                type: "GET",
                url: "{{ route('shineCompliance.equipment.ajax_equipment_template') }}",
                data:  {
                    type: '{{ $equipment->type ?? 0 }}',
                    is_water_temperature: 1
                },
                cache: false,
                success: function (response) {
                    if (response.status == 200) {
                        var actives = response.data;

                        actives.forEach(function(active) {
                            if (active != 'flow_temp_gauge_value' && active != 'return_temp_gauge_value') {
                                $('#' + active + '-form').show();
                            }
                        });

                        //validate require
                        var flow_temp_gauge = $("#flow_temp_gauge").prop('checked');
                        var return_temp_gauge = $("#return_temp_gauge").prop('checked');

                        requires = response.required;
                        requires.forEach(function(requireData) {
                            if (requireData != 'flow_temp_gauge_value' && requireData != 'return_temp_gauge_value') {
                                $('#' + requireData).addClass("form-require");
                                $('#' + requireData + '-star').removeClass("d-none");
                            } else {
                                if (flow_temp_gauge && requireData == 'flow_temp_gauge_value') {
                                    $('#' + requireData).addClass("form-require");
                                    $('#' + requireData + '-star').removeClass("d-none");
                                }
                                if (return_temp_gauge  && requireData == 'return_temp_gauge_value') {
                                    $('#' + requireData).addClass("form-require");
                                    $('#' + requireData + '-star').removeClass("d-none");
                                }
                            }
                        });

                        template_id = response.template_id;
                        // Miscellaneous Equipment template
                        // if (template_id == 1) {
                        //     $('#temp_tab').hide();
                        // } else {
                        //     $('#temp_tab').show();
                        // }

                        // Show Sampling tab if Outlet templates
                        // if (template_id == 4 || template_id == 5 || template_id == 6) {
                        //     $('#sampling_tab').show();
                        // } else {
                        //     $('#sampling_tab').hide();
                        // }

                        // if (template_id == 8) {
                        //     $('#model_tab').hide();
                        //     $('#cleaning_tab').hide();
                        //     $('#drain_valve-form').children('label').text('Drain Valve Fitted?');
                        // } else {
                        //     $('#model_tab').show();
                        //     $('#cleaning_tab').show();
                        //     $('#drain_valve-form').children('label').text('Drain Valve:');
                        // }

                        var validation = response.validation;
                        validate_length = Object.keys(validation).length;
                        // if no tmv logic
                        if (validate_length == 1) {
                            validation.forEach(function(field) {
                                applyValidate('bottom_temp', field.bottom_temp_max, field.bottom_temp_min);
                                applyValidate('flow_temp_gauge_value', field.flow_temp_gauge_value_max,field.flow_temp_gauge_value_min)
                                applyValidate('flow_temp', field.flow_temp_max,field.flow_temp_min)
                                applyValidate('inlet_temp', field.inlet_temp_max,field.inlet_temp_min)
                                applyValidate('return_temp_gauge_value', field.return_temp_gauge_value_max,field.return_temp_gauge_value_min)
                                applyValidate('return_temp', field.return_temp_max,field.return_temp_min)
                                applyValidate('stored_temp', field.stored_temp_max,field.stored_temp_min)
                                applyValidate('top_temp', field.top_temp_max,field.top_temp_min)
                                applyValidate('incoming_main_pipe_work_temp', field.incoming_main_pipe_work_temp_max,field.incoming_main_pipe_work_temp_min)

                            });
                            // if have tmv logic
                        } else if (validate_length == 2){

                            validation.forEach(function(tmv_field) {
                                if (tmv_field.tmv == 0) {
                                    validate_tmv_off = tmv_field;
                                }
                                if (tmv_field.tmv == 1) {
                                    validate_tmv_on = tmv_field;
                                }
                            });
                            var tmv_fitted = $("#tmv_fitted").prop('checked');
                            tmvFitted(tmv_fitted);
                        }

                        $('#overlay').fadeOut();
                    } else {
                        $('#overlay').fadeOut();
                    }
                }
            });

            $('body').on('click','.edit-temperature-btn',function(e){
                e.preventDefault();
                //set value for save later
                // $('#overlay').fadeIn();
                // table_history.rows().clear().draw();
                // table_history.ajax.url(url).load(fadeOut);//call back fade out
                //parent-element
                var key = $(this).data('key-id');
                //active_key
                $('#active_key').val(key);
                $.each($('.parent-edit-temperature-modal').find('.parent-element'), function (k,v) {
                    // $(v).hide();
                    if($(v).find('#'+key).length){
                        $(v).show();
                    } else {
                        $(v).hide();
                    }
                });
                $('#edit-temperature-modal').modal('show');
            });
            //todo click change input temperature
            $('body').on('click', '#save-temperature', function(e){
                e.preventDefault();
                var is_valid = true;
                $('.form-require').each(function(k,v){
                    if(($(v).val() == '' || !$(v).val()) && $(v).is(':visible')){
                        is_valid = false;
                        var label = $(v).closest('.parent-element').find('label').html();
                        label = label.replace('*', '');//remove character :*
                        label = label.replace(':', '');//remove character :*
                        var warning_msg = 'The ' + label+ ' field is required!';
                        showWarning(true, v, warning_msg);
                    }
                });
                if(!is_valid){
                    console.log(1);
                    //scroll to error
                    // $('html, body').animate({
                    //     scrollTop: $(this).closest('form').find('.is-invalid:first').offset().top - 200
                    // }, 1000);
                    // $('#overlay').fadeOut();
                    return;
                } else {
                    //set null when elements are invisible in temperature tab
                    // $.each($('#temp_ph').find('input:hidden'), function (k,v) {
                    //     if($(v).is(":hidden")){
                    //         $(v).val('');
                    //     }
                    // });
                    //todo update this submit using ajax + reload
                    $(this).closest('.parent-edit-temperature-modal').find('form').submit();
                    {{--$.ajaxSetup({--}}
                    {{--    headers: {--}}
                    {{--        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
                    {{--    }--}}
                    {{--});--}}
                    {{--$.ajax({--}}
                    {{--    type: "GET",--}}
                    {{--    url: "{{ route('shineCompliance.equipment.ajax_update_temperature') }}",--}}
                    {{--    data: {--}}
                    {{--        type: type,--}}
                    {{--        is_water_temperature: is_water_temperature--}}
                    {{--    },--}}
                    {{--    cache: false,--}}
                    {{--    success: function (response) {--}}

                    {{--    }--}}
                    {{--});--}}
                }
            });
            //temperature-history-btn
            var table_history = $('#temperature-history-dt-table').DataTable({
                'ajax': {
                    'url': '{!! route('shineCompliance.equipment.ajax_temperature_history') !!}',
                    "destroy" : true,
                },
                "lengthChange": false,
                "ordering": false,
                pageLength: 10,
                bInfo:false,
                "pagingType": "full_numbers",
                'columnDefs': [{
                    // 'targets': 0,
                    'searchable': false,
                    'orderable': false,
                    // 'className': 'dt-body-center',
                    'render': function (data, type, full, meta){
                        return '<td>'+full[0]+'</td><td>'+full[1]+'</td>';
                    }
                }],
                'order': []
            });
            $('body').on('click','.temperature-history-btn',function(e){
                e.preventDefault();
                var id = $(this).data('equip-id');
                var key = $(this).data('key-id');
                var url = '{!! route('shineCompliance.equipment.ajax_temperature_history') !!}'+'?id='+id+'&key='+key;
                if(url){
                    //set value for save later
                    $('#overlay').fadeIn();
                    table_history.rows().clear().draw();
                    table_history.ajax.url(url).load(fadeOut);//call back fade out
                    $('#temperature-history-listing-modal').modal('show');
                }
            });

            $('body').on('change', '.form-require', function(){
                if(!$(this).val() || $(this).val() == ''){
                    var label = $(this).closest('.parent-element').find('label').html();
                    label = label.replace('*', '');//remove character :*
                    label = label.replace(':', '');//remove character :*
                    var warning_msg = 'The ' + label+ ' field is required!';
                    showWarning(true, this, warning_msg);
                } else{
                    showWarning(false, this, '');
                }
            });
        });

        function fadeOut(){

            $('#overlay').fadeOut();
        }

        function tmvFitted(tmv_fitted) {
            if (!tmv_fitted) {
                if (validate_tmv_off) {
                    if (template_id = 5 || template_id == 6) {
                        $('#ph-form').show();
                    }
                    applyValidate('bottom_temp', validate_tmv_off.bottom_temp_max, validate_tmv_off.bottom_temp_min);
                    applyValidate('flow_temp_gauge_value', validate_tmv_off.flow_temp_gauge_value_max,validate_tmv_off.flow_temp_gauge_value_min)
                    applyValidate('flow_temp', validate_tmv_off.flow_temp_max,validate_tmv_off.flow_temp_min)
                    applyValidate('inlet_temp', validate_tmv_off.inlet_temp_max,validate_tmv_off.inlet_temp_min)
                    applyValidate('return_temp_gauge_value', validate_tmv_off.return_temp_gauge_value_max,validate_tmv_off.return_temp_gauge_value_min)
                    applyValidate('return_temp', validate_tmv_off.return_temp_max,validate_tmv_off.return_temp_min)
                    applyValidate('stored_temp', validate_tmv_off.stored_temp_max,validate_tmv_off.stored_temp_min)
                    applyValidate('top_temp', validate_tmv_off.top_temp_max,validate_tmv_off.top_temp_min)
                    applyValidate('incoming_main_pipe_work_temp', validate_tmv_off.incoming_main_pipe_work_temp_max,validate_tmv_off.incoming_main_pipe_work_temp_min)
                }
            } else {
                if (validate_tmv_on) {
                    if (template_id = 5 || template_id == 6) {
                        $('#ph-form').hide();
                    }
                    applyValidate('bottom_temp', validate_tmv_on.bottom_temp_max, validate_tmv_on.bottom_temp_min);
                    applyValidate('flow_temp_gauge_value', validate_tmv_on.flow_temp_gauge_value_max,validate_tmv_on.flow_temp_gauge_value_min)
                    applyValidate('flow_temp', validate_tmv_on.flow_temp_max,validate_tmv_on.flow_temp_min)
                    applyValidate('inlet_temp', validate_tmv_on.inlet_temp_max,validate_tmv_on.inlet_temp_min)
                    applyValidate('return_temp_gauge_value', validate_tmv_on.return_temp_gauge_value_max,validate_tmv_on.return_temp_gauge_value_min)
                    applyValidate('return_temp', validate_tmv_on.return_temp_max,validate_tmv_on.return_temp_min)
                    applyValidate('stored_temp', validate_tmv_on.stored_temp_max,validate_tmv_on.stored_temp_min)
                    applyValidate('top_temp', validate_tmv_on.top_temp_max,validate_tmv_on.top_temp_min)
                    applyValidate('incoming_main_pipe_work_temp', validate_tmv_on.incoming_main_pipe_work_temp_max,validate_tmv_on.incoming_main_pipe_work_temp_min)
                }
            }
        }

        function applyValidate(dom_js, max, min) {

            if (!min && !max) {
                console.log('no validate');
            }
            if (max && !min) {
                $('#' + dom_js + '_validate').html('Must be less than ' + max + ' °C');
            }

            if (!max && min) {
                $('#' + dom_js + '_validate').html('Must be at least ' + min + ' °C');
            }

            if (max && min && min < max) {
                $('#' + dom_js + '_validate').html('Must be between ' + min + ' - ' + max + ' °C');
            }

            if (max && min && min > max) {
                $('#' + dom_js + '_validate').html('Must be less than ' + max + ' °C' +  ' or at least ' + min + ' °C');
            }
        }
        //show warning
        function showWarning(is_show, that, message){
            if(is_show){
                $(that).addClass('is-invalid');
                $(that).parent().find('span strong').html(message);
            } else {
                $(that).removeClass('is-invalid');
                $(that).parent().find('span strong').html('');
            }
        }

        function initChart(chart, data_chart, chart_id, displayType){
            var yTitle = data_chart[3];
            // console.log(chart, data_chart, yTitle, 2222)
            if (yTitle != 'Pie') {
                var stock_data = data_chart[1];
                // console.log(stock_data, chart, 3333);
                $.each(stock_data, function (idx, rec) {
                    chart.addSeries(rec);
                });

                chart.setTitle({
                    text: yTitle,
                    align: 'left',
                });
                chart.yAxis[0].setTitle({text: yTitle});
            } else {
                console.log(chart, data_chart);
                // console.log(data_chart);
                chart.addSeries(data_chart[1]);
                // var offsetLeft = 0,
                //     offsetTop = 10,
                //     x = chart.plotLeft + chart.plotWidth / 2 + offsetLeft,
                //     y = chart.plotTop + chart.plotHeight / 2 + offsetTop;
                //
                // var total = 0, partValue = 0;
                //
                // data_chart[1]['data'].forEach(function(i, idx, array) {
                //     if(idx === array.length - 1) {
                //         total = i.total;
                //     }
                //     if (idx === 1) {
                //         partValue = i.total;
                //     }
                // });
                //
                // var displayText = '';
                // if (displayType == 'total') {
                //     displayText = total;
                // } else if (displayType == 'percent') {
                //     displayText = Math.round((partValue / total) * 100) + '%';
                // }
                //
                // chart.renderer.text(displayText, x, y).add().css({
                //     fontSize: '25px'
                // }).attr({
                //     align: 'center',
                //     id :'rendered_text'+chart_id
                // }).toFront();
            }
            // chart.redraw();
        }
    </script>
@endpush
