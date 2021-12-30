<div class="row">
    @include('shineCompliance.tables.survey_information', [
        'title' => 'Method - Text Box',
        'type' => 'method-table',
        'data' => $data,
        'data_content' => $data->surveyInfo->method ?? '',
        ])
</div>
<div class="row">
    @include('shineCompliance.tables.method_question', [
        'title' => 'Method - Questionnaire',
        'tableId' => 'method-question',
        'row_col' => 'col-md-12',
        'class' => 'method-question',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $data->surveyAnswer ?? ''
        ])
</div>
<!-- Button trigger modal -->
@if(!$is_locked and $canBeUpdateSurvey)
    <div class="row">
        <a class="btn btn_long_size light_grey_gradient_button fs-8pt mt-5" data-toggle="modal" data-target="#exampleModal"><strong>Edit</strong></a>
    </div>
@include('shineCompliance.modals.method_check_box_asbestos',['color' => 'orange','survey' => $data])
@endif
