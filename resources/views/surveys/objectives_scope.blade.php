<div class="row">
    @include('tables.survey_information', [
        'title' => 'Objectives / Scopes',
        'type' => 'objectives-scope-table',
        'data' => $data,
        'data_content' => $data->surveyInfo->objectives_scope,
        ])
</div>
