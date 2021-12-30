<div class="row">
    @include('shineCompliance.tables.survey_information', [
        'title' => 'Objectives / Scopes',
        'type' => 'objectives-scope-table',
        'data' => $data,
        'data_content' => $data->surveyInfo->objectives_scope ?? '',
        'canBeUpdateSurvey' => $canBeUpdateSurvey,
        'is_locked' => $is_locked,
        ])
</div>
