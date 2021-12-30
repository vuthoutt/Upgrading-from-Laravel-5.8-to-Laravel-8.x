 <div class="row offset-top40">
    <div class="col-md-12">
        <div class="col-md-12 client-image-show mb-3">
            <img class="image-signature" src="{{ CommonHelpers::getFile($locationData->id, LOCATION_IMAGE) }}" style="max-height: 320px">
        </div>
        <div class="col-md-12 client-image-show mb-3">
            <a title="Download Asbestos Room/Location Image" href="{{ route('retrive_image',['type'=>  LOCATION_IMAGE ,'id'=> $locationData->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
        @include('forms.form_text',['title' => 'Shine Reference:', 'data' => isset($locationData->reference) ? $locationData->reference : '' ])
        @include('forms.form_text',['title' => 'Room/Location Reference:', 'data' => isset($locationData->location_reference) ? $locationData->location_reference : '' ])
        @include('forms.form_text',['title' => 'Room/Location Description:', 'data' => isset($locationData->description) ? $locationData->description : '' ])

        @include('modals.decommission_history',[ 'color' => 'orange',
                                    'modal_id' => 'location_decommission_comment_history',
                                    'header' => 'Historical Room/location Comments',
                                    'table_id' => 'location_decommission_comment_history_table',
                                    'url' => route('comment.decommission', ['category' => 'location']),
                                    'data' => $locationData->decommissionCommentHistory,
                                    'id' => $locationData->id
                                    ])
        <br>
        @include('forms.form_text',['title' => 'Assessed:', 'data' => $locationData->not_assessed == NOT_ASSESSED ? 'Not Assessed' : 'Assessed' ])
        @if($locationData->not_assessed == NOT_ASSESSED)
        @include('forms.form_text',['title' => 'Reason:', 'data' =>  $locationData->notAssessedReason->description ?? ''])
        @endif
        <br>
        <div class="row">
            <label class="col-md-3 col-form-label text-md-left" >
                <span class="font-weight-bold">Reason:</span>
                <a href="" data-toggle="modal" data-target="#location_decommission_comment_history">(History)</a>
            </label>
            <div class="col-md-6 form-input-text" >
                {!! $locationData->decommissionedReason->description ?? null !!}
            </div>
        </div>
        @include('forms.form_text',['title' => 'Accessibility:', 'data' => isset($locationData->state_text) ? $locationData->state_text : '' ])

        @if ($locationData->state == "inaccessible")
            @include('forms.form_text',['title' => 'Reason NA:', 'data' =>  \CommonHelpers::getLocationVoidDetails(optional($locationData->locationInfo)->reason_inaccess_key, optional($locationData->locationInfo)->reason_inaccess_other ) ])
        @endif

        @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_location_void_investigations == ACTIVE) )
        <div class="mt-4 mb-4">
            <label class="font-weight-bold part-heading"> Room/location Void Investigations:</label>
        </div>
        @include('forms.form_text',['title' => 'Ceiling Void:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationVoid)->ceiling, optional($locationData->locationVoid)->ceiling_other ) ])
        @include('forms.form_text',['title' => 'Floor Void:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationVoid)->floor, optional($locationData->locationVoid)->floor_other ) ])
        @include('forms.form_text',['title' => 'Cavities:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationVoid)->cavities, optional($locationData->locationVoid)->cavities_other ) ])
        @include('forms.form_text',['title' => 'Risers:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationVoid)->risers, optional($locationData->locationVoid)->risers_other ) ])
        @include('forms.form_text',['title' => 'Ducting:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationVoid)->ducting, optional($locationData->locationVoid)->ducting_other ) ])
        @include('forms.form_text',['title' => 'Boxing:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationVoid)->boxing, optional($locationData->locationVoid)->boxing_other ) ])
        @include('forms.form_text',['title' => 'Pipework:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationVoid)->pipework, optional($locationData->locationVoid)->pipework_other ) ])
        @endif

        @if(empty($survey) || ( isset($survey->surveySetting) and optional($survey->surveySetting)->is_require_location_construction_details == ACTIVE) )
        <div class="mt-4 mb-4">
            <label class="font-weight-bold part-heading"> Room/location Construction Details:</label>
        </div>
        @include('forms.form_text',['title' => 'Ceiling:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationConstruction)->ceiling, optional($locationData->locationConstruction)->ceiling_other ) ])
        @include('forms.form_text',['title' => 'Walls:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationConstruction)->walls, optional($locationData->locationConstruction)->walls_other ) ])
        @include('forms.form_text',['title' => 'Floor:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationConstruction)->floor, optional($locationData->locationConstruction)->floor_other ) ])
        @include('forms.form_text',['title' => 'Doors:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationConstruction)->doors, optional($locationData->locationConstruction)->doors_other ) ])
        @include('forms.form_text',['title' => 'Windows:', 'data' => \CommonHelpers::getLocationVoidDetails(optional($locationData->locationConstruction)->windows, optional($locationData->locationConstruction)->windows_other ) ])
        @endif
        <div class="row">
            <label class="col-md-3 col-form-label text-md-left" >
                <span class="font-weight-bold">Room/location Comments:</span>
                <a href="" data-toggle="modal" data-target="#location_comment_history">(History)</a>
            </label>
            <div class="col-md-6 form-input-text" >
                {!! $locationData->locationInfo->comments ?? null !!}
            </div>
        </div>
        @include('modals.comment_history',[ 'color' => 'orange',
                                    'modal_id' => 'location_comment_history',
                                    'header' => 'Historical Room/location Comments',
                                    'table_id' => 'location_comment_history_table',
                                    'url' => route('comment.location'),
                                    'data' => $locationData->commentHistory,
                                    'id' => $locationData->id
                                    ])

    </div>
</div>
@if($canBeUpdateSurvey)
    @if(($survey->status != COMPLETED_SURVEY_STATUS) and ($locationData->is_locked == LOCATION_UNLOCKED))
        <a  class="btn btn_long_size light_grey_gradient mt-2" href="{{ route('get_edit_location',['id' => $locationData->id, 'position' => $position ?? 0,'category' => $category ?? 0,'pagination_type' => $pagination_type ?? 0]) }}"><strong>Edit</strong></a>
        {{-- @if(\CommonHelpers::checkDecommissionPermission() || $survey->consultant_id == \Auth::user()->id || $survey->client_id == \Auth::user()->id) --}}
            @if($locationData->decommissioned == 0)
            <a class="btn  light_grey_gradient mt-2" data-toggle="modal" data-target="#decommission_location">
                    <strong>Decommission</strong>
            </a>
                @include('modals.decommission',[ 'color' => 'orange',
                                                    'modal_id' => 'decommission_location',
                                                    'header' => 'Decommission Room/Location',
                                                    'decommission_type' => 'location',
                                                    'name' => 'location_decommisson_reason_add',
                                                    'url' => route('location.decommission', ['location_id' => $locationData->id]),
                                                    ])
            @else
            <a class="btn  light_grey_gradient mt-2" data-toggle="modal" data-target="#recommission_location">
                    <strong>Recommission</strong>
            </a>
                @include('modals.recommission',[ 'color' => 'orange',
                                                'modal_id' => 'recommission_location',
                                                'header' => 'Recommission Room/Location',
                                                'decommission_type' => 'location',
                                                'name' => 'location_decommisson_reason_add',
                                                'url' => route('location.decommission', ['location_id' => $locationData->id]),
                                                ])
            @endif
        {{-- @endif --}}
    @else
        <div class="spanWarningSurveying" style="width: 400px !important;">
            <strong><em>Survey is view only because technical activity is complete</em></strong>
        </div>
    @endif
@endif
 @include('vendor.pagination.simple-bootstrap-4-customize')
<div class="row">
    @include('tables.property_register_summary', [
        'title' => 'Room/location Assessment Summary',
        'tableId' => 'location-assetment-summary',
        'collapsed' => false,
        'plus_link' => (!$is_locked and $canBeUpdateSurvey and ($locationData->is_locked == LOCATION_UNLOCKED)) ? true : false,
        'normalTable' => true,
        'link' => route('item.get_add',['location' => $locationData->id]),
        'count' => $dataSummary["All ACM Items"]['number'],
        'data' => $surveyAssessment,
        'survey_lock' => $is_locked
        ])
</div>
<div class="row">
    @include('tables.property_decommissioned_items', [
        'title' => 'Room/location Decommissioned Items',
        'tableId' => 'location-dec-item',
        'collapsed' => true,
        'plus_link' => false,
        'header' =>  ['Reference','Product/debris type','MAS','Reason', 'Item Comments'],
        'data' => $dataDecommisstionItems,
        'pagination_type' => $pagination_type
        ])
</div>
