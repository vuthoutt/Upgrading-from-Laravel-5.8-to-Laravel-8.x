@extends('shineCompliance.layouts.app')
@section('content')
    @if($hazard->assess_id == 0)
        @include('shineCompliance.partials.nav',['breadCrumb' => 'register_hazard_edit', 'color' => 'red', 'data'=> $hazard])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_hazard_edit', 'color' => 'orange', 'data'=> $hazard])
    @endif

    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">Edit Hazard </h3>
        </div>
        <div class="main-content">
            <form method="POST" action="{{ route('shineCompliance.assessment.post_edit_hazard',
            ['hazard_id' => $hazard->id ?? 0]) }}" enctype="multipart/form-data" class="form-shine">
                @csrf
                <input type="hidden" id="assess_id" name="assess_id" value="{{ $hazard->assess_id ?? 0 }}">
                <input type="hidden" id="assess_type" name="assess_type" value="{{ $hazard->assess_type ?? 0 }}">
                <input type="hidden" id="property_id" name="property_id" value="{{ $hazard->property_id ?? 0 }}">
                <input type="hidden" id="linked_question_id" name="linked_question_id" value="{{ $hazard->linked_question_id ?? 0 }}">
                @include('shineCompliance.forms.form_input',['title' => 'Hazard Name:','name' => 'name','required' => true,
                'placeholder' => 'Add Hazard Name','data' => $hazard->name ?? ''])

                @if($hazard->assess_type == ASSESSMENT_WATER_TYPE || $hazard->assess_type == ASSESSMENT_HS_TYPE)
                    @include('shineCompliance.forms.form_dropdown',['title' =>'Hazard Type:',
                            'data' => $hazard_types ?? [], 'name' => 'type', 'key'=>'id', 'value'=>'description',
                            'compare_value' => $hazard->hazardType->id ?? '', 'required' => true])
                @elseif($hazard->assess_type == ASSESSMENT_FIRE_TYPE)
                    @include('shineCompliance.forms.form_dropdown',['title' =>'Hazard Type:',
                            'data' => $hazard_types->where('parent_id', 0) ?? [], 'name' => 'type', 'key'=>'id', 'value'=>'description','id'=> 'parent_type',
                            'compare_value' => $hazard->hazardType->id ?? '', 'required' => true])
                    @include('shineCompliance.forms.form_datepicker',['title' => 'Date Created:', 'name' => 'created_date', 'data' => $hazard->created_date ?? '', 'required' => true])

                @endif

                @include('shineCompliance.forms.form_dropdown',['title' => 'Reason for Inaccessibility:', 'data' => $inaccessible_reason ?? [], 'name' => 'reason',
                                                    'key'=> 'id', 'value'=>'description', 'compare_value' => $hazard->reason ?? ''])
                <div class="row register-form mb-4 acm-child" id="reason-other-form">
                    <div class="col-md-5 offset-md-3">
                        <input type="text" class="form-control" name="reason_other" value="{{ $hazard->reason_other ?? '' }}" id="reason-other" placeholder="Please add other Reason">
                    </div>
                </div>

                @include('shineCompliance.forms.form_dropdown_area',['title' => 'Floor:', 'data' => $areas, 'enableNA' => true,
                'name' => 'area','compare_value' => $hazard->area_id ?? '',
                'key'=> 'id', 'value'=>'area_reference', 'required' => true ])
                @include('shineCompliance.forms.form_dropdown_area',['title' => 'Room:', 'data' => $locations,
                'name' => 'location','compare_value' => $hazard->location_id ?? '',
                'key'=> 'id', 'value'=>'location_reference', 'required' => true,'enableNA' => true ])
                @if((request()->has('assess_type') && request()->assess_type != ASSESSMENT_FIRE_TYPE)
                   || ($assessment && $assessment->classification != ASSESSMENT_FIRE_TYPE))
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Specific Location:', 'data' => $specificLocations, 'name' => 'specificLocations1','id' => 'specificLocations', 'key'=> 'id', 'value'=>'description', 'compare_value' => \CommonHelpers::checkArrayKey2($selectedSpecificLocations, 0) ])
                    <div class="row" style="margin-top: -14px;">
                        <div class="col-md-5 offset-md-3">
                            <div class="form-group mt-3">
                                <select class="form-control" name="specificLocations2" id="specificLocations2">
                                    <option value="-1"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="specific-form3">
                        <div class="col-md-5 offset-md-3">
                            <div class="form-group">
                                <select class="form-control" name="specificLocations3[]" id="specificLocations3" multiple="multiple" style="max-width: 458.5px;">
                                    <option value="-1"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="specific-other">
                        <div class="col-md-5 offset-md-3">
                            <div class="form-group">
                                <input type="text" class="form-control" id="specific-other-input" name="specificLocations-other" value="{{ $otherSpecificLocation }}" placeholder="Please add other specific locations">
                            </div>
                        </div>
                    </div>
                @endif
                @include('shineCompliance.forms.form_dropdown',['title' =>'Hazard Potential:', 'form_class' => 'calculate_score',
                'data' => $hazard_potentials ?? [], 'name' => 'hazard_potential', 'key'=>'id', 'value'=>'description','required' => true,
                'option_data'=>'score','compare_value' => $hazard->hazard_potential ?? ''])
                @include('shineCompliance.forms.form_dropdown',['title' =>'Likelihood of Harm:', 'form_class' => 'calculate_score',
                'data' => $hazard_likelihood_harms ?? [], 'name' => 'likelihood_of_harm', 'key'=>'id', 'value'=>'description','required' => true,
                'option_data'=>'score','compare_value' => $hazard->likelihood_of_harm ?? ''])
                <div class="row register-form">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold  fs-8pt" >Total:</label>
                    <div class="col-md-5">
                        <span id="total-score"></span>
                        <input type="hidden" class="form-control" name="total-score" id="total-score-form"  value="">
                    </div>
                </div>
                <div class="row register-form">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >{{'Overall '.ucfirst($assessment->assess_type ?? '').' Risk Assessment:'}}</label>
                    <div class="col-md-5">
                        <span id="risk-color" style="width: 30px;">&nbsp;&nbsp;&nbsp;</span>
                        &nbsp;
                        <span id="risk-text"></span>
                    </div>
                </div>
{{--                @if((request()->has('assess_type') && request()->assess_type != ASSESSMENT_FIRE_TYPE)--}}
{{--                        || ($assessment && $assessment->classification != ASSESSMENT_FIRE_TYPE))--}}
                    <div class="row register-form mb-4">
                        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Extent:</label>
                        <div class="col-md-2" >
                            <input type="text" class="form-control" name="extent" id="extent" value="{{$hazard->extent ?? NULL}}">
                        </div>
                        <div class="col-md-2" style="max-width: 70%">
                            <select class="form-control" name="measure_id" id="measure_id">
                                <option value="0"></option>
                                @foreach($extends as $extend)
                                    <option value="{{ $extend->id }}" {{$extend->id == $hazard->measure_id ? 'selected' : ''}}>{{ $extend->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                <div class="row register-form {{ isset($class_other) ? $class_other : '' }}">
                    <label for="first-name" class="col-md-{{ isset($width_label) ? $width_label : 3 }}  col-form-label text-md-left font-weight-bold">
                        Photography Override
                    </label>
                    <div class="col-md-5 mt-1">
                        <label class="switch">
                            <input type="checkbox" name="photo_override"  id="photo_override" {{ $hazard->photo_override == 1 ? 'checked' : '' }} value="1">
                            <span class="slider round" id="photo_override"></span>
                        </label>
                    </div>
                </div>
{{--                @endif--}}
                <div class="row offset-top40" id="img-hazard-off">
                    <div class="col-md-2 mr-5 ml-3">
                        @include('forms.form_photo_hazard',['title' => 'Location:', 'name' => 'location_photo', 'object_id' => $hazard->id, 'folder' => HAZARD_LOCATION_PHOTO])
                    </div>
                    <div class="col-md-2 mr-5">
                        @include('forms.form_photo_hazard',['title' => 'Hazard:', 'name' => 'hazard_photo', 'object_id' => $hazard->id, 'folder' => HAZARD_PHOTO])
                    </div>
                    <div class="col-md-2">
                        @include('forms.form_photo_hazard',['title' => 'Additional:', 'name' => 'additional_photo', 'object_id' => $hazard->id, 'folder' => HAZARD_ADDITION_PHOTO])
                    </div>
                </div>

                <div class="row offset-top40" id="img-hazard-on">
                    <div class="col-md-2 mr-5 ml-3">
                        @include('forms.form_photo_hazard',['title' => 'Location:', 'name' => 'location_photo_on', 'object_id' => 0, 'folder' => HAZARD_LOCATION_PHOTO])
                    </div>
                    <div class="col-md-2 mr-5">
                        @include('forms.form_photo_hazard',['title' => 'Hazard:', 'name' => 'hazard_photo_on', 'object_id' => 0, 'folder' => HAZARD_PHOTO])
                    </div>
                    <div class="col-md-2">
                        @include('forms.form_photo_hazard',['title' => 'Additional:', 'name' => 'additional_photo_on', 'object_id' => 0, 'folder' => HAZARD_ADDITION_PHOTO])
                    </div>
                </div>

                @include('shineCompliance.forms.actions_recommendation',['title' => 'Actions/recommendations:',
                'name' => 'actions_recommendation_id', 'required' => true, 'data_noun' => $actions_recommendation_nouns, 'data_verb' => $actions_recommendation_verbs,
                'other_value_noun' => NULL,'other_value_verb' => NULL, 'key' => 'id','value'=>'description',
                'compare_verb_value'=> $hazard->act_recommendation_verb,'compare_noun_value'=> $hazard->act_recommendation_noun])

                @if(($hazard->assess_type == ASSESSMENT_FIRE_TYPE)
                    || ($assessment && $assessment->classification == ASSESSMENT_FIRE_TYPE))
                    <div class="row" style="margin-top: -14px;">
                        <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">
                            Action Responsibility:
                            {{--                            <span style="color: red;">*</span>--}}
                        </label>
                        <div class="col-md-5">
                            <div class="row form-group mt-3">
                                <select class="form-control" name="parent_action" id="parent_action">
                                    <option value="" data-option="0">------ Please select an option -------</option>
                                    @foreach($hazard_action_responsibilities->where('parent_id', 0) as $action)
                                        <option value="{{ $action->id }}"
                                                {{ ($hazard->actionResponsibility->parent_id ?? '') == $action->id ? 'selected' : '' }}>{{ $action->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="hazard_action_responsibility_form">
                        <div class="col-md-5 offset-md-3">
                            <div class="row form-group">
                                <select class="form-control" name="action_responsibility" id="action_responsibility">
                                    <option value="-1"></option>
                                    @foreach($hazard_action_responsibilities->where('parent_id', '<>', 0) as $action)
                                        <option value="{{ $action->id }}" {{ ($hazard->action_responsibility ?? '') == $action->id ? 'selected' : '' }}
                                                data-parent-id="{{ $action->parent_id }}">{{ $action->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                @if($hazard->assess_type == ASSESSMENT_HS_TYPE)
                   @include('shineCompliance.forms.form_text_area_hazard',['title' => 'Comments:', 'data' => $hazard->comment ?? NULL, 'name' => 'comment','css'=> 'width: 585px;height: 50px!important;' ])
                @else
                    @include('shineCompliance.forms.form_text_area_hazard',['title' => 'Comments:', 'data' => $hazard->comment ?? NULL, 'name' => 'comment','css'=> 'width: 585px;height: 50px!important;','required' =>true ])
                @endif
                <div class="row">
                    <div class="offset-md-3">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt edit-hazard">
                            <strong>{{ __('Save') }}</strong>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('change', '#photo_override', function(){
                if($(this).is(":checked")){
                    $('#img-hazard-on').show();
                    $('#img-hazard-on').addClass('disable-button');
                    $('#img-hazard-off').hide();
                }else{
                    $('#img-hazard-off').show();
                    $('#img-hazard-on').hide();
                }
            });
            $('#photo_override').trigger('change');
            countTotal()
            function countTotal() {
                $('#risk-color').removeAttr('class');
                var total = 0;
                var hazard_potential = parseInt($("#hazard_potential").find(":selected").data('option'));
                var likelihood_of_harm = parseInt($("#likelihood_of_harm").find(":selected").data('option'));
                if (!isNaN(hazard_potential) && !isNaN(likelihood_of_harm) ) {
                    total = hazard_potential * likelihood_of_harm;
                }

                var color = '';
                var risk = '';
                switch (true) {
                    case (total == 0):
                        color = "badge-no-risk";
                        risk = "No Risk";
                        break;
                    case (total < 4):
                        color = "badge-very-low-risk";
                        risk = "Very Low";
                        break;
                    case (total < 10):
                        color = "badge-low-risk";
                        risk = "Low";
                        break;
                    case (total < 16):
                        color = "badge-medium-risk";
                        risk = "Medium";
                        break;
                    case (total < 21):
                        color = "badge-high-risk";
                        risk = "High";
                        break;
                    case (total < 26):
                        color = "badge-very-high-risk";
                        risk = "Very High";
                        break;
                }

                $('#total-score').html(total);
                $('#total-score-form').val(total);
                $('#risk-text').html(risk);
                $('#risk-color').addClass("badge " + color);
            }

            $('#parent_type').change(function (){
                var parentId = $(this).val();
                $('#type').children('option').each(function (i,e) {
                    if ($(e).data('parentId') == parentId) {
                        $(e).show();
                    } else {
                        $(e).hide();
                    }
                })
            });

            $('#parent_action').change(function (){
                var parentId = $(this).val();
                var first = 0;
                $('#action_responsibility').children('option').each(function (i,e) {
                    if ($(e).data('parentId') == parentId) {
                        first++;
                        if(first == 1){
                            $(e).prop('selected', true);
                        }
                        $(e).show();
                    } else {
                        $(e).hide();
                    }
                })
            });

            $("#hazard_potential").change(function(){
                countTotal();
            });
            $("#likelihood_of_harm").change(function(){
                countTotal();
            });

            // Specific location
            $("#specific-other").hide();
            $('#specificLocations3').select2({
                placeholder: "Please select an option"
            }).on('select2:select', function(e){
                var id = e.params.data.id;
                var option = $(e.target).children('[value='+id+']');
                option.detach();
                $(e.target).append(option).change();
            });
            //selected option

            $("#specific-form3").hide();
            $("#specificLocations2").hide();

            // on change specific location 1
            $("#specificLocations").change(function(){
                $("#specific-other-input").val('');
                specificLocation();
            });
            specificLocation();

            function specificLocation() {
                $('#specificLocations3').find('option').remove();
                // show other option if
                var text = $("#specificLocations").val();
                if (text == {{ EQUIPMENT_SPECIFIC_LOCATION_OTHER }}) {
                    $("#specific-other").show();
                    $("#specific-form3").hide();
                    $("#specificLocations2").hide();
                } else {
                    $("#specific-other").hide();
                    $('#specificLocations2').find('option').remove();
                    var parentId = $("#specificLocations").val();
                    $.ajax({
                        type: "GET",
                        url: "{{ route('shineCompliance.hazard.ajax_specific') }}",
                        data: {parent_id: parentId},
                        cache: false,
                        success: function (html) {
                            if (html.data.length > 0) {

                                if (html.have_child == true) {
                                    var selected_val = false;
                                    $.each(html.data, function(key, value ) {
                                        if (value.id == {{ count($selectedSpecificLocations) == 3 ? $selectedSpecificLocations[1] : 0 }}) {
                                            selected_val = true;
                                        }
                                        $('#specificLocations2').append($('<option>', {
                                            value: value.id,
                                            text : value.description
                                        }));
                                        selected_val = false
                                    });
                                    $("#specificLocations2").show().trigger('change');
                                    $("#specific-form3").show();
                                } else {
                                    $('#specificLocations3').find('option').remove();
                                    $.each(html.data, function(key, value ) {
                                        $('#specificLocations3').append($('<option>', {
                                            value: value.id,
                                            text : value.description
                                        }));
                                    });
                                    //add selected multi

                                    $('#specificLocations3').val(<?php echo json_encode(end($selectedSpecificLocations)); ?>);
                                    $('#specificLocations3').trigger('change'); // Notify any JS components that the value changed

                                    $("#specific-form3").show();
                                    $("#specificLocations2").hide();
                                }
                            } else {
                                $("#specific-form3").hide();
                                $("#specificLocations2").hide();
                            }
                        }
                    });
                }
            }

            //on change specific location 2
            getAjaxItemData('specificLocations2','specificLocations3');
            $("#specificLocations2").change(function(){
                getAjaxItemData('specificLocations2','specificLocations3');
            });

            function getAjaxItemData(parent_select_id, child_select_id,dropdown_id, compare, child_select_id2, child_select_id3  ) {
                if (compare === undefined) {
                    compare = 'nan';
                }
                if (child_select_id2 === undefined) {
                    child_select_id2 = 'nan';
                }
                if (child_select_id3 === undefined) {
                    child_select_id3 = 'nan';
                }

                $('#' + child_select_id).find('option').remove();
                var parent_id = $('#' + parent_select_id).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('shineCompliance.hazard.ajax_specific') }}",
                    data: {parent_id: parent_id},
                    cache: false,
                    success: function (html) {
                        if (html.data.length > 0) {
                            var selected_val = false;
                            $.each(html.data, function(key, value, selected ) {
                                if (value.id == compare) {
                                    selected_val = true;
                                }
                                $('#' + child_select_id).append($('<option>', {
                                    value: value.id,
                                    text : value.description,
                                    selected : selected_val
                                }));
                                selected_val = false;

                            })

                            $('#' + child_select_id).show().trigger('change');
                            //add selected multi
                            $('#specificLocations3').val(<?php echo json_encode(end($selectedSpecificLocations)); ?>);
                            $('#specificLocations3').trigger('change'); // Notify any JS components that the value changed

                        } else {
                            $('#' + child_select_id).hide();
                            $('#' + child_select_id2).hide();
                            $('#' + child_select_id3).hide();
                            $("#productDebris1").show();
                        }
                    },
                });
            }

            $('#area').change(function () {
                $('#overlay').fadeIn();
                if ($(this).val() > 0) {
                    $.ajax
                    ({
                        type: "GET",
                        url: "/compliance/assessment/" + $('#assess_id').val() + "/area/" + $(this).val() + "/locations",
                        cache: false,
                        success: function (data) {
                            if (data) {
                                if (data != undefined) {
                                    $('#location').html('');
                                    $('#location').append($('<option>', {
                                        value: '',
                                        text : '------ Please select an option -------'
                                    }));
                                    $.each( data, function( key, value ) {
                                        $('#location').append($('<option>', {
                                            value: value.id,
                                            text : value.location_reference + ' - ' + value.description
                                        }))
                                    });
                                    $('#location').append($('<option>', {
                                        value: 0,
                                        text : 'N/A'
                                    })).append($('<option>', {
                                        value: -1,
                                        text : 'Other'
                                    }));
                                }
                            }
                        }
                    });
                } else {
                    $('#location').html('');
                    if($(this).val() == 0){ // area N/A -> location N/A
                        $('#location').append($('<option>', {
                            value: '',
                            text : '------ Please select an option -------'
                        })).append($('<option>', {
                            value: 0,
                            text : 'N/A'
                        }))
                    } else {
                        $('#location').append($('<option>', {
                            value: '',
                            text : '------ Please select an option -------'
                        })).append($('<option>', {
                            value: 0,
                            text : 'N/A'
                        })).append($('<option>', {
                            value: -1,
                            text : 'Other'
                        }));
                    }
                }
                $('input[name="location_reference"]').val(null);
                $('input[name="location_description"]').val(null);
                $('#location_other').hide();
                $('#overlay').fadeOut();
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

            $('body').on('click', '.edit-hazard', function(e){
                e.preventDefault();
                var is_valid = true;
                $('.form-require').each(function(k,v){
                    if($(v).is(':visible') && ($(v).val() == '' || !$(v).val()) ){
                        is_valid = false;
                        var label = $(v).closest('.parent-element').find('label').html();
                        label = label.replace('*', '');//remove character :*
                        label = label.replace(':', '');//remove character :*
                        var warning_msg = 'The ' + label+ ' field is required!';
                        showWarning(true, v, warning_msg);
                    }
                });
                if(!is_valid){
                    //scroll to error
                    $('html, body').animate({
                        scrollTop: $(this).closest('form').find('.is-invalid:visible:first').offset().top - 200
                    }, 1000);
                    return;
                } else {
                    $(this).closest('form').submit();
                }
            });

            var accessibility = $('#parent_type').val();
            if (accessibility == "{{HAZARD_TYPE_INACCESSIBLE_ROOM}}") {
                $('#reason-form').show();
            } else {
                $('#reason-form').hide();
            }


            $("#reason").change(function(){
                var text = $("#reason").find(":selected").text();
                if (text == 'Other') {
                    $("#reason-other").show();
                } else {
                    $("#reason-other").hide();
                }
            });
            $("#reason").trigger('change');
            $('select[name="type"]').trigger('change');
        });

        $('body').on('change', '#parent_type', function(){
            $('#action_recommendation_noun').find("option").hide();
            var hazard_type = $(this).val();
            var data = $('#action_recommendation_noun').find("[data-hazard-type='" + hazard_type + "']").show();
        })
        $('#parent_type').trigger('change');
        //show warning
        function showWarning(is_show, that, message){
            if(is_show){
                $(that).addClass('is-invalid error-text');
                $(that).parent().find('span strong').html(message);
            } else {
                $(that).removeClass('is-invalid error-text');
                $(that).parent().find('span strong').html('');
            }
        }
    </script>
@endpush
