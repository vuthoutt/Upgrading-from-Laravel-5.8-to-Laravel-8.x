@extends('shineCompliance.layouts.app')
@section('content')
    @if($assess_id == 0)
        @php
            $property['assess_type'] = request()->assess_type;
        @endphp
        @include('shineCompliance.partials.nav',['breadCrumb' => 'register_hazard_add', 'color' => 'red', 'data'=> $property])
    @else
       @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_hazard_add', 'color' => 'orange', 'data'=> $assessment])
    @endif

    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">Add Hazard </h3>
        </div>
        <div class="main-content">
            <form method="POST" action="{{ route('shineCompliance.assessment.post_add_hazard',
            ['assess_id' => $assess_id]) }}" enctype="multipart/form-data" class="form-shine">
                @csrf
                <input type="hidden" id="assess_id" name="assess_id" value="{{ $assess_id ?? 0 }}">
                <input type="hidden" id="property_id" name="property_id" value="{{ $property_id ?? 0 }}">
                <input type="hidden" id="linked_question_id" name="linked_question_id" value="{{ $question_id ?? 0 }}">
                <input type="hidden" id="assess_type" name="assess_type" value="{{ request()->has('assess_type') ? request()->get('assess_type') : 0 }}">
                @include('shineCompliance.forms.form_input',['title' => 'Hazard Reference:','name' => 'name','required' => true,
                'placeholder' => 'Add Hazard Name', 'data' => $hazard_name])
                @if((request()->has('assess_type') && request()->assess_type == ASSESSMENT_WATER_TYPE)
                    || ($assessment && $assessment->classification == ASSESSMENT_WATER_TYPE)
                    || (request()->has('assess_type') && request()->assess_type == ASSESSMENT_HS_TYPE)
                    || ($assessment && $assessment->classification == ASSESSMENT_HS_TYPE)
                    )
                    @include('shineCompliance.forms.form_dropdown',['title' =>'Hazard Type:',
                    'data' => $hazard_types ?? [], 'name' => 'type', 'key'=>'id', 'value'=>'description', 'required' => true])
                @elseif((request()->has('assess_type') && request()->assess_type == ASSESSMENT_FIRE_TYPE)
                    || ($assessment && $assessment->classification == ASSESSMENT_FIRE_TYPE))

                    @include('shineCompliance.forms.form_dropdown',['title' =>'Hazard Type:','id'=> 'parent_type',
                    'data' => $hazard_types->where('parent_id', 0) ?? [], 'name' => 'type', 'key'=>'id', 'value'=>'description', 'required' => true])

                    @include('shineCompliance.forms.form_datepicker',['title' => 'Date Created:', 'name' => 'created_date', 'data' => date('d/m/Y'), 'required' => true])
                @endif

                @include('shineCompliance.forms.form_dropdown',['title' => 'Reason for Inaccessibility:',
                'data' => $inaccessible_reason ?? [], 'name' => 'reason','key'=> 'id', 'value'=>'description', 'compare_value' => ''])
                <div class="row register-form mb-4 acm-child" id="reason-other-form">
                    <div class="col-md-5 offset-md-3">
                        <input type="text" class="form-control" name="reason_other" id="reason-other" placeholder="Please add other Reason">
                    </div>
                </div>

                @include('shineCompliance.forms.form_dropdown_area',['title' => 'Floor:', 'name' => 'area', 'enableNA' => true,
                'required' => true, 'key'=>'id', 'value'=>'title', 'data' => $areas])
                @include('shineCompliance.forms.form_dropdown_area',['title' => 'Room:', 'name' => 'location',
                'required' => true, 'key'=>'id', 'value'=>'title', 'data' => []])
                @if((request()->has('assess_type') && request()->assess_type != ASSESSMENT_FIRE_TYPE)
                    || ($assessment && $assessment->classification != ASSESSMENT_FIRE_TYPE))
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Specific Location:', 'data' => $specificLocations, 'name' => 'specificLocations1','id' => 'specificLocations', 'key'=> 'id', 'value'=>'description' ])
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
                                <input type="text" class="form-control" name="specificLocations-other" placeholder="Please add other specific locations">
                            </div>
                        </div>
                    </div>
                @endif
                @include('shineCompliance.forms.form_dropdown',['title' =>'Hazard Potential:', 'form_class' => 'calculate_score',
                'data' => $hazard_potentials ?? [], 'name' => 'hazard_potential', 'key'=>'id', 'value'=>'description','required' => true,
                'option_data'=>'score'])
                @include('shineCompliance.forms.form_dropdown',['title' =>'Likelihood of Harm:', 'form_class' => 'calculate_score',
                'data' => $hazard_likelihood_harms ?? [], 'name' => 'likelihood_of_harm', 'key'=>'id', 'value'=>'description','required' => true,
                'option_data'=>'score'])
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


                    <div class="row register-form mb-4">
                        <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Extent:</label>
                        <div class="col-md-2" >
                            <input type="text" class="form-control" name="extent" id="extent">
                        </div>
                        <div class="col-md-2" style="max-width: 70%">
                            <select class="form-control" name="measure_id" id="measure_id">
                                <option value="0"></option>
                                @foreach($extends as $extend)
                                    <option value="{{ $extend->id }}">{{ $extend->description }}</option>
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
                            <input type="checkbox" name="photo_override" id="photo_override" value="1">
                            <span class="slider round" id="photo_override"></span>
                        </label>
                    </div>
                </div>

                <div class="row offset-top40" id="img-hazard-off">
                    <div class="col-md-2 mr-5 ml-3">
                        @include('forms.form_photo_hazard',['title' => 'Location:', 'name' => 'location_photo', 'object_id' => 0, 'folder' => HAZARD_LOCATION_PHOTO])
                    </div>
                    <div class="col-md-2 mr-5">
                        @include('forms.form_photo_hazard',['title' => 'Hazard:', 'name' => 'hazard_photo', 'object_id' => 0, 'folder' => HAZARD_PHOTO])
                    </div>
                    <div class="col-md-2">
                        @include('forms.form_photo_hazard',['title' => 'Additional:', 'name' => 'additional_photo', 'object_id' => 0, 'folder' => HAZARD_ADDITION_PHOTO])
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
                'other_value_noun' => NULL,'other_value_verb' => NULL, 'key' => 'id', 'compare_value'=>'','value'=>'description',
                'compare_noun_value' => $noun_id, 'compare_verb_value' => $verb_id])

                @if((request()->has('assess_type') && request()->assess_type == ASSESSMENT_FIRE_TYPE)
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
                                        <option value="{{ $action->id }}">{{ $action->description }}</option>
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
                                        <option value="{{ $action->id }}" data-parent-id="{{ $action->parent_id }}">{{ $action->description }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                @endif
                @if((request()->has('assess_type') && request()->assess_type == ASSESSMENT_HS_TYPE)
                    || ($assessment && $assessment->classification == ASSESSMENT_HS_TYPE))
                    @include('shineCompliance.forms.form_text_area_hazard',['title' => 'Comments:', 'data' => ($hazard_comment ?? ''), 'name' => 'comment','css'=> 'width: 585px;height: 50px!important;'])
                @else
                    @include('shineCompliance.forms.form_text_area_hazard',['title' => 'Comments:', 'data' => ($hazard_comment ?? ''), 'name' => 'comment' ,'required' => true,'css'=> 'width: 585px;height: 50px!important;'])
                @endif
                <div class="row">
                    <div class="offset-md-3">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt add-hazard">
                            <strong>{{ __('Add') }}</strong>
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
            $('#img-hazard-off').show();
            $('#img-hazard-on').hide();

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
                console.log(total);
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
            $('body').on('change', '#parent_type', function(){
                $('#action_recommendation_noun').find("option").hide();
                var hazard_type = $(this).val();
                var data = $('#action_recommendation_noun').find("[data-hazard-type='" + hazard_type + "']").show();
            })

            $('#parent_type').trigger('change');

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

            $('body').on('click', '.add-hazard', function(e){
                e.preventDefault();
                var is_valid = true;
                $('.form-require').each(function(k,v){
                    if($(v).is(':visible') && ($(v).val() == '' || !$(v).val())){
                        is_valid = false;
                        var label = $(v).closest('.parent-element').find('label').html();
                        label = label.replace('*', '');//remove character :*
                        label = label.replace(':', '');//remove character :*
                        var warning_msg = 'The ' + label+ ' field is required!';
                        // console.log(v,warning_msg)
                        showWarning(true, v, warning_msg);
                    }
                });
                if(!is_valid){
                    //scroll to error
                    $('html, body').animate({
                        scrollTop: $(this).closest('form').find('.is-invalid:visible:first').offset().top - 200
                    }, 1000);
                    $('#overlay').fadeOut();
                    return;
                } else {
                    $(this).closest('form').submit();
                }
            });

            $('body').on('change','select[name="type"]',function(){
                var accessibility = $(this).val();
                if (accessibility == "{{HAZARD_TYPE_INACCESSIBLE_ROOM}}") {
                    $('#reason-form').show();
                } else {
                    $('#reason-form').hide();
                }
            });

            $("#reason").change(function(){
                var text = $("#reason").find(":selected").text();
                if (text == 'Other') {
                    $("#reason-other").show();
                } else {
                    $("#reason-other").hide();
                }
            });
            $("#reason").trigger('change');
            $('#reason-form').hide();
        });

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

        $("#specific-form3").hide();
        $("#specificLocations2").hide();

        // on change specific location 1
        $("#specificLocations").change(function(){

            $('#specificLocations3').find('option').remove();
            // show other option if
            var text = $("#specificLocations").find(":selected").text();
            if (text.includes("Other")) {
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

                                $.each(html.data, function(key, value ) {
                                    $('#specificLocations2').append($('<option>', {
                                        value: value.id,
                                        text : value.description
                                    }));
                                });
                                $("#specificLocations2").show().trigger('change');
                                $("#specific-form3").show();
                                // onchangeSpecific2();
                            } else {
                                $('#specificLocations3').find('option').remove();
                                $.each(html.data, function(key, value ) {
                                    $('#specificLocations3').append($('<option>', {
                                        value: value.id,
                                        text : value.description
                                    }));
                                });
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
        });
        //on change specific location 2
        $("#specificLocations2").change(function(){
            getAjaxItemData('specificLocations2','specificLocations3')
        });
        // end of specific location

        function getAjaxItemData(parent_select_id, child_select_id, child_select_id2 , child_select_id3 ) {
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
                        $.each(html.data, function(key, value ) {
                            $('#' + child_select_id).append($('<option>', {
                                value: value.id,
                                text : value.description
                            }));
                        })
                        $('#' + child_select_id).show().trigger('change');
                    } else {
                        $('#' + child_select_id).hide();
                        $('#' + child_select_id2).hide();
                        $('#' + child_select_id3).hide();
                        $("#productDebris1").show();
                    }
                },
            });
        }

    </script>
@endpush
