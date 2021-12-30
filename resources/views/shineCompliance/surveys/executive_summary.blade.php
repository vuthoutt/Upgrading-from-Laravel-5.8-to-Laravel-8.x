<div class="row">
    @include('shineCompliance.tables.survey_information', [
        'title' => 'Executive Summary',
        'type' => 'executive-summary-table',
        'data' => $data,
        'data_content' => $data->surveyInfo->executive_summary ?? '',
        'canBeUpdateSurvey' => $canBeUpdateSurvey,
        'is_locked' => $is_locked,
        ])
</div>
