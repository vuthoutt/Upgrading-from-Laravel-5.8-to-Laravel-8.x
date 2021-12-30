@extends('data_centre.index')
@section('data_centre_content')
@if(!\CompliancePrivilege::checkPermission(RESOURCES_WORK_REQUEST) and \CommonHelpers::isSystemClient())
@else
@include('tables.work_rejected', [
    'title' => 'Rejected Work Request Notifications',
    'tableId' => 'work-rejected',
    'collapsed' => false,
    'plus_link' => false,
    'data' => $works,
    'order_table' => 'survey-approve-table',
    'order_table' => 'wr-table'
    ])
@include('modals.rejected_note',[ 'modal_id' => 'rejected-work', 'header' => 'Work Request Rejection Note','type' => 'work-rejected' ])
@endif
@include('tables.survey_rejected', [
    'title' => 'Rejected Survey Notifications',
    'tableId' => 'survey-rejected',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $surveys,
    'order_table' => 'survey-approve-table'
    ])
@include('modals.rejected_note',[ 'modal_id' => 'rejected-note', 'header' => 'Survey Rejection Note','type' => 'survey-rejected' ])

@if($docs)
    @include('tables.document_rejected', [
        'title' => 'Rejected Contractor Document Notifications',
        'tableId' => 'document-rejected',
        'collapsed' => true,
        'plus_link' => false,
        'order_table' => 'survey-approve-table',
        'data' => $docs
        ])
    @include('modals.rejected_note',[ 'modal_id' => 'rejected-document', 'header' => 'Document Rejection Note','type' => 'document-rejected' ])
@endif

@if($planning_docs)
    @include('tables.document_rejected', [
        'title' => 'Rejected Planning Document Notifications',
        'tableId' => 'planning-rejected',
        'collapsed' => true,
        'plus_link' => false,
        'order_table' => 'survey-approve-table',
        'data' => $planning_docs
        ])
    @include('modals.rejected_note',[ 'modal_id' => 'rejected-planning-document', 'header' => 'Document Rejection Note','type' => 'planning-rejected' ])
@endif

@if($pre_start_docs)
    @include('tables.document_rejected', [
        'title' => 'Rejected Pre-Start Document Notifications',
        'tableId' => 'pre_start-rejected',
        'collapsed' => true,
        'plus_link' => false,
        'order_table' => 'survey-approve-table',
        'data' => $pre_start_docs
        ])
    @include('modals.rejected_note',[ 'modal_id' => 'rejected-pre_start-document', 'header' => 'Document Rejection Note','type' => 'document-pre_start-rejected' ])
@endif

@if($site_records_docs)
    @include('tables.document_rejected', [
        'title' => 'Rejected Site Records Document Notifications',
        'tableId' => 'site_records-rejected',
        'collapsed' => true,
        'plus_link' => false,
        'order_table' => 'survey-approve-table',
        'data' => $site_records_docs
        ])
    @include('modals.rejected_note',[ 'modal_id' => 'rejected-site_records-document', 'header' => 'Document Rejection Note','type' => 'document-site_records-rejected' ])
@endif

@if($completion_docs)
    @include('tables.document_rejected', [
        'title' => 'Rejected Completions Document Notifications',
        'tableId' => 'completion_docs-rejected',
        'collapsed' => true,
        'plus_link' => false,
        'order_table' => 'survey-approve-table',
        'data' => $completion_docs
        ])
    @include('modals.rejected_note',[ 'modal_id' => 'rejected-completion_docs-document', 'header' => 'Document Rejection Note','type' => 'document-completion_docs-rejected' ])
@endif

@endsection
