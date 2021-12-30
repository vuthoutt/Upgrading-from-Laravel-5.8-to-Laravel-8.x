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
        @include('forms.form_text',['title' => 'Status:', 'data' => $data->status_text ])
        @if($data->status == 8)
            @include('forms.form_text',['title' => 'Reason:', 'data' => $data->surveyReason->description ?? '' ])
        @endif
        @include('forms.form_text',['title' => 'Commissioned:', 'data' => CommonHelpers::getUserFullname($data->created_by) ])
        @include('forms.form_text',['title' => 'Workstream/Programme:', 'data' => $data->workStream->description ?? '' ])
        {{--  @include('forms.form_text',['title' => 'Survey Cost:', 'data' => $data->cost ?? '' ])  --}}
        @include('forms.form_text',['title' => 'Asbestos Lead:', 'data' => CommonHelpers::getUserFullname($data->lead_by) ])
        @include('forms.form_text',['title' => 'Second Asbestos Lead:', 'data' => CommonHelpers::getUserFullname($data->second_lead_by) ])
        @include('forms.form_text',['title' => 'Surveyor:', 'data' => CommonHelpers::getUserFullname($data->surveyor_id) ])
        @include('forms.form_text',['title' => 'Second Surveyor:', 'data' => CommonHelpers::getUserFullname($data->second_surveyor_id) ])
        @include('forms.form_text',['title' => 'Project Manager:', 'data' => CommonHelpers::getUserFullname($data->consultant_id) ])
        @include('forms.form_text',['title' => 'Quality Checked By:', 'data' => CommonHelpers::getUserFullname($data->quality_id) ])
        @include('forms.form_text',['title' => 'Analyst:', 'data' => CommonHelpers::getUserFullname($data->analyst_id) ])
        @include('forms.form_text',['title' => 'Linked Project:', 'data' => isset($data->project->reference) ? $data->project->reference : '','link' => route('project.index', ['id' => $data->project_id ?? 0]) ])

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
    </div>

    <div class="mt-5 ml-2 pb-5">
        @if($canBeUpdateSurvey)
            @if($data->status == COMPLETED_SURVEY_STATUS)
                <div class="spanWarningSurveying">
                    <strong><em>Survey is view only because technical activity is complete</em></strong>
                </div>
            @else
                @if(!$is_locked)
                    <a href="{{ route('survey.get_edit',['survey_id' => $data->id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient ">
                            <strong>{{ __('Edit') }}</strong>
                        </button>
                    </a>
                @endif
                @if($data->decommissioned == 0)
                    <a href="" data-toggle="modal" data-target="#decommission_assessment" style="text-decoration: none">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt">
                            <strong>{{ __('Decommission') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.decommission_assessment',[
                                'modal_id' => 'decommission_assessment',
                                'header' => 'Decommission Survey',
                                'decommission_type' => 'asbestos',
                                'name' => 'decommission_reason',
                                'color' => 'orange',
                                'reference' => $data->reference ?? '',
                                'url' => route('survey.decommission', ['assess_id' => $data->id]),
                            ])
                @else
                    <a href="" data-toggle="modal" data-target="#recommission_assessment" style="text-decoration: none">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt">
                            <strong>{{ __('Recommission') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.recommission_assessment',[
                                'modal_id' => 'recommission_assessment',
                                'header' => 'Recommission Survey',
                                'name' => 'decommission_reason',
                                'color' => 'orange',
                                'reference' => $data->reference ?? '',
                                'url' => route('survey.decommission', ['assess_id' => $data->id]),
                            ])
                @endif
                @if(isset($data->is_locked) and $data->is_locked != SURVEY_LOCKED)
                    <a href="#" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient " data-toggle="modal" data-target="#send-survey">
                            <strong>{{ __('Send') }}</strong>
                        </button>
                    </a>
                    <a href="#" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient" data-toggle="modal" data-target="#pushlish-survey">
                            <strong>{{ __('Publish') }}</strong>
                        </button>
                    </a>
                    @include('modals.publish_survey',['color' => 'orange','survey' => $survey, 'modal_id' => 'pushlish-survey', 'url' => route('survey.publish',['survey_id' => $survey->id])])
                    @include('modals.send_survey',['color' => 'orange','survey' => $survey, 'modal_id' => 'send-survey', 'url' => route('survey.send',['survey_id' => $survey->id])])
                @endif
            @endif
        @endif
    </div>
    @include('tables.survey_history', [
        'title' => 'Survey History',
        'data' => $data->publishedSurvey,
        'status' => $data->status,
        'tableId' => 'survey-history',
        'collapsed' => false,
        'plus_link' => false,
        'order_table' => 'published'
     ])
</div>
