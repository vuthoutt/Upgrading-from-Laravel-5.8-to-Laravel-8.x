@extends('shineCompliance.layouts.app')
@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'home_shineCompliance', 'color' => 'red'])
<div class="container prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $data->full_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        <div class="row mt-4">
            @include('shineCompliance.user.partials._data_centre_sidebar')
            <div class="col-md-9 pl-0" style="padding: 0">
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_FIRE_APPROVAL,JOB_ROLE_FIRE)
                and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_WATER_APPROVAL,JOB_ROLE_WATER))

                @else

                    @include('shineCompliance.tables.approval_assessment', [
                        'title' => 'Assessment Approval Notifications',
                        'tableId' => 'assessment-approval-table',
                        'row_col' => 'col-md-12',
                        'collapsed' => false,
                        'plus_link' => false,
                        'link' => '',
                        'data' => $approvalAssessments,
                        'order_table' => "[]"
                        ])
                    @include('shineCompliance.modals.approval_assessment',[ 'modal_id' => 'approval-assessment', 'color' => '#cc292d', 'header' => 'Assessment Approval','type' => 'approval-assessment' ])
                    @include('shineCompliance.modals.reject_assessment',[ 'modal_id' => 'rejected-assessment', 'color' => '#cc292d', 'header' => 'Assessment Rejection','type' => 'reject-assessment' ])
                    @include('shineCompliance.modals.cancel_assessment',[ 'modal_id' => 'cancel-assessment',  'color' => '#cc292d','header' => 'Assessment Cancel','type' => 'cancel-assessment' ])

                @endif

                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_APPROVAL,JOB_ROLE_ASBESTOS))
                @else
                    @include('shineCompliance.tables.approval_audit', [
                        'title' => 'Audit Approval Notifications',
                        'tableId' => 'audit-approval-table',
                        'row_col' => 'col-md-12',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => [],
                        'order_table' => "[]"
                        ])

                    @include('shineCompliance.tables.approval_certificate', [
                        'title' => 'Certificate Approval Notifications',
                        'tableId' => 'cert-approval-table',
                        'row_col' => 'col-md-12',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => [],
                        'order_table' => "[]"
                        ])
                @endif

                @include('shineCompliance.tables.approval_incident', [
                    'title' => 'Incident Report Approval Notifications',
                    'tableId' => 'incident-approval-table',
                    'row_col' => 'col-md-12',
                    'collapsed' => true,
                    'plus_link' => false,
                    'data' => $approvalIncident,
                    'order_table' => "[]"
                    ])
                @include('modals.cancel_incident',[ 'modal_id' => 'cancel-incident', 'header' => 'Incident Report Cancel','type' => 'cancel-incident' ])
                @include('modals.approval_incident',[ 'modal_id' => 'approval-incident', 'header' => 'Incident Report Approval','type' => 'approval-incident' ])
                @include('modals.reject_incident',[ 'modal_id' => 'rejected-incident', 'header' => 'Incident Report Rejection','type' => 'reject-incident' ])

                @include('shineCompliance.tables.approval_survey', [
                    'title' => 'Survey Approval Notifications',
                    'tableId' => 'survey-approval-table',
                    'row_col' => 'col-md-12',
                    'collapsed' => true,
                    'plus_link' => false,
                    'data' => $approvalSurveys,
                    'order_table' => "[]"
                    ])
                @include('modals.approval_survey',[ 'modal_id' => 'approval-survey', 'header' => 'Survey Approval','type' => 'approval' ])
                @include('modals.reject_survey',[ 'modal_id' => 'rejected-survey', 'header' => 'Survey Rejection','type' => 'reject' ])
                @include('modals.cancel_survey',[ 'modal_id' => 'cancel-survey', 'header' => 'Survey Cancel','type' => 'cancel' ])

                @include('shineCompliance.tables.work_request_approval', [
                    'title' => 'Work Request Notifications',
                    'tableId' => 'work_request-approval',
                    'collapsed' => true,
                    'plus_link' => false,
                    'data' => $works,
                    'order_table' => 'wr-table'
                    ])
                @include('shineCompliance.modals.approval_work_request',[ 'modal_id' => 'approval-work', 'header' => 'Work Request Approval','type' => 'approval_wr' ])
                @include('shineCompliance.modals.approval_major_work_request',[ 'modal_id' => 'approval-work-major', 'header' => 'Programmed Work Request Approval','type' => 'major_approval_wr' ])
                @include('shineCompliance.modals.reject_work_request',[ 'modal_id' => 'rejected-work', 'header' => 'Work Request Rejection','type' => 'reject_wr' ])

                @include('shineCompliance.tables.approval_project_docs', [
                    'title' => 'Project Document Approval Notifications',
                    'tableId' => 'project-approval-table',
                    'row_col' => 'col-md-12',
                    'collapsed' => true,
                    'plus_link' => false,
                    'data' => $approvalProjectDocs,
                    'order_table' => "[]"
                    ])
                @include('modals.project_doc_cancel',[ 'modal_id' => 'project-cancel'.($unique ?? ''),'color' => 'red', 'header' => 'shinePrism - Document Cancel','unique' => ($unique ?? '') ])
                @include('modals.project_doc_confirm',[ 'modal_id' => 'project-confirm'.($unique ?? ''),'color' => 'red', 'header' => 'shinePrism - Document Approval','unique' => ($unique ?? '')])
                @include('modals.project_doc_reject',[ 'modal_id' => 'project-reject'.($unique ?? ''),'color' => 'red', 'header' => 'shinePrism - Document Rejection', 'url' => route('document.reject'),'unique' => ($unique ?? '') ])
            </div>
        </div>
    </div>
</div>
@endsection
