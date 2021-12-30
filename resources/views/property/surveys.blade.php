<div class="row">
    @include('tables.property_surveys', [
        'title' => 'Surveys',
        'tableId' => 'property-survey-table',
        'collapsed' => false,
        'plus_link' => $canAddNewSurvey,
        'link' => route('survey.get_add', ['property_id' => $propertyData->id]),
        'data' => $surveys,
        'order_table' => "[]"
        ])
</div>
<div class="row">
    @include('tables.property_surveys', [
        'title' => 'Surveys Decommissioned',
        'tableId' => 'property-dec-survey-table',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $decommissionedSurveys,
        'order_table' => "[]"
        ])
</div>
