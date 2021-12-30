<div class="row offset-top40">
    <div class="col-md-12">
        @include('forms.form_text',['title' => 'Shine Reference:', 'data' => isset($areaData->reference) ? $areaData->reference : '' ])
        @include('forms.form_text',['title' => 'Area/floor Reference:', 'data' => isset($areaData->area_reference) ? $areaData->area_reference : '' ])
        @include('forms.form_text',['title' => 'Area/floor Description:', 'data' => isset($areaData->description) ? $areaData->description : '' ])
        <div class="row">
            <label class="col-md-3 col-form-label text-md-left" >
                <span class="font-weight-bold">Reason:</span>
                <a href="" data-toggle="modal" data-target="#decommission_reason_history_area">(History)</a>
            </label>
            <div class="col-md-6 form-input-text" >
                {!! $areaData->decommissionedReason->description ?? null !!}
            </div>
        </div>
        <br>
        @include('forms.form_text',['title' => 'Data Stamp:', 'data' => $areaStamping['data_stamping'] ?? 'N/A' ])
        @include('forms.form_text',['title' => 'Organisation:', 'data' => $areaStamping['organisation'] ?? 'N/A' ])
        @include('forms.form_text',['title' => 'Username:', 'data' => $areaStamping['username'] ?? 'N/A' ])
        <br>
        @include('forms.form_text',['title' => 'Creation Date:', 'data' => $areaStamping['data_stamping_create'] ?? 'N/A' ])
        @include('forms.form_text',['title' => 'Organisation:', 'data' => $areaStamping['organisation_create'] ?? 'N/A' ])
        @include('forms.form_text',['title' => 'Username:', 'data' => $areaStamping['username_create'] ?? 'N/A' ])

        @include('modals.decommission_history',[ 'color' => 'red',
                                        'modal_id' => 'decommission_reason_history_area',
                                        'header' => 'Decommission Area/floor Reason',
                                        'table_id' => 'area_decommission_history_table',
                                        'url' => route('comment.decommission',['category' => 'area']),
                                        'data' => $areaData->decommissionCommentHistory,
                                        'id' => $areaData->id
                                     ])
    </div>
</div>
@if((\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(REGISTER_UPDATE_PRIV) and $canBeUpdateThisSite))
    @if($areaData->is_locked != AREA_LOCKED)
        <a  class="btn btn_long_size light_grey_gradient mt-2" data-toggle="modal" data-target="#add-area"><strong>Edit</strong></a>
        @include('modals.add_area',['color' => 'red','property' => $propertyData, 'modal_id' => 'add-area',
                'action' => 'edit', 'data' => $areaData,
                'position' => $position,
                'category' => $category,
                'pagination_type' => $pagination_type,
                'url' => route('survey.post_area', ['area_id' => $areaData->id])])
        @if(\CommonHelpers::checkDecommissionPermission())
            @if($areaData->decommissioned == 0)
                <a class="btn  light_grey_gradient mt-2" data-toggle="modal" data-target="#decommission_area">
                        <strong>Decommission</strong>
                </a>
                @include('modals.decommission',[ 'color' => 'red',
                                                    'modal_id' => 'decommission_area',
                                                    'header' => 'Decommission Area/floor',
                                                    'decommission_type' => 'area',
                                                    'name' => 'area_decommisson_reason_add',
                                                    'url' => route('survey.decommission_area', ['area_id' => $areaData->id]),
                                                    ])
            @else
                <a class="btn  light_grey_gradient mt-2" data-toggle="modal" data-target="#recommission_area">
                        <strong>Recommission</strong>
                </a>
                @include('modals.recommission',[ 'color' => 'red',
                                                'modal_id' => 'recommission_area',
                                                'header' => 'Recommission Area/floor',
                                                'decommission_type' => 'area',
                                                'name' => 'area_decommisson_reason_add',
                                                'url' => route('survey.decommission_area', ['area_id' => $areaData->id]),
                                                ])
            @endif
        @endif
    @else
        <div class="spanWarningSurveying col-md-5 mt-4">
            <strong><em>Area/Floor is view only while surveying is in progress</em></strong>
        </div>
    @endif
@endif
@include('vendor.pagination.simple-bootstrap-4-customize')
<div class="row">
    @include('tables.property_register_summary', [
        'title' => 'Area/floor Assessment Summary',
        'tableId' => 'area-assetment-summary',
        'collapsed' => false,
        'plus_link' => false,
        'normalTable' => true,
        'count' => $dataSummary["All ACM Items"]['number'],
        'data' => $dataSummary,
        'register' => true
        ])
</div>
<div class="row">
    @include('tables.property_decommissioned_items', [
        'title' => 'Area/floor Decommissioned Items',
        'tableId' => 'area-dec-item',
        'collapsed' => true,
        'plus_link' => false,
        'header' =>  ['Reference','Product/debris type','MAS','Reason', 'Item Comments'],
        'data' => $dataDecommisstionItems,
        'pagination_type' => $pagination_type
        ])
</div>

<div class="row">
    @include('tables.locations', [
        'title' => 'Area/floor Room/locations',
        'tableId' => 'area-room-table',
        'collapsed' => false,
        'plus_link' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(REGISTER_UPDATE_PRIV) and $canBeUpdateThisSite and ($areaData->is_locked == AREA_UNLOCKED)) ? true : false,
        'link' => route('get_add_location', ['area_id' => $areaData->id]),
        'data' => $dataTab
        ])
</div>

<div class="row">
    @include('tables.decommission_locations', [
        'title' => 'Area/floor Decommissioned Room/locations',
        'tableId' => 'area-dec-room-table',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $dataDecommisstionTab
        ])
</div>
