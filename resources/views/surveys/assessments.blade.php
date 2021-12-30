<div class="row">
    @include('tables.property_register_summary', [
        'title' => 'Survey Assessment Summary',
        'tableId' => 'survey-assetment-summary',
        'collapsed' => false,
        'plus_link' => false,
        'normalTable' => true,
        'count' => $surveyAssessment["All ACM Items"]['number'],
        'data' => $surveyAssessment,
        'survey_lock' => $is_locked
        ])

</div>
<div class="row">
    @include('tables.property_decommissioned_items', [
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
    @include('tables.areas', [
        'title' => 'Survey Area/floors ',
        'tableId' => 'survey-floor-table',
        'collapsed' => false,
        'plus_link' => (!$is_locked and $canBeUpdateSurvey) ? true : false,
        'modal_id' => 'survey-add-area',
        'data' => $areas
        ])
</div>
@include('modals.add_area',['color' => 'orange','survey' => $survey, 'modal_id' => 'survey-add-area', 'action' => 'create' , 'url' => route('survey.post_area')])


<div class="row">
    @include('tables.decommission_areas', [
        'title' => 'Survey Decommissioned Area/floors',
        'tableId' => 'survey-dec-floor-table',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $decommissionedAreas
        ])
</div>
