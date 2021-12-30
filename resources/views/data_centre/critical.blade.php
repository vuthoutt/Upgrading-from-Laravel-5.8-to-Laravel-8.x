@extends('data_centre.index')
@section('data_centre_content')
@include('tables.missing_survey_table', [
    'title' => 'Missing Management Surveys',
    'tableId' => 'survey-missing-critical',
    'collapsed' => false,
    'plus_link' => false,
    'data' => $missing_surveys
    ])

@include('tables.overdue_surveys_datacentre_critical', [
    'title' => 'Overdue Surveys',
    'tableId' => 'survey-overdue-critical',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $overdue_surveys
    ])

@include('tables.overdue_surveys_re_inspections_datacentre_critical2', [
    'title' => 'Overdue Surveys Re-Inspections',
    'tableId' => 're-inspection-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $reinspection_sites
    ])

 @include('tables.overdue_documents_datacentre', [
    'title' => 'Overdue Planning Documents',
    'tableId' => 'planning-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $planningDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Overdue Pre-Start Documents',
    'tableId' => 'overdue-pre-start-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $preStartDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Overdue Site Records Documents',
    'tableId' => 'overdue-site-record-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $siteRecordDocs
    ])

@include('tables.overdue_documents_datacentre', [
    'title' => 'Overdue Completion  Documents',
    'tableId' => 'overdue-completion-document-overdue',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $siteRecordDocs
    ])
@endsection
