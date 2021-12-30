<div class="row">
    @include('shineCompliance.tables.survey_information', [
        'title' => 'Limitations',
        'type' => 'limitations-table',
        'data' => $data,
        'data_content' => $data->surveyInfo->limitations ?? '',
        'canBeUpdateSurvey' => $canBeUpdateSurvey,
        'is_locked' => $is_locked,
        ])
</div>
