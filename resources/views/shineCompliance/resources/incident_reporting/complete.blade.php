<div class="row offset-top40">
    @include('shineCompliance.tables.live_incident_reports', [
        'title' => 'Completed incident Reports',
        'data' => $data,
        'status' => 1,
        'tableId' => 'completed-incident-reports',
        'collapsed' => false,
        'plus_link' => false,
        'row_col' => 'col-md-12',
        'order_table' => 'published'
     ])
</div>
