<div class="row">
    @include('shineCompliance.tables.property_register_summary', [
        'title' => 'Survey Assessment Summary',
        'tableId' => 'survey-assetment-summary',
        'collapsed' => false,
        'plus_link' => false,
        'count' => $surveyAssessment["All ACM Items"]['number'],
        'data' => $surveyAssessment,
        'survey_lock' => $is_locked,
        ])
</div>

<div class="row">
    @include('shineCompliance.tables.property_decommissioned_items', [
        'title' => 'Survey Decommissioned Items',
        'tableId' => 'survey-dec-item',
        'collapsed' => true,
        'plus_link' => false,
        'header' => ['Reference','Product/debris type','MAS','Reason', 'Item Comments'],
        'data' => $dataDecommisstionItems,
        'pagination_type' => $pagination_type
        ])
</div>

<div class="row">
    @include('shineCompliance.tables.areas', [
        'title' => 'Survey Area/floors ',
        'tableId' => 'survey-floor-table',
        'collapsed' => false,
        'plus_link' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'modal_id' => 'survey-add-area',
        'data' => $areas
        ])
</div>
@include('shineCompliance.modals.add_area_survey',['color' => 'orange','survey' => $survey, 'modal_id' => 'survey-add-area', 'action' => 'create' , 'url' => route('shineCompliance.survey.post_area')])


<div class="row">
    @include('shineCompliance.tables.decommission_areas', [
        'title' => 'Survey Decommissioned Area/floors',
        'tableId' => 'survey-dec-floor-table',
        "row_col" => "col-md-12",
        'collapsed' => true,
        'plus_link' => false,
        'data' => $decommissionedAreas
        ])
</div>
