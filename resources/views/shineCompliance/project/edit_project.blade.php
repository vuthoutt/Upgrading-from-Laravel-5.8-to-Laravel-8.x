@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav', ['breadCrumb' => 'project_edit_shineCompliance','color' => 'blue', 'data' => $project])

<div class="container-cus prism-content">
  <div class="row">
      <h3 class="title-row">Edit Project</h3>
  </div>
    <div class="main-content">
        <form method="POST" action="{{ route('project.post_edit', ['project_id' => $project->id]) }}" enctype="multipart/form-data">
        @csrf
            @include('shineCompliance.forms.form_input_hidden',['name' => 'client_id', 'data' => $project->client_id ])
            @include('shineCompliance.forms.form_input_hidden',['name' => 'property_id', 'data' => $project->property_id ])
            @include('shineCompliance.forms.form_input_hidden',['name' => 'status', 'data' => $project->status ])
            @include('shineCompliance.forms.form_text',['title' => 'Property:', 'data' => optional($project->property)->name ])
            @include('shineCompliance.forms.form_input',['title' => 'Project Title:', 'name' => 'title', 'data' => $project->title, 'required' => true ])
            @include('forms.form_dropdown',['title' => 'Project Risk Classification:', 'data' => $project_classification, 'name' => 'risk_classification_id', 'key'=> 'id', 'value'=>'description', 'required' => true, 'no_first_op' => true,'compare_value' => $project->risk_classification_id])
            @include('forms.form_dropdown',['title' => 'Project Type:', 'data' => $project_types, 'name' => 'project_type', 'key'=> 'id', 'value'=>'description', 'required' => true, 'compare_value' => $project->project_type, 'option_data' => 'compliance_type' ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Workstream/Programme:', 'data' => $work_streams, 'name' => 'work_stream', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->work_stream, 'required' => false ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Project Enquiry Date:', 'name' => 'enquiry_date', 'data' => $project->enquiry_date ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Project Start Date:', 'name' => 'date', 'data' => $project->date ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Project Completion Date:', 'name' => 'due_date', 'data' => $project->due_date ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Asbestos Lead:', 'data' => $asbestos_leads, 'name' => 'lead_key', 'key'=> 'id', 'value'=>'full_name', 'required' => true, 'compare_value' => $project->lead_key ])
            {{-- @include('shineCompliance.forms.form_dropdown',['title' => 'Second Asbestos Lead:', 'data' => $asbestos_leads, 'name' => 'second_lead_key', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $project->second_lead_key ]) --}}
            {{-- @include('shineCompliance.forms.form_dropdown',['title' => 'Sponsor Lead:', 'data' => $sponsor_leads, 'name' => 'sponsor_lead_key', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $project->sponsor_lead_key ]) --}}
            {{-- @include('shineCompliance.forms.form_dropdown',['title' => 'Sponsor:', 'data' => $sponsors, 'name' => 'sponsor_id', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->sponsor_id ]) --}}
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Secondary Asbestos Lead:', 'name' => 'second_lead_key' ])
            @include('shineCompliance.forms.form_input',['title' => 'PO Number:', 'name' => 'job_no', 'data' => $project->job_no])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Linked Survey Type:', 'data' => $survey_types, 'name' => 'survey_type', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->survey_type ])

            <div class="row register-form assessment">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Document(s) for Reference:</label>
                <div class="col-md-5">
                    <div class="form-group">
                        <select class="form-control" multiple="multiple" name="document_id[]" id="documentSelect">
                            @if($document_project && count($document_project))
                                @foreach($document_project as $document)
                                    <option value="{{ $document->id }}">{{ $document->reference }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Survey(s) for reference:</label>
                <div class="col-md-5">
                    <div class="form-group">
                        <select class="form-control" multiple="multiple" name="survey_id[]" id="surveySelect">
                            @if(!is_null($surveys))
                                @foreach($surveys as $survey)
                                    <option value="{{ $survey->id }}">{{ $survey->reference }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            @include('shineCompliance.forms.form_dropdown',['title' => 'Linked Project Type:', 'data' => $project_types, 'name' => 'linked_project_type', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->linked_project_type ])

            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Linked Project:</label>
                <div class="col-md-5">
                    <div class="form-group">
                        <select class="form-control" multiple="multiple" name="linked_project_id[]" id="projectSelect">
                            @if(!is_null($linked_projects))
                                @foreach($linked_projects as $p)
                                    <option value="{{ $p->id }}">{{ $p->reference }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            @include('shineCompliance.forms.form_dropdown',['title' => 'R&R Condition:', 'data' => $rr_conditions, 'name' => 'rr_condition', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->rr_condition ])
            @include('shineCompliance.forms.form_text_area',['title' => 'Comments:', 'name' => 'comments', 'data' => $project->comments ])

             <div class="row register-form {{ isset($class_other) ? $class_other : '' }}">
                <label for="first-name" class="col-md-{{ isset($width_label) ? $width_label : 3 }}  col-form-label text-md-left font-weight-bold">
                Contractor Not Required
            </label>
                <div class="col-md-5 mt-1">
                        <label class="switch ">
                        <input type="checkbox" name="contractor_not_required" class="primary {{isset($class) ? $class : ''}}" id="contractor_not_required" {{ $project->contractor_not_required == 1 ? 'checked' : '' }}>
                        <span class="slider round" id="contractor_tick"></span>
                        </label>
                </div>
            </div>
            {{-- contractor --}}
            <div id="load_contractor">

            </div>
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('javascript')

<script type="text/javascript" src="{{ URL::asset('js/shineCompliance/multiple_option.js') }}"></script>
<script type="text/javascript">
    $("#project_type").find('option').hide();
    $("#project_type option[data-option='"+$('#risk_classification_id').val()+"']").show();
    $.ajax
    ({
        type: "GET",
        url: "shineCompliance/ajax/client-users/" + 1,
        data: {},
        cache: false,
        success: function (html) {
            $('#second_lead_key').append($('<option>', {
                    value: 0,
                    text : '------ Please select an option -------'
                }));
            $.each( html, function( key, value ) {
                var selected_second_lead_key = {{ isset($project->second_lead_key) ? $project->second_lead_key : 0 }};
                loadOptionAjax('second_lead_key',value,selected_second_lead_key);
            });
        }
    });

    function loadOptionAjax(id, value, selected_key) {

        selected = false;
        if (selected_key == value.id) {
            selected = true;
        }
        $('#'+id).append($('<option>', {
            value: value.id,
            text : value.first_name + ' ' + value.last_name,
            selected : selected
        }));
    }
    $('#surveySelect').select2({
    placeholder: "Please select an option"
    });
    $('#surveySelect').val(<?php echo json_encode(explode(",",$project->survey_id)); ?>);
    $('#surveySelect').trigger('change'); // Notify any JS components that the value changed

    $('#projectSelect').select2({
        placeholder: "Please select an option"
    });
    $('#projectSelect').val(<?php echo json_encode(explode(",",$project->linked_project_id)); ?>);
    $('#projectSelect').trigger('change'); // Notify any JS components that the value changed

    $("#project_type").change(function(){
        var id = $(this).val();
        $.ajax
            ({
                type: "GET",
                url: "{{ route('shineCompliance.ajax.contractor_select' )}}",
                data: {
                    type_id : id,
                    selected : "{{ $project->contractors }}",
                    edit_project : true,
                    checked_contractors : "{{ $project->checked_contractors }}"
                    },
                cache: false,
                success: function (data) {
                    if (data) {
                        $('#load_contractor').html(data.data)
                        $('#max_option').val(data.total - 1);
                    }
                    else {

                    }
                }
            });
        if (id == 2) {
            $("#rrCondition-form").show();
            $("#survey_type-form").show();
        }else{
            $("#rrCondition-form").hide();
            $("#survey_type-form").hide();
        }
    });
    $("#project_type").trigger('change');

    $("#contractor_tick").click(function(){

       var contractor_not_required = $("#contractor_not_required").prop('checked');
       if (contractor_not_required) {
            $("#load_contractor").show();
       } else {
            $("#load_contractor").hide();
       }
    });
    // $("#contractor_tick").trigger('change');
    var contractor_not_required = $("#contractor_not_required").prop('checked');

    if (contractor_not_required) {
         $("#load_contractor").hide();
    } else {
         $("#load_contractor").show();
    }

    var risk_classification_id = $('#risk_classification_id').val();
    if(risk_classification_id == '{{ASBESTOS_CLASSIFICATION}}'){
        $('.survey_reference').show();
        $('.rcf_assessment_reference').hide();
        $('.assessment_reference').hide();
    } else if(risk_classification_id == '{{RCF_CLASSIFICATION}}') {
        $('.rcf_assessment_reference').show();
        $('.survey_reference').hide();
        $('.assessment_reference').hide();
    } else {
        $('.assessment_reference').show();
        $('.survey_reference').hide();
        $('.rcf_assessment_reference').hide();
        if(risk_classification_id == '{{FIRE_CLASSIFICATION}}'){
            $("#assessmentSelect option[data-option='"+'{{ASSESSMENT_FIRE_TYPE}}'+"']").prop('disabled', false);
            $("#assessmentSelect option[data-option!='"+'{{ASSESSMENT_FIRE_TYPE}}'+"']").prop('disabled', true);
        } else if(risk_classification_id == '{{WATER_CLASSIFICATION}}'){
            $("#assessmentSelect option[data-option='"+'{{ASSESSMENT_WATER_TYPE}}'+"']").prop('disabled', false);
            $("#assessmentSelect option[data-option!='"+'{{ASSESSMENT_WATER_TYPE}}'+"']").prop('disabled', true);
        }
        /*$('#assessmentSelect').val(null).select2({
            placeholder: "Please select an option"
        });*/
    }

    $('body').on('change', '#risk_classification_id', function () {
        var risk_classification_id = $(this).val();
        $("#project_type option").hide();
        $("#project_type option[data-option='"+risk_classification_id+"']").show();
        $("#project_type option").each(function(k,v){
            if ($(v).css('display') != 'none') {
                $(v).prop("selected", true);
                return false;
            }
        });
        if(risk_classification_id == '{{ASBESTOS_CLASSIFICATION}}'){
            $('.survey_reference').show();
            $('.rcf_assessment_reference').hide();
            $('.assessment_reference').hide();
        } else if(risk_classification_id == '{{RCF_CLASSIFICATION}}') {
            $('.rcf_assessment_reference').show();
            $('.survey_reference').hide();
            $('.assessment_reference').hide();
        } else {
            $('.assessment_reference').show();
            $('.survey_reference').hide();
            $('.rcf_assessment_reference').hide();
            if(risk_classification_id == '{{FIRE_CLASSIFICATION}}'){
                $("#assessmentSelect option[data-option='"+'{{ASSESSMENT_FIRE_TYPE}}'+"']").prop('disabled', false);
                $("#assessmentSelect option[data-option!='"+'{{ASSESSMENT_FIRE_TYPE}}'+"']").prop('disabled', true);
            } else if(risk_classification_id == '{{WATER_CLASSIFICATION}}'){
                $("#assessmentSelect option[data-option='"+'{{ASSESSMENT_WATER_TYPE}}'+"']").prop('disabled', false);
                $("#assessmentSelect option[data-option!='"+'{{ASSESSMENT_WATER_TYPE}}'+"']").prop('disabled', true);
            }
            $('#assessmentSelect').val(null).select2();
        }
        $("#project_type").trigger('change');
    });
</script>
@endpush
