<div class="row">
    @include('shineCompliance.tables.assessment_sampling', [
        'title' => 'Sample Certificates',
        'type' => 'survey_plans',
        'tableId' => 'sample_certificates',
        'collapsed' => false,
        'plus_link' =>  ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
        'data' => $samples ?? [],
        'modal_id' => 'sample-add',
        'survey' => true,
        'edit_permission' =>  true,
        ])
</div>

@include('shineCompliance.modals.assessment_sampling_add',[
    'color' => 'orange',
    'modal_id' => 'sample-add',
    'url' => route('shineCompliance.ajax.assessment_sampling'),
    'data_site' => $assessment, 'assessment' => true,
])
