@extends('data_centre.index')
@section('data_centre_content')
@include('tables.overdue_surveys_datacentre', [
    'title' => 'Survey Deadline Warning',
    'tableId' => 'survey-deadline',
    'collapsed' => false,
    'plus_link' => false,
    'data' => $overdue_surveys
    ])

@include('tables.overdue_surveys_re_inspections_datacentre_critical2', [
    'title' => 'Survey Re-Inspection Deadline Warnings',
    'tableId' => 're-inspection-overdue-deadline',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $reinspection_sites
    ])

 @include('tables.overdue_documents_datacentre', [
    'title' => 'Planning Documents Deadline Warning',
    'tableId' => 'planning-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $planningDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Pre-Start Documents Deadline Warning',
    'tableId' => 'overdue-pre-start-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $preStartDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Site Records Documents Deadline Warning',
    'tableId' => 'overdue-site-record-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $siteRecordDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Completion Documents Deadline Warning',
    'tableId' => 'overdue-completion-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $siteRecordDocs
    ])
@endsection
