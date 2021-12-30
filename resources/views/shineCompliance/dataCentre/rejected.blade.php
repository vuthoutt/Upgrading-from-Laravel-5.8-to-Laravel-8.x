@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' =>'data_center', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">Rejected</h3>
        </div>

        <div class="row">
            @include('shineCompliance.dataCentre.partials._data_centre_sidebar')

            <div class="col-md-9 pl-0" style="padding: 0">
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_REJECTED,JOB_ROLE_FIRE)
                and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_REJECTED,JOB_ROLE_WATER))

                @else
                @include('shineCompliance.tables.assessment_rejected', [
                    'title' => 'Assessment Rejection Notifications',
                    'row_col' => 'col-md-12',
                    'tableId' => 'assessment-rejected',
                    'collapsed' => false,
                    'plus_link' => false,
                    'data' => $assessments,
                    'order_table' => 'survey-approve-table'
                    ])
                @include('shineCompliance.modals.rejected_note',[ 'modal_id' => 'rejected-assessment-note', 'header' => 'Assessment Rejection Note','type' => 'assessment-rejected', 'color' => 'red' ])
                @endif
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_REJECTED,JOB_ROLE_ASBESTOS))
                @else
                    @include('shineCompliance.tables.audit_rejected', [
                        'title' => 'Audit Rejection Notifications',
                        'row_col' => 'col-md-12',
                        'tableId' => 'audit-rejected',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => [],
                        'order_table' => 'survey-approve-table'
                        ])

                    @include('shineCompliance.tables.certificate_rejected', [
                        'title' => 'Certificate Rejection Notifications',
                        'row_col' => 'col-md-12',
                        'tableId' => 'certificate-rejected',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => [],
                        'order_table' => 'survey-approve-table'
                        ])
                @endif
                @include('shineCompliance.tables.incident_rejected', [
                    'title' => 'Incident Report Rejection Notifications',
                    'row_col' => 'col-md-12',
                    'tableId' => 'incident-rejected',
                    'collapsed' => true,
                    'plus_link' => false,
                    'data' => $rejectIncident,
                    'order_table' => 'survey-approve-table'
                    ])
                @include('shineCompliance.modals.rejected_note',[ 'modal_id' => 'rejected-incident-note', 'header' => 'Survey Rejection Note','type' => 'survey-rejected' ])

                @include('shineCompliance.tables.survey_rejected', [
                    'title' => 'Survey Rejection Notifications',
                    'tableId' => 'survey-rejected',
                    'row_col' => 'col-md-12',
                    'collapsed' => true,
                    'plus_link' => false,
                    'data' => $surveys,
                    'order_table' => 'survey-approve-table'
                    ])
                @include('shineCompliance.modals.rejected_note',[ 'modal_id' => 'rejected-note', 'header' => 'Survey Rejection Note','type' => 'survey-rejected' ])

                @include('shineCompliance.tables.work_rejected', [
                        'title' => 'Rejected Work Request Notifications',
                        'tableId' => 'work-rejected',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $works,
                        'order_table' => 'survey-approve-table',
                        'order_table' => 'wr-table'
                        ])
                @include('modals.rejected_note',[ 'modal_id' => 'rejected-work', 'header' => 'Work Request Rejection Note','type' => 'work-rejected' ])

                @if($projectDocs)
                    @include('shineCompliance.tables.document_rejected', [
                        'title' => 'Project Document Rejection Notifications',
                        'tableId' => 'project-doc-rejected',
                        'row_col' => 'col-md-12',
                        'collapsed' => true,
                        'plus_link' => false,
                        'order_table' => 'survey-approve-table',
                        'data' => $projectDocs
                        ])
                    @include('shineCompliance.modals.rejected_note',[ 'modal_id' => 'rejected-project-document', 'header' => 'Document Rejection Note','type' => 'planning-rejected' ])
                @endif
            </div>
        </div>
    </div>
@endsection
