@extends('data_centre.index')
@section('data_centre_content')
@if($survey_only_projects)
@include('tables.project_datacentre', [
    'title' => 'Survey Only Projects ',
    'tableId' => 'survey-project',
    'collapsed' => false,
    'plus_link' => false,
    'data' => $survey_only_projects,
    'order_table' => 'survey-table'
    ])
@endif
@if($remediation_projects)
@include('tables.project_datacentre', [
    'title' => 'Remediation/Removal Projects',
    'tableId' => 'project-remediation',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $remediation_projects,
    'order_table' => 'survey-table'
    ])
@endif
@if($demolition_projects)
@include('tables.project_datacentre', [
    'title' => 'Demolition Projects',
    'tableId' => 'project-demolition',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $demolition_projects,
    'order_table' => 'survey-table'
    ])
@endif
@if($analytical_projects)
@include('tables.project_datacentre', [
    'title' => 'Analytical Projects ',
    'tableId' => 'project-analyrical',
    'collapsed' => true,
    'plus_link' => false,
    'data' => $analytical_projects,
    'order_table' => 'survey-table'
    ])
@endif
@endsection
