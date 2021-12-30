<div class="row">
    @include('tables.survey_information', [
        'title' => 'Method - Text Box',
        'type' => 'method-table',
        'data' => $data,
        'data_content' => $data->surveyInfo->method,
        ])
</div>
<div class="row">
    @include('tables.method_question', [
        'title' => 'Method - Questionnaire',
        'tableId' => 'method-question',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $data->surveyAnswer
        ])
</div>
<!-- Button trigger modal -->
@if($canBeUpdateSurvey)
    <div class="row">
        <a  class="btn btn_long_size light_grey_gradient mt-5" data-toggle="modal" data-target="#exampleModal"><strong>Edit</strong></a>
    </div>
@endif
@include('modals.method_check_box',['color' => 'orange','survey' => $data])
