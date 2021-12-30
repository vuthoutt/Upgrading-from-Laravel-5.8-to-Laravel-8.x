<div class="row">
    @include('tables.survey_information', [
        'title' => 'Executive Summary',
        'type' => 'executive-summary-table',
        'data' => $data,
        'data_content' => $data->surveyInfo->executive_summary,
        ])
</div>
