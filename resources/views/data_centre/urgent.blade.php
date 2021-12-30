@extends('data_centre.index')
@section('data_centre_content')
@include('tables.overdue_surveys_datacentre', [
    'title' => 'Urgent  Survey Deadlines',
    'tableId' => 'survey-urgent',
    'collapsed' => false,
    'plus_link' => false,
    'data' => $overdue_surveys
    ])

@include('tables.overdue_surveys_re_inspections_datacentre', [
    'title' => 'Urgent Survey Re-Inspection Deadlines ',
    'tableId' => 're-inspection-overdue-urgent',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $reinspection_sites
    ])

 @include('tables.overdue_documents_datacentre', [
    'title' => 'Urgent Planning Documents',
    'tableId' => 'planning-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $planningDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Urgent Pre-Start Documents',
    'tableId' => 'overdue-pre-start-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $preStartDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Urgent Site Records Documents',
    'tableId' => 'overdue-site-record-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $siteRecordDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Urgent Completion  Documents',
    'tableId' => 'overdue-completion-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $siteRecordDocs
    ])
@endsection
