 <div class="row offset-top40">
    @include('shineCompliance.tables.live_incident_reports', [
        'title' => 'Live incident Reports',
        'data' => $data,
        'status' => 1,
        'tableId' => 'live-incident-reports',
        'collapsed' => false,
        'plus_link' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_INCIDENT_REPORT_EDIT)) ? true :false,
        'link' => route('shineCompliance.incident_reporting.get_add'),
        'row_col' => 'col-md-12',
        'order_table' => 'published'
     ])
</div>
