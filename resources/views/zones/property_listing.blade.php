 <div class="row ">
    @if($type)
        @if($type == TYPE_INACCESS_ROOM_SUMMARY)
            @include('tables.locations',[
                'title' => $title,
                'tableId' => $table_id,
                'collapsed' => false,
                'plus_link' => false,
                'data' => $items_summary_table,
                'pagination_type' => $pagination_type,
                ])
        @else
        @include('tables.item_summary', [
            'title' => $title,
            'tableId' => $table_id,
            'collapsed' => false,
            'plus_link' => false,
            'data' => $items_summary_table,
            'pagination_type' => $pagination_type,
            'summary' => true,
            'property_head' => true,
            'risk_table' => true,
            'header' => ['Property','Summary','Area/floor Reference','Room/location Reference','Product/debris type', 'MAS',''],
            'order_table' => 'mas-risk'
            ])
        @endif
    @else
        @include('tables.property_register_summary', [
            'title' => 'Client Register Summary',
            'tableId' => 'client-register-summary',
            'collapsed' => false,
            'plus_link' => false,
            'normalTable' => true,
            'count' => $dataSummary["All ACM Items"]['number'],
            'data' => $dataSummary,
            'register' => true
            ])
    @endif

    @include('tables.properties', [
        'title' => 'Properties',
        'tableId' => 'properties',
        'collapsed' => false,
        'plus_link' => \CommonHelpers::isSystemClient() ? true : false,
        'data' => $properties,
        'link' => route('property.get_add', ['client_id' => $client_id, 'zone_id' => $zone->id]),
        ])
</div>
