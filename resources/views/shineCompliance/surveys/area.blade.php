 <div class="row offset-top40">
    <div class="col-md-12">
        @include('shineCompliance.forms.form_text',['title' => 'Shine Reference:', 'data' => isset($areaData->reference) ? $areaData->reference : '' ])
        @include('shineCompliance.forms.form_text',['title' => 'Area/floor Reference:', 'data' => isset($areaData->area_reference) ? $areaData->area_reference : '' ])
        @include('shineCompliance.forms.form_text',['title' => 'Area/floor Description:', 'data' => isset($areaData->description) ? $areaData->description : '' ])
         <div class="row">
            <label class="col-md-3 col-form-label text-md-left" >
                <span class="font-weight-bold">Reason:</span>
                <a href="" data-toggle="modal" data-target="#decommission_reason_history_area">(History)</a>
            </label>
            <div class="col-md-6 form-input-text" >
                {!! $areaData->decommissionedReason->description ?? null !!}
            </div>
        </div>
        @include('shineCompliance.modals.decommission_history',[ 'color' => 'orange',
                                        'modal_id' => 'decommission_reason_history_area',
                                        'header' => 'Decommission Area/floor Reason',
                                        'table_id' => 'area_decommission_history_table',
                                        'url' => route('shineCompliance.comment.decommission',['category' => 'area']),
                                        'data' => $areaData->decommissionCommentHistory ?? '',
                                        'id' => $areaData->id ?? ''
                                     ])
    </div>
</div>
@if($survey->status != COMPLETED_SURVEY_STATUS and ($survey->is_locked != SURVEY_LOCKED) and ($areaData->is_locked == AREA_UNLOCKED))

        <a  class="btn btn_long_size light_grey_gradient_button fs-8pt mt-2" data-toggle="modal" data-target="#add-area"><strong>Edit</strong></a>
        @include('shineCompliance.modals.add_area_survey',['color' => 'orange','survey' => $survey, 'modal_id' => 'add-area','action' => 'edit',
                                     'data' => $areaData,
                                    'position' => $position,
                                    'category' => $category,
                                    'pagination_type' => $pagination_type,
                                     'url' => route('shineCompliance.survey.post_area', ['area_id' => $areaData->id])])
        {{-- @if(\CommonHelpers::checkDecommissionPermission() || $survey->consultant_id == \Auth::user()->id || $survey->client_id == \Auth::user()->id) --}}
            @if($areaData->decommissioned == 0)
                <a class="btn  light_grey_gradient_button fs-8pt mt-2" data-toggle="modal" data-target="#decommission_area">
                        <strong>Decommission</strong>
                </a>
                @include('shineCompliance.modals.decommission',[ 'color' => 'orange',
                                                    'modal_id' => 'decommission_area',
                                                    'header' => 'Decommission Area/floor',
                                                    'decommission_type' => 'area',
                                                    'name' => 'area_decommisson_reason_add',
                                                    'url' => route('shineCompliance.survey.decommission_area', ['area_id' => $areaData->id]),
                                                    ])
            @else
                <a class="btn  light_grey_gradient_button fs-8pt mt-2" data-toggle="modal" data-target="#recommission_area">
                        <strong>Recommission</strong>
                </a>
                @include('shineCompliance.modals.recommission',[ 'color' => 'orange',
                                                'modal_id' => 'recommission_area',
                                                'header' => 'Recommission Area/floor',
                                                'decommission_type' => 'area',
                                                'name' => 'area_decommisson_reason_add',
                                                'url' => route('shineCompliance.survey.decommission_area', ['area_id' => $areaData->id]),
                                                ])
            @endif
        {{-- @endif --}}
@else
    <div class="spanWarningSurveying col-md-5 mt-4">
        <strong><em>Survey is view only because technical activity is complete</em></strong>
    </div>
@endif
 @include('vendor.pagination.simple-bootstrap-4-customize')
<div class="row">
{{--    {{ dd($surveyAssessment) }}--}}
    @include('shineCompliance.tables.property_register_summary', [
        'title' => 'Area/floor Assessment Summary',
        'tableId' => 'area-assetment-summary',
        'collapsed' => false,
        'plus_link' => false,
        'count' => $surveyAssessment["All ACM Items"]['number'],
        'data' => $surveyAssessment,
        'survey_lock' => $is_locked
        ])
</div>
<div class="row">
    @include('shineCompliance.tables.property_decommissioned_items', [
        'title' => 'Area/floor Decommissioned Items',
        'tableId' => 'area-dec-item',
        'collapsed' => true,
        'plus_link' => false,
        'header' =>  ['Reference','Product/debris type','MAS','Reason', 'Item Comments'],
        'data' => $dataDecommisstionItems,
        ])
</div>

<div class="row">
    @include('shineCompliance.tables.locations', [
        'title' => 'Area/floor Room/locations',
        'tableId' => 'area-room-table',
        'row_col' => 'col-md-12',
        'collapsed' => false,
        'plus_link' =>($areaData->is_locked == AREA_UNLOCKED) and $canBeUpdateSurvey and !$is_locked ? true : false,
        'link' => route('shineCompliance.location.get_add', ['area_id' => $areaData->id]),
        'data' => $areas
        ])
</div>

<div class="row">
    @include('shineCompliance.tables.decommission_locations', [
        'title' => 'Area/floor Decommissioned Room/locations',
        'tableId' => 'area-dec-room-table',
        'row_col' => 'col-md-12',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $decommissionedAreas
        ])
</div>
