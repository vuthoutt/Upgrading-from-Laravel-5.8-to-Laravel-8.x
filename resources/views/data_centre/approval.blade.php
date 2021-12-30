@extends('data_centre.index')
@section('data_centre_content')

@if(!\CompliancePrivilege::checkPermission(RESOURCES_WORK_REQUEST) and \CommonHelpers::isSystemClient())
@else
@include('tables.work_request_approval', [
    'title' => 'Work Request Notifications',
    'tableId' => 'work_request-approval',
    'collapsed' => false,
    'plus_link' => false,
    'data' => $works,
    'order_table' => 'wr-table'
    ])

@include('modals.approval_work_request',[ 'modal_id' => 'approval-work', 'header' => 'Work Request Approval','type' => 'approval_wr' ])
@include('modals.approval_major_work_request',[ 'modal_id' => 'approval-work-major', 'header' => 'Programmed Work Request Approval','type' => 'major_approval_wr' ])
@include('modals.reject_work_request',[ 'modal_id' => 'rejected-work', 'header' => 'Work Request Rejection','type' => 'reject_wr' ])
@endif
@if($approval_survey)
    @include('tables.survey_approval', [
        'title' => 'Survey Approval Notifications',
        'tableId' => 'survey-approval',
        'collapsed' => true,
        'plus_link' => false,
        'data' => $surveys,
        'asbestos_lead_admin' => $asbestos_lead_admin,
        'order_table' => 'survey-approve-table-2'
        ])
    @include('modals.approval_survey',[ 'modal_id' => 'approval-survey', 'header' => 'Survey Approval','type' => 'approval' ])
    @include('modals.reject_survey',[ 'modal_id' => 'rejected-survey', 'header' => 'Survey Rejection','type' => 'reject' ])
    @include('modals.cancel_survey',[ 'modal_id' => 'cancel-survey', 'header' => 'Survey Cancel','type' => 'cancel' ])
@endif

@if($approval_contractor_doc)
    @if($docs)
        @include('tables.document_approval', [
            'title' => 'Contractor Document Approval Notifications',
            'tableId' => 'document-approval',
            'collapsed' => true,
            'plus_link' => false,
            'asbestos_lead_admin' => $asbestos_lead_admin,
            'order_table' => 'survey-approve-table',
            'data' => $docs
            ])
    @endif
@endif

@if($approval_planning)
{{--     @if($docs) --}}
        @include('tables.document_approval', [
            'title' => 'Planning Document Approval Notifications',
            'tableId' => 'planning-approval',
            'collapsed' => true,
            'plus_link' => false,
            'data' => $planning_docs,
            'order_table' => 'survey-approve-table',
            'asbestos_lead_admin' => $asbestos_lead_admin,
            'unique' => \Str::random(5)
            ])
    {{-- @endif --}}
@endif

@if($approval_pre_start)
    {{-- @if($docs) --}}
        @include('tables.document_approval', [
            'title' => 'Pre-Start Document Approval Notifications',
            'tableId' => 'pre_start-approval',
            'collapsed' => true,
            'plus_link' => false,
            'order_table' => 'survey-approve-table',
            'asbestos_lead_admin' => $asbestos_lead_admin,
            'data' => $pre_start_docs,
            'unique' => \Str::random(5)
            ])
    {{-- @endif --}}
@endif

@if($approval_site_records)
    {{-- @if($docs) --}}
        @include('tables.document_approval', [
            'title' => 'Site Records Document Approval Notifications',
            'tableId' => 'site_records-approval',
            'collapsed' => true,
            'plus_link' => false,
            'order_table' => 'survey-approve-table',
            'asbestos_lead_admin' => $asbestos_lead_admin,
            'data' => $site_records_docs,
            'unique' => \Str::random(5)
            ])
    {{-- @endif --}}
@endif

@if($approval_completion)
    {{-- @if($docs) --}}
        @include('tables.document_approval', [
            'title' => 'Completions Document Approval Notifications',
            'tableId' => 'completion-approval',
            'collapsed' => true,
            'plus_link' => false,
            'order_table' => 'survey-approve-table',
            'asbestos_lead_admin' => $asbestos_lead_admin,
            'data' => $completion_docs,
            'unique' => \Str::random(5)
            ])
    {{-- @endif --}}
@endif
@overwrite
