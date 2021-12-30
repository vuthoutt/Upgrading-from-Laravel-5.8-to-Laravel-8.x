@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'survey_edit','color' => 'orange', 'data' => $survey])

<div class="container prism-content">
    <h3>Edit Survey</h3>
        <div class="main-content">
            <form method="POST" action="{{ route('survey.post_edit',['survey' => $survey->id]) }}" enctype="multipart/form-data" id="form_edit_survey" class="form-shine">
            @csrf
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Survey Type:</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        <select  class="form-control" name="surveyType" id="surveyType">
                            <option value="{{MANAGEMENT_SURVEY}}" {{ ($survey->survey_type == MANAGEMENT_SURVEY) ? 'selected' : '' }}>Management Survey</option>
                            <option value="{{REFURBISHMENT_SURVEY}}" {{ ($survey->survey_type == REFURBISHMENT_SURVEY) ? 'selected' : '' }}>Refurbishment Survey</option>
                            <option value="{{RE_INSPECTION_REPORT}}" {{ ($survey->survey_type == RE_INSPECTION_REPORT) ? 'selected' : '' }}>Re-inspection Report</option>
                            <option value="{{DEMOLITION_SURVEY}}" {{ ($survey->survey_type == DEMOLITION_SURVEY) ? 'selected' : '' }}>Demolition Survey</option>
                            <option value="{{MANAGEMENT_SURVEY_PARTIAL}}" {{ ($survey->survey_type == MANAGEMENT_SURVEY_PARTIAL) ? 'selected' : '' }}>Management Survey – Partial</option>
                            <option value="{{SAMPLE_SURVEY}}" {{ ($survey->survey_type == SAMPLE_SURVEY) ? 'selected' : '' }}>Sample Survey</option>
                            {{-- <option value="{{NOACM_SURVEY}}" {{ ($survey->survey_type == NOACM_SURVEY) ? 'selected' : '' }}>No ACM Management Survey</option> --}}
                        </select>
                    </div>
                </div>
            </div>
            @include('forms.form_datepicker',['title' => 'Survey Date:', 'data' => $survey->created_at->format('d/m/Y'), 'name' => 'date' ])
            @include('forms.form_datepicker',['title' => 'Survey Due Date:', 'data' => $survey->surveyDate->due_date, 'name' => 'due-date' ])
            @include('forms.form_datepicker',['title' => 'Survey Start Date:', 'data' => $survey->surveyDate->surveying_start_date, 'name' => 'sv-start-date' ])
            @include('forms.form_datepicker',['title' => 'Survey Finish Date:', 'data' => $survey->surveyDate->surveying_finish_date, 'name' => 'sv-finish-date' ])
            @include('forms.form_datepicker',['title' => 'Published Date:', 'data' => $survey->surveyDate->published_date, 'name' => 'published-date' ])
            @include('forms.form_datepicker',['title' => 'Completed Date:', 'data' => $survey->surveyDate->completed_date, 'name' => 'completed-date' ])
            @include('forms.form_dropdown',['title' => 'Organisation:', 'data' => $clients, 'name' => 'clientKey', 'key'=> 'id', 'value'=>'name', 'compare_value' => $survey->client_id, 'required' => true ])
            @include('forms.form_dropdown',['title' => 'Workstream/Programme:', 'data' => $work_streams, 'name' => 'work_stream', 'key'=> 'id', 'value'=>'description', 'compare_value' => $survey->work_stream, 'required' => false ])
            @include('forms.form_input',['title' => 'Survey Cost:', 'data' => $survey->cost ?? '', 'name' => 'cost', 'maxlength' => 200])
            @include('forms.form_input',['title' => 'Organisation Reference:', 'data' => $survey->organisation_reference ?? '', 'name' => 'organisation_reference', 'maxlength' => 200])
            @include('forms.form_dropdown',['title' => 'Asbestos Lead:', 'data' => $surveyUsers, 'name' => 'leadBy', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $survey->lead_by, 'required' => true ])
            @include('forms.form_dropdown',['title' => 'Second Asbestos Lead:', 'data' => $surveyUsers, 'name' => 'secondLeadBy', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $survey->second_lead_by ])
            @include('forms.form_select_ajax',['title' => 'Surveyor:', 'name' => 'surveyor', 'compare_value' => $survey->surveyor_id ])
            @include('forms.form_select_ajax',['title' => 'Second Surveyor:', 'name' => 'secondSurveyor', 'compare_value' => $survey->second_surveyor_id ])
            @include('forms.form_select_ajax',['title' => 'Project Manager:', 'name' => 'consultantKey', 'compare_value' => $survey->consultant_id ])
            <div class="row register-form">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >
                    Linked Project:
                    @if(isset($projects) and !is_null($projects) and !$projects->isEmpty())
                        <span style="color: red;">*</span>
                    @endif
                </label>
                <div class="col-md-5">
                    <div class="form-group ">
                        <select  class="form-control @error('projectKey') is-invalid @enderror" name="projectKey" id="projectKey">
                            @if(isset($projects) and !is_null($projects) and !$projects->isEmpty())
                            <option value="-1">------ Please select a project -------</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ ($survey->project_id == $project->id) ? 'selected' : ''}}>{{ $project->reference }}</option>
                                @endforeach
                            @else
                                <option value="" data-option="0">No Projects</option>
                            @endif
                        </select>
                        @error('projectKey')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
                @include('forms.form_select_ajax',['title' => 'Quality Checked By:', 'name' => 'qualityKey' ])
                @include('forms.form_select_ajax',['title' => 'Analyst:', 'name' => 'analystKey' ])
                @include('forms.form_checkbox_survey',['title' => 'Settings:', 'name' => 'priorityAssessmentRequired','id' => 'priority_ass','title_right' => 'Priority Risk Assessment (PAS)', 'data' => $survey->surveySetting->is_require_priority_assessment,'compare' => 1 ])
                @include('forms.form_checkbox_survey',['name' => 'constructionDetailsRequired','title_right' => 'Property Construction Details','id' => 'construction_detail','data' => $survey->surveySetting->is_require_construction_details,'compare' => 1 ])
                @include('forms.form_checkbox_survey',['name' => 'locationVoidInvestigationsRequired','id' => 'location_void','title_right' => 'Location Void Investigations','data' => $survey->surveySetting->is_require_location_void_investigations,'compare' => 1 ])
                @include('forms.form_checkbox_survey',['name' => 'locationConstructionDetailsRequired','id' => 'location_con','title_right' => 'Location Construction Details','data' => $survey->surveySetting->is_require_location_construction_details,'compare' => 1 ])
                @include('forms.form_checkbox_survey',['name' => 'RDinManagementAllowed','id' => 'r_and_d', 'title_right' => 'R&D Elements','data' => $survey->surveySetting->is_require_r_and_d_elements,'compare' => 1 ])
                @include('forms.form_checkbox_survey',['name' => 'licenseStatusRequired','id' => 'license_status','title_right' => 'Licensed/non Licensed','data' => $survey->surveySetting->is_require_license_status,'compare' => 1 ])
                @include('forms.form_checkbox_survey',['name' => 'photosRequired','id' => 'photo_re','title_right' => 'Item Photos Required','data' => $survey->surveySetting->is_require_photos,'compare' => 1 ])
                @include('forms.form_checkbox_survey',['name' => 'propertyPlanPhoto','id' => 'property_plan','title_right' => 'Surveyors Notes Required','data' => $survey->surveySetting->is_property_plan_photo,'compare' => 1 ])

            <div class="col-md-6 offset-md-3 mt-4">
                <button type="button" id="btn-submit1" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
            @include('forms.form_warning_require_project',['id'=> 'missing_project'])
        </form>
    </div>
</div>
@endsection
@push('javascript')

<script type="text/javascript">
    $(document).ready(function(){
        //refresh token after a specified period of time
        var csrfToken = $('[name="csrf_token"]').attr('content');
        var period_time = 14*60*1000; // 14min
        setInterval(refreshToken, period_time); // 1 hour

        function refreshToken(){
            $.ajax
            ({
                type: "GET",
                url: "/refresh-csrf",
                data: {},
                cache: false,
                success: function (html) {
                    $('[name="csrf_token"]').attr('content', html);
                }
            });
        }
        getUserSurvey();
        $("#clientKey").change(function () {
            getUserSurvey();
        });

        $("#surveyType").change(function () {
            if ($("#surveyType").val() == {{REFURBISHMENT_SURVEY}}) {
                $("#form-r_and_d").css("opacity",0.4);
                $("#r_and_d").val(0);
                $("#r_and_d").prop('disabled', true);
                $('#r_and_d').prop('checked', false);
            }else{
                $("#form-r_and_dd").css("opacity",1);
                $("#r_and_d").removeAttr('disabled');
            }

            if($("#surveyType").val() == {{ SAMPLE_SURVEY }}){
                $("#form-r_and_d").css("opacity",0.4);
                $("#r_and_d").val(0);
                $("#r_and_d").prop('disabled', true);
                $('#r_and_d').prop('checked', false); // Checks it

                $("#form-checkbox-priority_ass").css("opacity",0.4);
                $("#priority_ass").val(0);
                $("#priority_ass").prop('disabled', true);
                $('#priority_ass').prop('checked', false);

                $("#form-construction_detail").css("opacity",0.4);
                $("#construction_detail").val(0);
                $("#construction_detail").prop('disabled', true);
                $('#construction_detail').prop('checked', false);

                $("#form-location_void").css("opacity",0.4);
                $("#location_void").val(0);
                $("#location_void").prop('disabled', true);
                $('#location_void').prop('checked', false);

                $("#form-location_con").css("opacity",0.4);
                $("#location_con").val(0);
                $("#location_con").prop('disabled', true);
                $('#location_con').prop('checked', false);


                $("#form-license_status").css("opacity",0.4);
                $("#license_status").val(0);
                $("#license_status").prop('disabled', true);
                $('#license_status').prop('checked', false);


                $("#form-property_plan").css("opacity",0.4);
                $("#property_plan").val(0);
                $("#property_plan").prop('disabled', true);
                $('#property_plan').prop('checked', false);

            }else{
                if ($("#surveyType").val() != {{REFURBISHMENT_SURVEY}}) {
                    $("#form-r_and_d").css("opacity", 1);
                    $("#r_and_d").val(0);
                    $("#r_and_d").removeAttr('disabled');
                }
                $("#form-checkbox-priority_ass").css("opacity",1);
                $("#priority_ass").removeAttr('disabled');

                $("#form-construction_detail").css("opacity",1);
                $("#construction_detail").removeAttr('disabled');

                $("#form-location_void").css("opacity",1);
                $("#location_void").removeAttr('disabled');

                $("#form-location_con").css("opacity",1);
                $("#location_con").removeAttr('disabled');

                $("#form-license_status").css("opacity",1);
                $("#license_status").removeAttr('disabled');

                $("#form-property_plan").css("opacity",1);
                $("#property_plan").removeAttr('disabled');
            }
        });
        $("#surveyType").trigger('change');

        function getUserSurvey() {
            var client_id =  $('#clientKey').val();
            $("#surveyor").html("");
            $("#secondSurveyor").html("");
            $("#analystKey").html("");
            $("#qualityKey").html("");
            $("#consultantKey").html("");
            $.ajax
            ({
                type: "GET",
                url: "/ajax/client-users/" + client_id,
                data: {},
                cache: false,
                success: function (html) {
                    if (html) {
                        $('#secondSurveyor').append($('<option>', {
                            value: 0,
                            text : ''
                        }));
                        $('#analystKey').append($('<option>', {
                            value: 0,
                            text : ''
                        }));
                        $.each( html, function( key, value ) {
                            var selected_surveyor = {{ isset($survey->surveyor_id) ? $survey->surveyor_id : 0 }};
                            var selected_secondSurveyor = {{ isset($survey->second_surveyor_id) ? $survey->second_surveyor_id : 0 }};
                            var selected_quality_id = {{ isset($survey->quality_id) ? $survey->quality_id : 0 }};
                            var selected_analyst_id = {{ isset($survey->analyst_id) ? $survey->analyst_id : 0 }};
                            var selected_consultantKey = {{ isset($survey->consultant_id) ? $survey->consultant_id : 0 }};

                            loadOptionAjax('surveyor',value,selected_surveyor);
                            loadOptionAjax('secondSurveyor',value,selected_secondSurveyor);
                            loadOptionAjax('qualityKey',value,selected_quality_id);
                            loadOptionAjax('analystKey',value,selected_analyst_id);
                            loadOptionAjax('consultantKey',value,selected_consultantKey);


                        });
                    }
                }
            });
        }

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
        $('#btn-submit1').click(function(e){ //No Project or have to select a project
            e.preventDefault();
            $('#form_edit_survey').submit();
            // if($('#projectKey').val() != '-1'){
            //     $('#missing_project').hide();
            //     $('#form_edit_survey').submit();
            // } else {
            //     $('#missing_project').show();
            //     return;
            // }
        });
    });
</script>
@endpush
