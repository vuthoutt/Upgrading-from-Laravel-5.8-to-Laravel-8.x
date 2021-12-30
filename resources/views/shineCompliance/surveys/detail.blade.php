 <div class="row offset-top40">
    <div class="col-md-12">
        @include('forms.form_text',['title' => 'Survey Reference:', 'data' => $data->reference ])
        @include('forms.form_text',['title' => 'Created Date:', 'data' => $data->created_at->format('d/m/Y') ])
        @include('forms.form_text',['title' => 'Due Date:', 'data' => optional($data->surveyDate)->due_date ])
        @include('forms.form_text',['title' => 'Started Date:', 'data' => optional($data->surveyDate)->started_date ])
        @include('forms.form_text',['title' => 'Surveying Start:', 'data' => optional($data->surveyDate)->surveying_start_date ])
        @include('forms.form_text',['title' => 'Surveying Finished:', 'data' => optional($data->surveyDate)->surveying_finish_date ])
        @include('forms.form_text',['title' => 'Published Date:', 'data' => optional($data->surveyDate)->published_date ])
        @include('forms.form_text',['title' => 'Completed Date:', 'data' => optional($data->surveyDate)->completed_date ])
        @include('forms.form_text',['title' => 'Samples Sent to Lab:', 'data' => optional($data->surveyDate)->sample_sent_to_lab_date ])
        {{-- @include('forms.form_text',['title' => 'Samples Received from Lab:', 'data' => optional($data->surveyDate)->sample_received_from_lab_date ]) --}}
        @include('forms.form_text',['title' => 'Status:', 'data' => $data->status_text ])
        @if($data->status == 8)
            @include('forms.form_text',['title' => 'Reason:', 'data' => $data->surveyReason->description ?? '' ])
        @endif
        @include('forms.form_text',['title' => 'Commissioned:', 'data' => CommonHelpers::getUserFullname($data->created_by) ])
        @include('forms.form_text',['title' => 'Workstream/Programme:', 'data' => $data->workStream->description ?? '' ])
        @include('forms.form_text',['title' => 'Asbestos Lead:', 'data' => CommonHelpers::getUserFullname($data->lead_by) ])
        {{-- @include('forms.form_text',['title' => 'Second Asbestos Lead:', 'data' => CommonHelpers::getUserFullname($data->second_lead_by) ]) --}}
        @include('forms.form_text',['title' => 'Surveyor:', 'data' => CommonHelpers::getUserFullname($data->surveyor_id) ])
        @include('forms.form_text',['title' => 'Secondary Asbestos Lead:', 'data' => CommonHelpers::getUserFullname($data->second_lead_by) ])
        @include('forms.form_text',['title' => 'Second Surveyor:', 'data' => CommonHelpers::getUserFullname($data->second_surveyor_id) ])
        @include('forms.form_text',['title' => 'Project Manager:', 'data' => CommonHelpers::getUserFullname($data->consultant_id) ])
        @include('forms.form_text',['title' => 'CAD Technician:', 'data' => CommonHelpers::getUserFullname($data->cad_tech_id) ])
        @include('forms.form_text',['title' => 'Quality Checked By:', 'data' => CommonHelpers::getUserFullname($data->quality_id) ])
        @include('forms.form_text',['title' => 'Analyst:', 'data' => CommonHelpers::getUserFullname($data->analyst_id) ])
        @include('forms.form_text',['title' => 'Linked Project:', 'data' => isset($data->project->reference) ? $data->project->reference : '' ,'link' => isset($data->project->reference) ? route('project.index', ['id' => $data->project->id]) : '' ])

{{--         @if(CommonHelpers::checkFile($data->client_id, UKAS_IMAGE))
            @include('forms.form_display_upload',['title' => 'UKAS:', 'object_id' => $data->client_id, 'folder' => UKAS_IMAGE ])
        @endif
        @if(CommonHelpers::checkFile($data->client_id, UKAS_TESTING_IMAGE))
            @include('forms.form_display_upload',['title' => 'UKAS:', 'object_id' => $data->client_id, 'folder' => UKAS_TESTING_IMAGE ])
        @endif --}}

        @include('forms.form_text',['title' => 'PAS:', 'data' => (optional($data->surveySetting)->is_require_priority_assessment == 1) ? "Required" : "Not Required" ])
        @include('forms.form_text',['title' => 'Property Construction Details:', 'data' => (optional($data->surveySetting)->is_require_construction_details == 1) ? "Required" : "Not Required" ])
        @include('forms.form_text',['title' => 'Location Construction Details:', 'data' => (optional($data->surveySetting)->is_require_location_construction_details == 1) ? "Required" : "Not Required" ])
        @include('forms.form_text',['title' => 'Location Void Investigations:', 'data' => (optional($data->surveySetting)->is_require_location_void_investigations == 1) ? "Required" : "Not Required" ])
        @include('forms.form_text',['title' => 'R&D Elements:', 'data' => (optional($data->surveySetting)->is_require_r_and_d_elements == 1) ? "Required" : "Not Required" ])
        @include('forms.form_text',['title' => 'Licensed/non Licensed:', 'data' => (optional($data->surveySetting)->is_require_license_status == 1) ? "Required" : "Not Required" ])
        @include('forms.form_text',['title' => 'Item Photos Required:', 'data' => (optional($data->surveySetting)->is_require_photos == 1) ? "Required" : "Not Required" ])
        @include('forms.form_text',['title' => 'Surveyors Notes Required:', 'data' => (optional($data->surveySetting)->is_property_plan_photo == 1) ? "Required" : "Not Required" ])
        @if($data->client_id == 1)
            @include('forms.form_text',['title' => 'External Laboratory:', 'data' => (optional($data->surveySetting)->external_laboratory == 1) ? "Required" : "Not Required" ])
        @endif
    </div>

    <div class="mt-3 ml-3 pb-2" >
        @if($canBeUpdateSurvey)
            @if($data->status == COMPLETED_SURVEY_STATUS)
                <div class="spanWarningSurveying">
                    <strong><em>Survey is view only because technical activity is complete</em></strong>
                </div>
            @else
                @if(!$is_locked)
                    <a href="{{ route('shineCompliance.survey.get_edit',['survey_id' => $data->id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                            <strong>{{ __('Edit') }}</strong>
                        </button>
                    </a>
                @endif
                {{-- @if(\CommonHelpers::checkDecommissionPermission() || $survey->consultant_id == \Auth::user()->id || $survey->client_id == \Auth::user()->id) --}}
                    <a href="{{ route('shineCompliance.survey.decommission',['survey_id' => $data->id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                            @if($data->decommissioned == SURVEY_UNDECOMMISSION)
                                <strong>{{ __('Decommission') }}</strong>
                            @else
                                <strong>{{ __('Recommission') }}</strong>
                            @endif
                        </button>
                    </a>
                {{-- @endif --}}
                @if(isset($data->is_locked) and $data->is_locked != SURVEY_LOCKED)
                    <a href="#" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="#send-survey">
                            <strong>{{ __('Send') }}</strong>
                        </button>
                    </a>
                    <a href="#" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="#pushlish-survey">
                            <strong>{{ __('Publish') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.publish_survey',['color' => 'orange','survey' => $survey, 'modal_id' => 'pushlish-survey', 'url' => route('shineCompliance.survey.publish',['survey_id' => $survey->id])])
                    @include('shineCompliance.modals.send_survey',['color' => 'orange','survey' => $survey, 'modal_id' => 'send-survey', 'url' => route('shineCompliance.survey.send',['survey_id' => $survey->id])])
                @endif
            @endif
        @endif
    </div>
    @include('shineCompliance.tables.survey_history', [
        'title' => 'Survey History',
        'data' => $data->publishedSurvey,
        'status' => $data->status,
        'tableId' => 'survey-history',
        'collapsed' => false,
        'plus_link' => false,
        'row_col' => 'col-md-12',
        'order_table' => 'published'
     ])
</div>
