<div class="row">
    @include('tables.property_plans', [
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
@include('modals.property_plan_add',['color' => 'orange', 'modal_id' => 'site-plan-add', 'url' => route('ajax.property_plan'), 'data_site' => $survey, 'survey' => true, 'doc_type' => 'survey_plan', 'title' => 'Site Plan Document'])

@if($survey->surveySetting->is_property_plan_photo == ACTIVE)
<div class="row">
    @include('tables.surveyors_notes', [
        'title' => 'Surveyors Notes',
        'tableId' => 'surveyors-notes',
        'collapsed' => true,
        'plus_link' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'edit_permission' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'modal_id' => 'surveyors-notes-add',
        'data' => $surveyorsNotes
        ])
    @include('modals.property_plan_add',['color' => 'orange', 'modal_id' => 'surveyors-notes-add', 'url' => route('ajax.property_plan'), 'data_site' => $survey, 'survey' => true, 'surveyor_note' => true,'doc_type' => 'surveyor_plan'])
</div>
@endif
