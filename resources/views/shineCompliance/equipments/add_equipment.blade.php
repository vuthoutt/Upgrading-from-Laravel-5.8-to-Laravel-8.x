@extends('shineCompliance.layouts.app')
@section('content')
    @if($assess_id == 0)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_equipment_add', 'color' => 'red', 'data'=> $property])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_equipment_add', 'color' => 'orange', 'data'=> $assessment])
    @endif
    <div id="overlay" style="display:none;">
        <div class="spinner"></div>
        <br/>
        Loading...
    </div>
    <div class="container-cus prism-content pad-up">
        <div class="row ">
            <h3 class="title-row">New Equipment</h3>
        </div>
        <div class="main-content">
            <!-- Nav tabs -->
            <ul class="nav nav-pills {{ ($assess_id == 0) ? \CommonHelpers::getNavItemColor('red') : \CommonHelpers::getNavItemColor('orange') }}" id="myTab" style="margin-left: -7px !important;">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#details"><strong>Details</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="model_tab" data-toggle="tab" href="#Model"><strong>Model</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="construction_tab" data-toggle="tab" href="#Construction"><strong>Construction</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="cleaning_tab" data-toggle="tab" href="#Cleaning"><strong>Cleaning</strong></a>
                </li>
                <li class="nav-item" id="temp_tab">
                    <a class="nav-link" data-toggle="tab" href="#temp_ph"><strong>Temperatures & PH</strong></a>
                </li>
                <li class="nav-item" id="sampling_tab">
                    <a class="nav-link" data-toggle="tab" href="#sampling"><strong>Sampling</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Photography"><strong>Photography</strong></a>
                </li>
            </ul>
            <form method="POST" action="{{ route('shineCompliance.equipment.post_add_equipment', ['property_id' => $property_id ?? 0,'assess_id' => $assess_id ?? 1]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <!-- Tab panes -->
                <div class="tab-content">
                    <div id="details" class="container tab-pane active pl-0">
                        @include('shineCompliance.equipments.add_detail')
                    </div>
                    <div id="Model" class="container tab-pane fade pl-0">
                        @include('shineCompliance.equipments.add_model')
                    </div>
                    <div id="Construction" class="container tab-pane fade pl-0">
                        @include('shineCompliance.equipments.add_construction')
                    </div>
                    <div id="Cleaning" class="container tab-pane fade pl-0">
                        @include('shineCompliance.equipments.add_cleaning')
                    </div>
                    <div id="temp_ph" class="container tab-pane fade pl-0">
                        @include('shineCompliance.equipments.add_temp')
                    </div>
                    <div id="sampling" class="container tab-pane fade pl-0">
                        @include('shineCompliance.equipments.add_sampling')
                    </div>
                    <div id="Photography" class="container tab-pane fade">
                        @include('shineCompliance.equipments.add_photography')
                    </div>
                </div>
                <div class="offset-md-3 mt-4 pull-left pl-1">
                    <button type="button" class="btn light_grey_gradient_button fs-8pt add-equipment">
                        <strong>{{ __('Add') }}</strong>
                    </button>
                </div>
            </form>
        </div>
        @endsection
        @push('javascript')

            <script type="text/javascript">
                var validate_tmv_on;
                var validate_tmv_off;
                var template_id;
                $(document).ready(function(){
                    var assessment_classification = "{{ $assessment->assess_classification ?? null }}";
                    var assessment_type = "{{ $assessment->type ?? null }}";
                    var is_water_temperature = "{{ $is_water_temperature ?? false }}";

                    var options = {
                        url: function(phrase) {
                            return "{{route('shineCompliance.equipment.search_equipment_type')}}"+"?query_string=" + phrase+ "&assessment_classification=" + assessment_classification + "&assessment_type=" + assessment_type;
                        },
                        getValue: "description",
                        template: {
                            type: "custom",
                            method: function(value, item) {
                                return  item.description;
                            }
                        },
                        list: {
                            onClickEvent: function() {
                                var value = $("#equipment-search-system_type").getSelectedItemData().id;
                                $("#equipment-type-holder").val(value).trigger("change");
                                selectEquipmentType(value, is_water_temperature);
                            },
                            hideAnimation: {
                                type: "slide", //normal|slide|fade
                                time: 250,
                                callback: function() {}
                            },
                            maxNumberOfElements: 5
                        },
                        placeholder: "Search for Equipment Type",
                        theme: "blue-light"
                    };

                    $("#equipment-search-system_type").easyAutocomplete(options);

                    function selectEquipmentType(type, is_water_temperature) {

                        $('#overlay').fadeIn();
                        $('.validate_tmv').html('');
                        $(".equipment_section").find('.form-require').removeClass('form-require');
                        $(".equipment_section").find('.d-none').addClass('d-none');
                        $.ajax({
                            type: "GET",
                            url: "{{ route('shineCompliance.equipment.ajax_equipment_template') }}",
                            data:  {
                                type: type,
                                is_water_temperature: is_water_temperature
                            },
                            cache: false,
                            success: function (response) {
                                if (response.status == 200) {
                                    $('.equipment_section').hide();
                                    var actives = response.data;

                                    actives.forEach(function(active) {
                                        if (active != 'flow_temp_gauge_value' && active != 'return_temp_gauge_value'
                                            && active != 'nearest_furthest' && active != 'pipe_insulation_condition'
                                            && active != 'water_meter_reading'
                                            && active != 'insulation_thickness' && active != 'insulation_condition') {
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
                                    if (template_id == 1) {
                                        $('#temp_tab').hide();
                                        $('#comment').val('No action required as long as this equipment is operated and maintained in accordance with manufacturer guidelines.');
                                    } else {
                                        $('#temp_tab').show();
                                        $('#comment').val('');
                                    }

                                    // Show Sampling tab if Outlet templates
                                    if (template_id == 4 || template_id == 5 || template_id == 6) {
                                        $('#sampling_tab').show();
                                    } else {
                                        $('#sampling_tab').hide();
                                    }

                                    // Hide Construction tab if Outlet templates
                                    if (template_id == 10 || template_id == 12 || template_id == 13) {
                                        $("#construction_return_temp").change(function(){
                                            var flow_temp_gauge = $("#construction_return_temp").prop('checked');

                                            if (flow_temp_gauge) {
                                                $("#return_temp-form").show();
                                                $("#return_temp").addClass('form-require');
                                            } else {
                                                $("#return_temp-form").hide();
                                                $("#return_temp").removeClass('form-require');
                                            }
                                        });
                                        $("#construction_return_temp").trigger('change');

                                    }

                                    // Hide Cleaning tab
                                    if (template_id == 10 || template_id == 8) {
                                        $('#cleaning_tab').hide();
                                    } else {
                                        $('#cleaning_tab').show();
                                    }

                                    if (template_id == 8) {
                                        $('#model_tab').hide();
                                        $('#drain_valve-form').children('label').text('Drain Valve Fitted?');
                                    } else {
                                        $('#model_tab').show();
                                        $('#drain_valve-form').children('label').text('Drain Valve:');
                                    }

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
                                            applyValidate('hot_flow_temp', field.hot_flow_temp_max,field.hot_flow_temp_min)
                                            applyValidate('cold_flow_temp', field.cold_flow_temp_max,field.cold_flow_temp_min)
                                            applyValidate('pre_tmv_cold_flow_temp', field.pre_tmv_cold_flow_temp_max,field.pre_tmv_cold_flow_temp_min)
                                            applyValidate('pre_tmv_hot_flow_temp', field.pre_tmv_hot_flow_temp_max,field.pre_tmv_hot_flow_temp_min)
                                            applyValidate('post_tmv_temp', field.post_tmv_temp_max,field.post_tmv_temp_min)
                                            // applyValidate('tmv = field.tmv;')

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
                                        if (tmv_fitted) {
                                            $("#mixed_temp-form").show();
                                        } else {
                                            $("#mixed_temp-form").hide();
                                        }
                                    }

                                    $('#overlay').fadeOut();
                                } else {
                                    $('#overlay').fadeOut();
                                }
                            }
                        });
                    }

                    $("#flow_temp_gauge").change(function(){
                        var flow_temp_gauge = $("#flow_temp_gauge").prop('checked');

                        if (flow_temp_gauge) {
                            $("#flow_temp_gauge_value-form").show();
                            $("#flow_temp_gauge_value").addClass('form-require');
                        } else {
                            $("#flow_temp_gauge_value-form").hide();
                            $("#flow_temp_gauge_value").removeClass('form-require');
                        }
                    });
                    $("#flow_temp_gauge").trigger('change');

                    $("#return_temp_gauge").change(function(){
                        var flow_temp_gauge = $("#return_temp_gauge").prop('checked');
                        if (flow_temp_gauge) {
                            $("#return_temp_gauge_value-form").show();
                            $("#return_temp_gauge_value").addClass('form-require');
                        } else {
                            $("#return_temp_gauge_value-form").hide();
                            $("#return_temp_gauge_value").removeClass('form-require');
                        }
                    });

                    $("#return_temp_gauge").trigger('change');

                    $("#sentinel").change(function(){
                        var sentinel = $("#sentinel").prop('checked');
                        if (sentinel) {
                            $("#nearest_furthest-form").show();
                        } else {
                            $("#nearest_furthest-form").hide();
                        }
                    });
                    $("#sentinel").trigger('change');

                    $("#water_meter_fitted").change(function(){
                        var water_meter_fitted = $("#water_meter_fitted").prop('checked');
                        if (water_meter_fitted) {
                            $("#water_meter_reading-form").show();
                        } else {
                            $("#water_meter_reading-form").hide();
                        }
                    });
                    $("#water_meter_fitted").trigger('change');

                    $("#insulation_type").change(function(){
                        var insulation_type = $("#insulation_type").val();

                        if (insulation_type.includes('221') || insulation_type.includes('256') || insulation_type.includes('257')) {
                            $("#insulation_thickness-form").hide();
                            $("#insulation_condition-form").hide();
                        } else {
                            $("#insulation_thickness-form").show();
                            $("#insulation_condition-form").show();
                        }
                    });
                    $("#insulation_type").trigger('change');

                    $("#pipe_insulation").change(function(){
                        var pipe_insulation = $("#pipe_insulation").val();

                        if (pipe_insulation.includes('222') || pipe_insulation.includes('260') || pipe_insulation.includes('261')) {
                            $("#pipe_insulation_condition-form").hide();
                        } else {
                            $("#pipe_insulation_condition-form").show();
                        }
                    });
                    $("#pipe_insulation").trigger('change');

                    $("#tmv_fitted").change(function(){
                        var tmv_fitted = $("#tmv_fitted").prop('checked');
                        tmvFitted(tmv_fitted);
                        if (tmv_fitted) {
                            $("#mixed_temp-form").show();
                        } else {
                            $("#mixed_temp-form").hide();
                        }
                    });
                    $("#tmv_fitted").trigger('change');

                    function tmvFitted(tmv_fitted) {
                        if (!tmv_fitted) {
                            if (validate_tmv_off) {
                                if (template_id = 5 || template_id == 6 || template_id == 14) {
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
                                applyValidate('hot_flow_temp', validate_tmv_off.hot_flow_temp_max,validate_tmv_off.hot_flow_temp_min)
                                applyValidate('cold_flow_temp', validate_tmv_off.cold_flow_temp_max,validate_tmv_off.cold_flow_temp_min)
                                applyValidate('pre_tmv_cold_flow_temp', validate_tmv_off.pre_tmv_cold_flow_temp_max,validate_tmv_off.pre_tmv_cold_flow_temp_min)
                                applyValidate('pre_tmv_hot_flow_temp', validate_tmv_off.pre_tmv_hot_flow_temp_max,validate_tmv_off.pre_tmv_hot_flow_temp_min)
                                applyValidate('post_tmv_temp', validate_tmv_off.post_tmv_temp_max,validate_tmv_off.post_tmv_temp_min)
                            }
                        } else {
                            if (validate_tmv_on) {
                                if (template_id = 5 || template_id == 6 || template_id == 14) {
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
                                applyValidate('hot_flow_temp', validate_tmv_on.hot_flow_temp_max,validate_tmv_on.hot_flow_temp_min)
                                applyValidate('cold_flow_temp', validate_tmv_on.cold_flow_temp_max,validate_tmv_on.cold_flow_temp_min)
                                applyValidate('pre_tmv_cold_flow_temp', validate_tmv_on.pre_tmv_cold_flow_temp_max,validate_tmv_on.pre_tmv_cold_flow_temp_min)
                                applyValidate('pre_tmv_hot_flow_temp', validate_tmv_on.pre_tmv_hot_flow_temp_max,validate_tmv_on.pre_tmv_hot_flow_temp_min)
                                applyValidate('post_tmv_temp', validate_tmv_on.post_tmv_temp_max,validate_tmv_on.post_tmv_temp_min)
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

                    $('body').on('click', '.add-equipment', function(e){
                        e.preventDefault();
                        var is_valid = true;
                        $('.form-require').each(function(k,v){
                            if($(v).is(":visible")){
                                if($(v).val() == '' || !$(v).val()){
                                    is_valid = false;
                                    var label = $(v).closest('.parent-element').find('label').html();
                                    label = label.replace('*', '');//remove character :*
                                    label = label.replace(':', '');//remove character :*
                                    var warning_msg = 'The ' + label+ ' field is required!';
                                    showWarning(true, v, warning_msg);
                                }
                            }
                        });
                        if(!is_valid){
                            //scroll to error
                            $('html, body').animate({
                                scrollTop: $(this).closest('form').find('.is-invalid:first').offset().top - 200
                            }, 1000);
                            $('#overlay').fadeOut();
                            return;
                        } else {
                            //set null when elements are invisible in temperature tab
                            $.each($('#temp_ph').find('input:hidden'), function (k,v) {
                                if($(v).is(":visible")){
                                    $(v).val('');
                                }
                            });
                            $(this).closest('form').submit();
                        }
                    });
                });

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
            </script>
    @endpush
