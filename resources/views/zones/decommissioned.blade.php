 <div class="row ">
    @include('tables.properties', [
        'title' => 'Deccomissioned Properties',
        'tableId' => 'deccomissioned-properties',
        'collapsed' => false,
        'plus_link' => true,
        'data' => $decommissioned_properties,
        ])
</div>