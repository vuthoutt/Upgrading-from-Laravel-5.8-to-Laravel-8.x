 <div class="row offset-top40">
    @include('shineCompliance.tables.documents_incident', [
        'title' => 'Incident Report Documents',
        'data' => $incident->documents,
        'status' => $incident->status,
        'tableId' => 'incident_report_documents',
        'collapsed' => false,
        'plus_link' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_INCIDENT_REPORT_EDIT) and !$is_locked) ? true :false,
        'modal_id' => 'add-incident-reporting-doc',
        'edit_permission' => (\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_INCIDENT_REPORT_EDIT) and !$is_locked) ? true :false,
        'row_col' => 'col-md-12',
        'order_table' => 'published'
     ])
     @include('shineCompliance.modals.add_incident_reporting_doc',[ 'modal_id' => 'add-incident-reporting-doc','action' => 'add','url' => route('shineCompliance.incident_reporting.post_document'),'incident_report_id' => $incident->id,'unique' => \Str::random(5), ])
</div>
