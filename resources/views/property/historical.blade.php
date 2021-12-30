<div class="row">
    @if(is_null($historical_categories))
    @else
        @foreach($historical_categories as $key => $category)
            @include('tables.historical_data', [
                'title' => $category->category,
                'tableId' => 'property-historic-table'.$key,
                'collapsed' => $loop->first ? false : true,
                'plus_link' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(HISTORICAL_DATA_UPDATE_PRIV) and $canBeUpdateThisSite) ? true : false,
                'modal_id' => 'historic-add-'.$category->id,
                'data' => $category->historicalDoc,
                'edit_permission' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(HISTORICAL_DATA_UPDATE_PRIV) and $canBeUpdateThisSite) ? true : false
                ])
                @include('modals.historical_add',['color' => 'red', 'modal_id' => 'historic-add-'.$category->id, 'url' => route('ajax.create_historical_doc'), 'id' =>$category->id, 'unique' => \Str::random(5) ])
        @endforeach
    @endif
</div>

