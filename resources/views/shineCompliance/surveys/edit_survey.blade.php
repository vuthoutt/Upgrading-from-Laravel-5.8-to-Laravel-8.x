@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav', ['color' => 'orange', 'data' => $survey])

<div class="container-cus prism-content">
    <div class="row">
        <h3 class="title-row">Edit Survey</h3>
    </div>
        <div class="main-content">
        <form method="POST" action="{{ route('shineCompliance.survey.post_edit',['survey' => $survey->id]) }}" enctype="multipart/form-data" id="form_edit_survey" class="form-shine">
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
            @include('shineCompliance.forms.form_datepicker',['title' => 'Survey Date:', 'data' => $survey->created_at->format('d/m/Y'), 'name' => 'date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Survey Due Date:', 'data' => $survey->surveyDate->due_date ?? '', 'name' => 'due-date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Survey Start Date:', 'data' => $survey->surveyDate->surveying_start_date ?? '', 'name' => 'sv-start-date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Survey Finish Date:', 'data' => $survey->surveyDate->surveying_finish_date ?? '', 'name' => 'sv-finish-date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Published Date:', 'data' => $survey->surveyDate->published_date ?? '', 'name' => 'published-date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Completed Date:', 'data' => $survey->surveyDate->completed_date ?? '', 'name' => 'completed-date' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Samples Sent to Lab:', 'data' => $survey->surveyDate->sample_sent_to_lab_date ?? '', 'name' => 'sample-sent-to-lab-date' ])
            {{-- @include('shineCompliance.forms.form_datepicker',['title' => 'Samples Received from Lab:', 'data' => $survey->surveyDate->sample_received_from_lab_date, 'name' => 'sample-received-from-lab-date' ]) --}}
            @include('shineCompliance.forms.form_dropdown',['title' => 'Organisation:', 'data' => $clients, 'name' => 'clientKey', 'key'=> 'id', 'value'=>'name', 'compare_value' => $survey->client_id ?? '', 'required' => true ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Workstream/Programme:', 'data' => $work_streams, 'name' => 'work_stream', 'key'=> 'id', 'value'=>'description', 'compare_value' => $survey->work_stream ?? '', 'required' => false ])
            @include('shineCompliance.forms.form_input',['title' => 'Survey Cost:', 'data' => $survey->cost ?? '', 'name' => 'cost', 'maxlength' => 200])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Asbestos Lead:', 'data' => $surveyUsers, 'name' => 'leadBy', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $survey->lead_by, 'required' => true ])
            {{-- @include('shineCompliance.forms.form_dropdown',['title' => 'Second Asbestos Lead:', 'data' => $surveyUsers, 'name' => 'secondLeadBy', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $survey->second_lead_by ]) --}}
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Surveyor:', 'name' => 'surveyor', 'compare_value' => $survey->surveyor_id ?? ''])
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Secondary Asbestos Lead:', 'name' => 'secondLeadBy' ])
            {{-- @include('shineCompliance.forms.form_select_ajax',['title' => 'Second Surveyor:', 'name' => 'secondSurveyor', 'compare_value' => $survey->second_surveyor_id ]) --}}
            @include('shineCompliance.forms.form_dropdown',['title' => 'Second Surveyor:', 'data' => $secondSurveyors, 'name' => 'secondSurveyor', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $survey->second_surveyor_id ?? ''])
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Project Manager:', 'name' => 'consultantKey', 'compare_value' => $survey->consultant_id ?? ''])
            {{-- @include('shineCompliance.forms.form_select_ajax',['title' => 'CAD Technician:', 'name' => 'cad_tech' ]) --}}
{{--            @include('shineCompliance.forms.form_dropdown',['title' => 'CAD Technician:', 'data' => $cadTechnicans, 'name' => 'cad_tech', 'key'=> 'id', 'value'=>'full_name', 'compare_value' => $survey->cad_tech_id])--}}
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
{{--            @include('shineCompliance.forms.form_select_ajax',['title' => 'Quality Checked By:', 'name' => 'qualityKey' ])--}}
            @include('shineCompliance.forms.form_select_ajax',['title' => 'Analyst:', 'name' => 'analystKey' ])
            @include('shineCompliance.forms.form_checkbox_survey',['title' => 'Settings:', 'name' => 'priorityAssessmentRequired','title_right' => 'Priority Risk Assessment (PAS)', 'data' => $survey->surveySetting->is_require_priority_assessment ?? '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'constructionDetailsRequired','title_right' => 'Property Construction Details','data' => $survey->surveySetting->is_require_construction_details ?? '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'locationVoidInvestigationsRequired','title_right' => 'Location Void Investigations','data' => $survey->surveySetting->is_require_location_void_investigations ?? '','compare' => 1,'id' => 'location_void_required' ])
{{--             <div id="location_void" style="margin-left: 90px">
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'all_void','title_right' => 'Select All Voids','data' => '','compare' => 1, 'id' => 'location_void_all' ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'ceiling_void','title_right' => 'Ceiling Void','data' => $survey->surveySetting->ceiling_void,'compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'floor_void','title_right' => 'Floor Void','data' => $survey->surveySetting->floor_void,'compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'cavities','title_right' => 'Cavities','data' => $survey->surveySetting->cavities,'compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'risers','title_right' => 'Risers','data' => $survey->surveySetting->risers,'compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'ducting','title_right' => 'Ducting','data' => $survey->surveySetting->ducting,'compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'boxing','title_right' => 'Boxing','data' => $survey->surveySetting->boxing,'compare' => 1 ])
                @include('shineCompliance.forms.form_checkbox_survey',['class' => 'location_void','name' => 'pipework','title_right' => 'Pipework','data' => $survey->surveySetting->pipework,'compare' => 1 ])
            </div> --}}
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'locationConstructionDetailsRequired','title_right' => 'Location Construction Details','data' => $survey->surveySetting->is_require_location_construction_details ?? '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'RDinManagementAllowed','id' => 'r_and_d', 'title_right' => 'R&D Elements','data' => $survey->surveySetting->is_require_r_and_d_elements ?? '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'licenseStatusRequired','title_right' => 'Licensed/non Licensed','data' => $survey->surveySetting->is_require_license_status ?? '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'photosRequired','title_right' => 'Item Photos Required','data' => $survey->surveySetting->is_require_photos ?? '','compare' => 1 ])
            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'propertyPlanPhoto','title_right' => 'Surveyors Notes Required','data' => $survey->surveySetting->is_property_plan_photo ?? '','compare' => 1 ])
{{--            @include('shineCompliance.forms.form_checkbox_survey',['name' => 'external_laboratory','id'=> 'external_laboratory','title_right' => 'External Laboratory','data' => $survey->surveySetting->external_laboratory ?? 0,'compare' => 1 ])--}}
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="button" id="btn-submit1" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
            @include('shineCompliance.forms.form_warning_require_project',['id'=> 'missing_project'])
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

        @if($survey->survey_type == SAMPLE_SURVEY )
            $("#form-RDinManagementAllowed").css("opacity",0.4);
            $("#RDinManagementAllowed").val(0);
            $("#RDinManagementAllowed").prop('disabled', true);

            $("#form-priorityAssessmentRequired").css("opacity",0.4);
            $("#priorityAssessmentRequired").val(0);
            $("#priorityAssessmentRequired").prop('disabled', true);

            $("#form-constructionDetailsRequired").css("opacity",0.4);
            $("#constructionDetailsRequired").val(0);
            $("#constructionDetailsRequired").prop('disabled', true);

            $("#form-locationVoidInvestigationsRequired").css("opacity",0.4);
            $("#locationVoidInvestigationsRequired").val(0);
            $("#locationVoidInvestigationsRequired").prop('disabled', true);

            $("#form-locationConstructionDetailsRequired").css("opacity",0.4);
            $("#locationConstructionDetailsRequired").val(0);
            $("#locationConstructionDetailsRequired").prop('disabled', true);

            $("#form-licenseStatusRequired").css("opacity",0.4);
            $("#locationConstructionDetailsRequired").val(0);
            $("#licenseStatusRequired").prop('disabled', true);

        @endif

        $("#surveyType").change(function () {
            var sample_type = $("#surveyType").val();
            if ($("#surveyType").val() == {{REFURBISHMENT_SURVEY}}) {
                $("#form-RDinManagementAllowed").css("opacity",0.4);
                $("#RDinManagementAllowed").val(0);
                $("#RDinManagementAllowed").prop('disabled', true);
            }else{
                $("#form-RDinManagementAllowed").css("opacity",1);
                $("#RDinManagementAllowed").removeAttr('disabled');
            }
            if(sample_type == {{ SAMPLE_SURVEY }}){
                $("#form-RDinManagementAllowed").css("opacity",0.4);
                $("#RDinManagementAllowed").val(0);
                $("#RDinManagementAllowed").prop('disabled', true);

                $("#form-priorityAssessmentRequired").css("opacity",0.4);
                $("#priorityAssessmentRequired").val(0);
                $("#priorityAssessmentRequired").prop('disabled', true);

                $("#form-constructionDetailsRequired").css("opacity",0.4);
                $("#constructionDetailsRequired").val(0);
                $("#constructionDetailsRequired").prop('disabled', true);

                $("#form-locationVoidInvestigationsRequired").css("opacity",0.4);
                $("#locationVoidInvestigationsRequired").val(0);
                $("#locationVoidInvestigationsRequired").prop('disabled', true);

                $("#form-locationConstructionDetailsRequired").css("opacity",0.4);
                $("#locationConstructionDetailsRequired").val(0);
                $("#locationConstructionDetailsRequired").prop('disabled', true);

                $("#form-licenseStatusRequired").css("opacity",0.4);
                $("#locationConstructionDetailsRequired").val(0);
                $("#licenseStatusRequired").prop('disabled', true);

            }else{
                if($("#surveyType").val() != {{ REFURBISHMENT_SURVEY }}) {
                    $("#form-RDinManagementAllowed").css("opacity", 1);
                    $("#RDinManagementAllowed").removeAttr('disabled');
                }

                $("#form-priorityAssessmentRequired").css("opacity",1);
                $("#priorityAssessmentRequired").removeAttr('disabled');

                $("#form-constructionDetailsRequired").css("opacity",1);
                $("#constructionDetailsRequired").removeAttr('disabled');

                $("#form-locationVoidInvestigationsRequired").css("opacity",1);
                $("#locationVoidInvestigationsRequired").removeAttr('disabled');

                $("#form-locationConstructionDetailsRequired").css("opacity",1);
                $("#locationConstructionDetailsRequired").removeAttr('disabled');

                $("#form-licenseStatusRequired").css("opacity",1);
                $("#licenseStatusRequired").removeAttr('disabled');

                $("#form-propertyPlanPhoto").css("opacity",1);
                $("#propertyPlanPhoto").removeAttr('disabled');

                $("#form-external_laboratory").css("opacity",1);
                $("#external_laboratory").removeAttr('disabled');
            }
        });
        $("#surveyType").trigger('change');


        $("#location_void_required").change(function () {
            var location_void_required = $(this).is(':checked');
            if (location_void_required) {
                 $("#location_void").show();
            } else {
                $("#location_void").hide();
                $(".location_void").prop( "checked", false );
            }
        });
        $("#location_void_required").trigger('change');

        $("#location_void_all").change(function () {
            var location_void_all = $(this).is(':checked');
            if (location_void_all) {
                 $(".location_void").prop( "checked", true );
            } else {
                $(".location_void").prop( "checked", false );
            }
        });


        $.ajax
        ({
            type: "GET",
            url: "/ajax/client-users/" + 1,
            data: {},
            cache: false,
            success: function (html) {
                $('#secondLeadBy').append($('<option>', {
                    value: 0,
                    text : '------ Please select an option -------'
                }));
                $.each( html, function( key, value ) {
                    var selected_secondLeadBy = {{ isset($survey->second_lead_by) ? $survey->second_lead_by : 0 }};
                    loadOptionAjax('secondLeadBy',value,selected_secondLeadBy);
                });
            }
        });


        function getUserSurvey() {
            var client_id =  $('#clientKey').val();
            if (client_id == 1) {
                $("#form-external_laboratory").show();
            } else {
                $("#form-external_laboratory").hide();
            }
            $("#surveyor").html("");
            // $("#secondSurveyor").html("");
            // $("#cad_tech").html("");
            $("#analystKey").html("");
            $("#qualityKey").html("");
            $("#consultantKey").html("");

            $.ajax
            ({
                type: "GET",
                url: "/ajax/client-users-CO1/" + client_id,
                data: {},
                cache: false,
                success: function (html) {
                    $('#surveyor').append($('<option>', {
                        value: 0,
                        text : '------ Please select an option -------'
                    }));
                        $.each( html, function( key, value ) {
                            var selected_surveyor = {{ isset($survey->surveyor_id) ? $survey->surveyor_id : 0 }};
                            loadOptionAjax('surveyor',value,selected_surveyor);
                        });
                    }
            });

            $.ajax
            ({
                type: "GET",
                url: "/ajax/client-users-QA",
                data: {},
                cache: false,
                success: function (html) {
                    $('#qualityKey').append($('<option>', {
                        value: 0,
                        text : '------ Please select an option -------'
                    }));
                    $.each( html, function( key, value ) {
                        var selected_surveyqa = {{ isset($survey->quality_id) ? $survey->quality_id : 0 }};
                        loadOptionAjax('qualityKey',value,selected_surveyqa);
                    });
                }
            });

            $.ajax
            ({
                type: "GET",
                url: "/ajax/client-users/" + client_id,
                data: {},
                cache: false,
                success: function (html) {
                    if (html) {
                        // $('#secondSurveyor').append($('<option>', {
                        //     value: 0,
                        //     text : '------ Please select an option -------'
                        // }));
                        // $('#cad_tech').append($('<option>', {
                        //     value: 0,
                        //     text : '------ Please select an option -------'
                        // }));
                        $('#consultantKey').append($('<option>', {
                            value: 0,
                            text : '------ Please select an option -------'
                        }));
                        $('#analystKey').append($('<option>', {
                            value: 0,
                            text : '------ Please select an option -------'
                        }));
                        $.each( html, function( key, value ) {
                            {{-- var selected_secondSurveyor = {{ isset($survey->second_surveyor_id) ? $survey->second_surveyor_id : 0 }}; --}}
                            var selected_analyst_id = {{ isset($survey->analyst_id) ? $survey->analyst_id : 0 }};
                            var selected_consultantKey = {{ isset($survey->consultant_id) ? $survey->consultant_id : 0 }};
                            // var selected_cadTech = {{ isset($survey->cad_tech_id) ? $survey->cad_tech_id : 0 }};

                            // loadOptionAjax('secondSurveyor',value,selected_secondSurveyor);
                            loadOptionAjax('analystKey',value,selected_analyst_id);
                            loadOptionAjax('consultantKey',value,selected_consultantKey);
                            // loadOptionAjax('cad_tech',value,selected_cadTech);

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
            if($('#projectKey').val() != '-1'){
                $('#missing_project').hide();
                $('#form_edit_survey').submit();
            } else {
                $('#missing_project').show();
                return;
            }
        });
    });
</script>
@endpush
