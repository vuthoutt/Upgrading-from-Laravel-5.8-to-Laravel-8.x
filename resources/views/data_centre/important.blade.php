@extends('data_centre.index')
@section('data_centre_content')
@include('tables.overdue_surveys_datacentre', [
    'title' => 'Important  Survey Deadlines',
    'tableId' => 'survey-important',
    'collapsed' => false,
    'plus_link' => false,
    'data' => $overdue_surveys
    ])

@include('tables.overdue_surveys_re_inspections_datacentre', [
    'title' => 'Important Survey Re-Inspection Deadlines',
    'tableId' => 're-inspection-overdue-important',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $reinspection_sites
    ])

 @include('tables.overdue_documents_datacentre', [
    'title' => 'Important Planning Documents',
    'tableId' => 'planning-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $planningDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Important Pre-Start Documents',
    'tableId' => 'overdue-pre-start-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $preStartDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Important Site Records Documents',
    'tableId' => 'overdue-site-record-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $siteRecordDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Important Completion  Documents',
    'tableId' => 'overdue-completion-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $siteRecordDocs
    ])
@endsection
