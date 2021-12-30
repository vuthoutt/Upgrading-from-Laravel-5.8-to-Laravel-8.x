<div class="row offset-top40">
    <div class="col-md-12">
        @include('shineCompliance.forms.form_text',['title' => 'Assessment Reference:', 'data' => $data->reference ])
        @include('shineCompliance.forms.form_text',['title' => 'Assessment Type:', 'data' => $data->assess_type ])
        @include('shineCompliance.forms.form_text',['title' => 'Created Date:', 'data' => $data->created_at->format('d/m/Y') ])
        @include('shineCompliance.forms.form_text',['title' => 'Due Date:', 'data' => $data->due_date ])
        @include('shineCompliance.forms.form_text',['title' => 'Started Date:', 'data' => $data->started_date ])
        @include('shineCompliance.forms.form_text',['title' => 'Assessment Start Date:', 'data' => $data->assess_start_date ])
        @include('shineCompliance.forms.form_text',['title' => 'Assessment Start Time:', 'data' => $data->assess_start_time ])
        @include('shineCompliance.forms.form_text',['title' => 'Assessment Finished:', 'data' => $data->assess_finish_date ])
        @include('shineCompliance.forms.form_text',['title' => 'Published Date:', 'data' => $data->published_date ])
        @include('shineCompliance.forms.form_text',['title' => 'Completed Date:', 'data' => $data->completed_date ])
        @include('shineCompliance.forms.form_text',['title' => 'Status:', 'data' => $data->status_text ])
        @include('shineCompliance.forms.form_text',['title' => 'Commissioned:', 'data' => CommonHelpers::getUserFullname($data->created_by), 'link' => route('shineCompliance.profile-shineCompliance', $data->created_by ?? 0) ])
        @include('shineCompliance.forms.form_text',['title' => 'Organisation:', 'data' => $data->clients->name ?? '' ])
        @include('shineCompliance.forms.form_text',['title' => Str::title(str_replace('_',' ',$data->assess_classification)) . ' Lead:', 'data' => CommonHelpers::getUserFullname($data->lead_by), 'link' => route('shineCompliance.profile-shineCompliance', $data->lead_by ?? 0) ])
        @include('shineCompliance.forms.form_text',['title' => 'Second ' . Str::title(str_replace('_',' ',$data->assess_classification)) . ' Lead:', 'data' => CommonHelpers::getUserFullname($data->second_lead_by), 'link' => route('shineCompliance.profile-shineCompliance', $data->second_lead_by ?? 0) ])
        @include('shineCompliance.forms.form_text',['title' => Str::title(str_replace('_',' ',$data->assess_classification)) . ' Risk Assessor:', 'data' => CommonHelpers::getUserFullname($data->assessor_id), 'link' => route('shineCompliance.profile-shineCompliance', $data->assessor_id ?? 0) ])
        @include('shineCompliance.forms.form_text',['title' => 'Quality Checked By:', 'data' => CommonHelpers::getUserFullname($data->quality_checker), 'link' => route('shineCompliance.profile-shineCompliance', $data->quality_checker ?? 0) ])
        @include('shineCompliance.forms.form_text',['title' => 'Linked Project:', 'data' => isset($data->project->reference) ? $data->project->reference : '' ,'link' => isset($data->project->reference) ? route('project.index', ['id' => $data->project->id ?? 0]) : '' ])
{{--        @include('shineCompliance.forms.form_text',['title' => 'Link Work Request:', 'data' => $data->decommissioned, 'link' => '' ])--}}
        @include('shineCompliance.forms.form_text',['title' => 'Property Size/volume Details:', 'data' => optional($data->assessmentInfo)->property_size_volume ])
        @include('shineCompliance.forms.form_text',['title' => 'Fire Safety Systems & Equipment:', 'data' => optional($data->assessmentInfo)->fire_safety_setting ])

        @if(($assessment->classification == ASSESSMENT_FIRE_TYPE && in_array($assessment->type, ASSESS_TYPE_FIRE_RISK_ALL_TYPE)) || $assessment->classification == ASSESSMENT_HS_TYPE)
            @include('shineCompliance.forms.form_text',['title' => 'Vehicle Parking:', 'data' => optional($data->assessmentInfo)->vehicle_parking ])
            @include('shineCompliance.forms.form_text',['title' => 'Nonconformities:', 'data' => optional($data->assessmentInfo)->non_conformities ])
        @endif

        @include('shineCompliance.forms.form_text',['title' => 'Equipment Details:', 'data' => optional($data->assessmentInfo)->equipment_details ])
        @include('shineCompliance.forms.form_text',['title' => 'Item Photos Required:', 'data' => optional($data->assessmentInfo)->hazard_photo ])
        @include('shineCompliance.forms.form_text',['title' => 'Assessors Notes Required:', 'data' => optional($data->assessmentInfo)->assessors_note ])
    </div>

    <div class="mt-3 ml-3 pb-2 pl-0" >
        @if($canBeUpdateSurvey)
            @if($data->status == ASSESSMENT_STATUS_COMPLETED)
                <div class="spanWarningSurveying">
                    <strong><em>Assessment is view only because technical activity is complete</em></strong>
                </div>
            @else
                @if(!$data->is_locked)
                    <a href="{{ route('shineCompliance.assessment.get_edit',['assess_id' => $data->id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt">
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
                                'header' => 'Decommission Assessment',
                                'decommission_type' => \ComplianceHelpers::getAssessmentDecomissionReasonType($data->classification),
                                'name' => 'decommission_reason',
                                'color' => 'orange',
                                'reference' => $data->reference ?? '',
                                'url' => route('shineCompliance.assessment.decommission', ['assess_id' => $data->id]),
                            ])
                @else
                    <a href="" data-toggle="modal" data-target="#recommission_assessment" style="text-decoration: none">
                        <button type="button" class="btn light_grey_gradient_button fs-8pt">
                            <strong>{{ __('Recommission') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.recommission_assessment',[
                                'modal_id' => 'recommission_assessment',
                                'header' => 'Recommission Assessment',
                                'name' => 'decommission_reason',
                                'color' => 'orange',
                                'reference' => $data->reference ?? '',
                                'url' => route('shineCompliance.assessment.recommission', ['assess_id' => $data->id]),
                            ])
                @endif
                @if(isset($data->is_locked) and $data->is_locked != SURVEY_LOCKED)
                    <a href="#" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="#send-assessment">
                            <strong>{{ __('Send') }}</strong>
                        </button>
                    </a>
                    <a href="#" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt" data-toggle="modal" data-target="#publish-assessment">
                            <strong>{{ __('Publish') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.publish_assessment',['color' => 'orange','assessment' => $data, 'modal_id' => 'publish-assessment', 'url' => route('shineCompliance.assessment.publish',['assess_id' => $data->id])])
                    @include('shineCompliance.modals.send_assessment',['color' => 'orange','assessment' => $data, 'modal_id' => 'send-assessment', 'url' => route('shineCompliance.assessment.send',['assess_id' => $data->id])])
                @endif
            @endif
        @endif
    </div>
</div>
<div class="row">

        @include('shineCompliance.tables.assess_history', [
            'title' => 'Assessment History',
            'data' => $data->publishedAssessments ?? [],
            'status' => $data->status ?? 0,
            'tableId' => 'survey-history',
            'collapsed' => false,
            'plus_link' => false,
            'order_table' => 'published'
         ])

</div>
