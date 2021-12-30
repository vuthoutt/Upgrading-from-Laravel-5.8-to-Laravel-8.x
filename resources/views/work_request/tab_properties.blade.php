<div class="row">
        @include('tables.properties', [
        'title' => 'Properties',
        'tableId' => 'properties',
        'collapsed' => false,
        'plus_link' => false,
        'data' => $data
        ])

</div>