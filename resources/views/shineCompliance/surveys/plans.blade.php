<div class="row">
    @include('shineCompliance.tables.property_plans', [
        'title' => 'Survey Plans ',
        'type' => 'survey_plans',
        'tableId' => 'survey-plans',
        'collapsed' => false,
        'plus_link' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'data' => $plans,
        'modal_id' => 'site-plan-add',
        'survey' => true,
        'edit_permission' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        ])
</div>
@include('shineCompliance.modals.property_plan_add',['color' => 'orange', 'modal_id' => 'site-plan-add', 'url' => route('shineCompliance.asbestos.ajax.property_plan'), 'data_site' => $survey,'assessment' => true, 'survey' => true, 'doc_type' => 'survey_plan'])
{{--@if(isset($survey->client_id) and ($survey->client_id) == 1)--}}
{{--    <a class="btn btn_long_size light_grey_gradient mt-4" href="#plan-email-co1" data-toggle="modal"><strong>Email</strong></a>--}}
{{--@endif--}}
@if(($survey->surveySetting->is_property_plan_photo ?? '') == ACTIVE)
<div class="row">
    @include('shineCompliance.tables.surveyors_notes', [
        'title' => 'Surveyors Notes',
        'tableId' => 'surveyors-notes',
        'collapsed' => true,
        'plus_link' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'edit_permission' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'modal_id' => 'surveyors-notes-add',
        'data' => $surveyorsNotes
        ])
    @include('shineCompliance.modals.property_plan_add',['color' => 'orange', 'modal_id' => 'surveyors-notes-add', 'url' => route('shineCompliance.asbestos.ajax.property_plan'), 'data_site' => $survey,'assessment' => true, 'assessNotes' => true,'doc_type' => 'surveyor_plan'])
</div>
@endif

@include('shineCompliance.modals.email_sample',['title' => 'Email Notification' ,
                                    'color' => 'orange',
                                    'modal_id' => 'plan-email-co1',
                                     'url' => route('shineCompliance.ajax.sample_email_co1'),
                                     'data' => $survey,
                                     'survey' => true,
                                     'doc_title' => 'Certificate',
                                     'reference' => 'Reference:'
                                     ])
