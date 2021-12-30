@extends('shineCompliance.layouts.app')

@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_edit', 'color' => 'orange', 'data'=> $assessment])
    <div class="container-cus prism-content pad-up pl-0">
        <div class="row">
            <h3 class="col-12 title-row">Edit Assessment Details</h3>
        </div>
        <div class="main-content">
            <form id="form_edit_assessment" method="POST" action="{{ route('shineCompliance.assessment.post_edit', ['assessment_id' => $assessment->id]) }}" enctype="multipart/form-data" class="form-shine">
                @csrf
                <input type="hidden" id="classification" value="{{ $assessment->assess_classification }}">
                @include('shineCompliance.forms.form_dropdown',['title' => 'Assessment Type:', 'name' => 'type', 'data' => $assessmentTypes, 'key' => 'key', 'value' => 'value','compare_value' => $assessment->type])
                @include('shineCompliance.forms.form_datepicker',['title' => 'Due Date:', 'name' => 'due_date', 'data' => $assessment->due_date])
                @include('shineCompliance.forms.form_datepicker',['title' => 'Started Date:', 'name' => 'started_date', 'data' => $assessment->started_date])
                @include('shineCompliance.forms.form_datepicker',['title' => 'Assessment Start Date:', 'name' => 'assess_start_date', 'data' => $assessment->assess_start_date])
                @include('shineCompliance.forms.form_timepicker',['title' => 'Assessment Start Time:', 'name' => 'assess_start_time', 'data' => $assessment->assess_start_time])
                @include('shineCompliance.forms.form_datepicker',['title' => 'Assessment Finished:', 'name' => 'assess_finish_date', 'data' => $assessment->assess_finish_date])
                @include('forms.form_dropdown',['title' => 'Organisation:', 'data' => $clients, 'name' => 'clientKey', 'key'=> 'id', 'value'=>'name', 'compare_value' => $assessment->client_id, 'required' => true ])
                @include('shineCompliance.forms.form_dropdown',['title' => Str::title(str_replace('_',' ',$assessment->assess_classification)) . ' Lead:', 'name' => 'lead_by', 'data' => $leads, 'key' => 'id', 'value' => 'full_name', 'compare_value' => $assessment->lead_by, 'required' => true])
                @include('shineCompliance.forms.form_dropdown',['title' => 'Second ' . Str::title(str_replace('_',' ',$assessment->assess_classification)) . ' Lead:', 'name' => 'second_lead_by', 'data' => $leads, 'key' => 'id', 'value' => 'full_name', 'compare_value' => $assessment->second_lead_by])
                @if($assessment->assess_classification == "H&S")
                    @include('forms.form_select_ajax',['title' => 'H&S Assessor:', 'name' => 'assessor_id', 'compare_value' => $assessment->assessor_id, 'required' => true ])
                @else
                    @include('forms.form_select_ajax',['title' => Str::title(str_replace('_',' ',$assessment->assess_classification)) . ' Risk Assessor:', 'name' => 'assessor_id', 'compare_value' => $assessment->assessor_id, 'required' => true ])
                @endif
                @include('forms.form_select_ajax',['title' => 'Quality Checked By:', 'name' => 'quality_checker' , 'compare_value' => $assessment->quality_checker, 'required' => true])
{{--                @include('shineCompliance.forms.form_dropdown',['title' => 'Quality Checked By:', 'name' => 'quality_checker', 'data' => $leads, 'key' => 'id', 'value' => 'full_name', 'compare_value' => $assessment->quality_checker, 'required' => true])--}}
                @include('shineCompliance.forms.form_dropdown',['title' => 'Linked Project:', 'name' => 'project_id', 'data' => $projects, 'key' => 'id', 'value' => 'reference', 'compare_value' => $assessment->project_id])
{{--                @include('shineCompliance.forms.form_dropdown',['title' => 'Link Work Request:', 'name' => 'work_request_id', 'compare_value' => $assessment->work_request_id])--}}
                @include('shineCompliance.forms.form_checkbox',['title' => 'Equipment Details:', 'name' => 'setting_equipment_details', 'data'=> 1, 'compare' => optional($assessment->assessmentInfo)->setting_equipment_details])
                @if(($assessment->classification == ASSESSMENT_FIRE_TYPE && in_array($assessment->type, ASSESS_TYPE_FIRE_RISK_ALL_TYPE)) || $assessment->classification == ASSESSMENT_HS_TYPE)
                        @include('shineCompliance.forms.form_checkbox',['title' => 'Nonconformities:', 'name' => 'setting_non_conformities', 'data'=> 1, 'compare' => optional($assessment->assessmentInfo)->setting_non_conformities])
                        @include('shineCompliance.forms.form_checkbox',['title' => 'Property Size/volume Details:', 'name' => 'setting_property_size_volume', 'data'=> 1, 'compare' => optional($assessment->assessmentInfo)->setting_property_size_volume])
                        @include('shineCompliance.forms.form_checkbox',['title' => 'Fire Safety Systems & Equipment:', 'name' => 'setting_fire_safety', 'data'=> 1, 'compare' => optional($assessment->assessmentInfo)->setting_fire_safety])
                        @include('shineCompliance.forms.form_checkbox',['title' => 'Vehicle Parking:', 'name' => 'setting_show_vehicle_parking', 'data'=> 1, 'compare' => optional($assessment->assessmentInfo)->setting_show_vehicle_parking])
                @endif
                @include('shineCompliance.forms.form_checkbox',['title' => 'Item Photos Required:', 'name' => 'setting_hazard_photo_required', 'data'=> 1, 'compare' => optional($assessment->assessmentInfo)->setting_hazard_photo_required])
                @include('shineCompliance.forms.form_checkbox',['title' => 'Assessors Notes Required:', 'name' => 'setting_assessors_note_required', 'data'=> 1, 'compare' => optional($assessment->assessmentInfo)->setting_assessors_note_required])

                <a href="">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" id="submit" class="btn light_grey_gradient_button fs-8pt">
                            Save
                        </button>
                    </div>
                </a>
            </form>
        </div>
    </div>
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            showSettingVehicleParking();
            $('#type').on('change', function (){
                showSettingVehicleParking();
            });

            function showSettingVehicleParking() {
                if ($('#classification').val() == 'fire') {
                    if($('#type').val() == 2) {
                        $('#setting_show_vehicle_parking-form').show();
                    } else {
                        $('#setting_show_vehicle_parking-form').hide();
                    }
                }
            }
            {{--function getUserAssessor() {--}}
            {{--    $("#assessor_id").html("");--}}
            {{--    $.ajax--}}
            {{--    ({--}}
            {{--        type: "GET",--}}
            {{--        url: "/compliance/ajax/client-users-assessment/" + {{ \Auth::user()->client_id ?? "" }},--}}
            {{--        data: {},--}}
            {{--        cache: false,--}}
            {{--        success: function (html) {--}}
            {{--            if (html) {--}}
            {{--                $.each( html, function( key, value ) {--}}
            {{--                    var selected_assessor_id = {{ isset($assessment->assessor_id) ? $assessment->assessor_id : 0 }};--}}
            {{--                    loadOptionAjax('assessor_id',value,selected_assessor_id);--}}
            {{--                });--}}
            {{--            }--}}
            {{--        }--}}
            {{--    });--}}
            {{--}--}}

            getUserSurvey();
            $("#clientKey").change(function () {
                getUserSurvey();
            });

            function getUserSurvey() {
                var client_id =  $('#clientKey').val();
                $("#assessor_id").html("");
                $("#quality_checker").html("");
                $.ajax
                ({
                    type: "GET",
                    url: "/compliance/ajax/client-users-assessment/" + client_id,
                    data: {},
                    cache: false,
                    success: function (html) {
                        if (html) {
                            $.each(html, function (key, value) {
                                var selected_assessor_id = {{ isset($assessment->assessor_id) ? $assessment->assessor_id : 0 }};
                                loadOptionAjax('assessor_id', value, selected_assessor_id);

                                var selected_quality_checker = {{ isset($assessment->quality_checker) ? $assessment->quality_checker : 0 }};
                                loadOptionAjax('quality_checker',value,selected_quality_checker);
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
        });
    </script>
@endpush
