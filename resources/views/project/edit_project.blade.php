@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'project_edit','color' => 'blue', 'data' => $project])

<div class="container prism-content">
    <h3>Edit Project</h3>
    <div class="main-content">
        <form method="POST" action="{{ route('project.post_edit', ['project_id' => $project->id]) }}" enctype="multipart/form-data">
        @csrf
            @include('forms.form_input_hidden',['name' => 'client_id', 'data' => $project->client_id ])
            @include('forms.form_input_hidden',['name' => 'property_id', 'data' => $project->property_id ])
            @include('forms.form_input_hidden',['name' => 'status', 'data' => $project->status ])
            @include('forms.form_text',['title' => 'Property:', 'data' => optional($project->property)->name ])
            @include('forms.form_input',['title' => 'Project Title:', 'name' => 'title', 'data' => $project->title, 'required' => true ])
            @include('forms.form_dropdown',['title' => 'Project Risk Classification:', 'data' => $project_classification, 'name' => 'risk_classification_id', 'key'=> 'id', 'value'=>'description', 'required' => true, 'no_first_op' => true,'compare_value' => $project->risk_classification_id])
            @include('forms.form_dropdown',['title' => 'Project Type:', 'data' => $project_types, 'name' => 'project_type', 'key'=> 'id', 'value'=>'description', 'required' => true, 'compare_value' => $project->project_type, 'option_data' => 'compliance_type' ])
            @include('forms.form_dropdown',['title' => 'Workstream/Programme:', 'data' => $work_streams, 'name' => 'work_stream', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->work_stream, 'required' => false ])
            @include('forms.form_datepicker',['title' => 'Project Enquiry Date:', 'name' => 'enquiry_date', 'data' => $project->enquiry_date ])
            @include('forms.form_datepicker',['title' => 'Project Start Date:', 'name' => 'date', 'data' => $project->date ])
            @include('forms.form_datepicker',['title' => 'Project Due Date:', 'name' => 'due_date', 'data' => $project->due_date ])
            @include('forms.form_dropdown',['title' => 'Project Lead:', 'data' => $asbestos_leads, 'name' => 'lead_key', 'key'=> 'id', 'value'=>'full_name', 'required' => true, 'compare_value' => $project->lead_key ])
            @include('forms.form_dropdown',['title' => 'Second Project Lead:', 'data' => $asbestos_leads, 'name' => 'second_lead_key', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $project->second_lead_key ])
            {{-- @include('forms.form_dropdown',['title' => 'Sponsor Lead:', 'data' => $sponsor_leads, 'name' => 'sponsor_lead_key', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $project->sponsor_lead_key ]) --}}
            {{-- @include('forms.form_dropdown',['title' => 'Sponsor:', 'data' => $sponsors, 'name' => 'sponsor_id', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->sponsor_id ]) --}}
            @include('forms.form_input',['title' => 'PO Number:', 'name' => 'job_no', 'data' => $project->job_no])
            @include('forms.form_dropdown',['title' => 'Linked Survey Type:', 'data' => $survey_types, 'name' => 'survey_type', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->survey_type ])

            <div class="row register-form assessment">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Document(s) for Reference:</label>
                <div class="col-md-5">
                    <div class="form-group">
                        <select class="form-control" multiple="multiple" name="document_id[]" id="documentSelect">
                            @if($document_project && count($document_project))
                                @foreach($document_project as $document)
                                    <option value="{{ $document->id }}">{{ $document->reference . ' - ' . $document->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="row register-form assessment survey_reference">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Assessment(s) for Reference:</label>
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

            <div class="row register-form assessment assessment_reference">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Assessment(s) for Reference:</label>
                <div class="col-md-5">
                    <div class="form-group">
                        <select class="form-control" multiple="multiple" name="assessment_id[]" id="assessmentSelect">
                            @if($assessments && count($assessments))
                                @foreach($assessments as $assessment)
                                    <option value="{{ $assessment->id }}" data-option="{{$assessment->classification}}">{{ $assessment->reference }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="row register-form assessment assessment_reference">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Hazard(s) for Reference:</label>
                <div class="col-md-5">
                    <div class="form-group">
                        <select class="form-control" multiple="multiple" name="hazard_id[]" id="hazardSelect">
                            @if($hazard_project && count($hazard_project))
                                @foreach($hazard_project as $hazard)
                                    <option value="{{ $hazard->id }}">{{ ($hazard->reference ?? ''). ' - ' .($hazard->hazardType->description ?? ''). ' - ' .($hazard->action_recommendations ?? '') }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            @include('forms.form_dropdown',['title' => 'Linked Project Type:', 'data' => $project_types, 'name' => 'linked_project_type', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->linked_project_type ])

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
            @include('forms.form_dropdown',['title' => 'R&R Condition:', 'data' => $rr_conditions, 'name' => 'rr_condition', 'key'=> 'id', 'value'=>'description', 'compare_value' => $project->rr_condition ])
            @include('forms.form_text_area',['title' => 'Comments:', 'name' => 'comments', 'data' => $project->comments ])

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
                <button type="submit" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('javascript')

<script type="text/javascript" src="{{ URL::asset('js/multiple_option.js') }}"></script>
<script type="text/javascript">
    $("#project_type").find('option').hide();
    $("#project_type option[data-option='"+$('#risk_classification_id').val()+"']").show();

    $('#surveySelect').select2({
    placeholder: "Please select an option"
    });

    $('#documentSelect').select2({
    placeholder: "Please select an option"
    });

    $('#documentSelect').val(<?php echo json_encode(explode(",",$project->document_ids)); ?>);
    $('#documentSelect').trigger('change'); // Notify any JS components that the value changed

    $('#hazardSelect').select2({
        placeholder: "Please select an option"
    });
    $('#hazardSelect').val(<?php echo json_encode(explode(",",$project->hazard_ids)); ?>);
    $('#hazardSelect').trigger('change'); // Notify any JS components that the value changed

    $('#surveySelect').val(<?php echo json_encode(explode(",",$project->survey_id)); ?>);
    $('#surveySelect').trigger('change'); // Notify any JS components that the value changed

    $('#assessmentSelect').select2({
        placeholder: "Please select an option"
    });
    $('#assessmentSelect').val(<?php echo json_encode(explode(",",$project->assessment_ids)); ?>);
    $('#assessmentSelect').trigger('change'); // Notify any JS components that the value changed

    var risk_classification_id = $('#risk_classification_id').val();
    if(risk_classification_id == '{{ASBESTOS_CLASSIFICATION}}'){
        $('.survey_reference').show();
        $('.assessment_reference').hide();
    } else {
        $('.assessment_reference').show();
        $('.survey_reference').hide();
        if(risk_classification_id == '{{FIRE_CLASSIFICATION}}'){
            $("#assessmentSelect option[data-option='"+'{{ASSESSMENT_FIRE_TYPE}}'+"']").prop('disabled', false);
            $("#assessmentSelect option[data-option!='"+'{{ASSESSMENT_FIRE_TYPE}}'+"']").prop('disabled', true);
        } else if(risk_classification_id == '{{WATER_CLASSIFICATION}}'){
            $("#assessmentSelect option[data-option='"+'{{ASSESSMENT_WATER_TYPE}}'+"']").prop('disabled', false);
            $("#assessmentSelect option[data-option!='"+'{{ASSESSMENT_WATER_TYPE}}'+"']").prop('disabled', true);
        }
    }

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
                url: "{{ route('ajax.contractor_select' )}}",
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
            $('.assessment_reference').hide();
        } else {
            $('.assessment_reference').show();
            $('.survey_reference').hide();
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
</script>
@endpush
