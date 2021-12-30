<div class="row">
    @include('shineCompliance.tables.property_plans', [
        'title' => 'Assessment Plans ',
        'type' => 'survey_plans',
        'tableId' => 'survey-plans',
        'collapsed' => false,
        'plus_link' =>  ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
        'data' => $plans,
        'modal_id' => 'site-plan-add',
        'survey' => true,
        'edit_permission' =>  true,
        ])
</div>

@include('shineCompliance.modals.property_plan_add',['color' => 'orange', 'modal_id' => 'site-plan-add', 'url' => route('shineCompliance.ajax.property_plan'), 'data_site' => $assessment, 'assessment' => true, 'doc_type' => 'survey_plan'])

<div class="row">
    @include('shineCompliance.tables.surveyors_notes', [
        'title' => 'Assessment Notes',
        'tableId' => 'surveyors-notes',
        'collapsed' => false,
        'plus_link' => ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
        'edit_permission' => true ,
        'modal_id' => 'surveyors-notes-add',
        'data' => $assessorsNotes
        ])
    @include('shineCompliance.modals.property_plan_add',['color' => 'orange', 'modal_id' => 'surveyors-notes-add', 'url' => route('shineCompliance.ajax.property_plan'), 'data_site' => $assessment, 'assessment' => true, 'assessNotes' => true,'doc_type' => 'surveyor_plan'])
</div>
