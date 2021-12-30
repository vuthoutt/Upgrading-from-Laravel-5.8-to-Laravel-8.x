<div class="row">
    @include('tables.property_register_summary', [
        'title' => 'Property Register Summary',
        'count' => $dataSummary["All ACM Items"]['number'],
        'data' => $dataSummary,
        'register' => true,
        'plus_link' => false,
        'collapsed' => false
        ])
</div>
<div class="row">
    @include('tables.property_decommissioned_items', [
        'title' => 'Property Decommissioned Items',
        'tableId' => 'property-dec-item',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $dataDecommisstionItems,
        'header' => ['Reference','Product/debris type','MAS','Reason', 'Item Comments']
        ])
</div>

<div class="row">
    @include('tables.areas', [
        'title' => 'Property Area/floors',
        'tableId' => 'property-floor-table',
        'collapsed' => false,
        'plus_link' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(REGISTER_UPDATE_PRIV) and $canBeUpdateThisSite) ? true : false,
        'modal_id' => 'add-area-register',
        'data' => $areas
        ])
</div>

<div class="row">
    @include('tables.decommission_areas', [
        'title' => 'Property Decommissioned Area/floors',
        'tableId' => 'property-dec-floor-table',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $decommissionedAreas
        ])
</div>
@include('modals.add_area',['property' => $propertyData, 'modal_id' => 'add-area-register', 'action' => 'create' , 'url' => route('survey.post_area')])
