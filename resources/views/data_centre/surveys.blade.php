@extends('data_centre.index')
@section('data_centre_content')
@include('tables.survey_datacentre', [
    'title' => 'Management Surveys',
    'tableId' => 'survey-management',
    'collapsed' => false,
    'plus_link' => false,
    'data' => $management_surveys,
    'order_table' => 'survey-table'
    ])

@include('tables.survey_datacentre', [
    'title' => 'Management Survey – Partial',
    'tableId' => 'survey-management-partial',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $management_surveys_partial,
    'order_table' => 'survey-table'
    ])

@include('tables.survey_datacentre', [
    'title' => 'Refurbishment Surveys',
    'tableId' => 'survey-refurbishment',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $refurbish_surveys,
    'order_table' => 'survey-table'
    ])

@include('tables.survey_datacentre', [
    'title' => 'Re-inspection Reports',
    'tableId' => 'inspection-survey',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $reinspection_surveys,
    'order_table' => 'survey-table'
    ])

@include('tables.survey_datacentre', [
    'title' => 'Demolition Surveys',
    'tableId' => 'survey-demolition',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $demolition_surveys,
    'order_table' => 'survey-table'
    ])

@include('tables.survey_datacentre', [
    'title' => 'Sample Survey',
    'tableId' => 'survey-sample',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $sample_surveys,
    'order_table' => '[[0, "desc"]]'
    ])

{{-- @include('tables.survey_datacentre', [
    'title' => 'No ACM Management Survey',
    'tableId' => 'no-acm-demolition',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $noACM_surveys
    ]) --}}
@endsection
