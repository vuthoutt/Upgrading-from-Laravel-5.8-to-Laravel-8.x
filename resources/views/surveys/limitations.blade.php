<div class="row">
    @include('tables.survey_information', [
        'title' => 'Limitations',
        'type' => 'limitations-table',
        'data' => $data,
        'data_content' => $data->surveyInfo->limitations,
        ])
</div>
