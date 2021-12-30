<div class="row">
    @include('tables.historical_categories', [
        'title' => 'Historical Categories',
        'tableId' => 'property-historic-cat-table',
        'collapsed' => false,
        'plus_link' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(HISTORICAL_DATA_UPDATE_PRIV) and $canBeUpdateThisSite) ? true : false,
        'modal_id' => 'historic-cat-add',
        'data' => $historical_categories,
        'edit_permission' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(HISTORICAL_DATA_UPDATE_PRIV) and $canBeUpdateThisSite) ? true : false
        ])
        @include('modals.historical_category',['color' => 'red', 'modal_id' => 'historic-cat-add', 'title' => 'Add Historical Category', 'url' => route('ajax.historical-category'), 'type' => '-add'])
        @include('modals.historical_category',['color' => 'red', 'modal_id' => 'historic-cat-edit', 'title' => 'Edit Historical Category', 'url' => route('ajax.historical-category'), 'type' => '-edit'])
</div>

