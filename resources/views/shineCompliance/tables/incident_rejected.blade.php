@extends('shineCompliance.tables.main_table', [
        'header' => ['Reference','Type','Property Name', 'File', 'Rejected Note'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
            <tr>
                <td width="8%"><a href="{{ route('shineCompliance.incident_reporting.incident_Report',$dataRow->id) }}">{{ $dataRow->reference }}</a></td>
                <td>{{ $dataRow->incidentType->description ?? ''}}</td>
                <td><a href="{{ route('shineCompliance.property.property_detail', $dataRow->property->id) }}">{{ $dataRow->property->name ?? '' }}</a></td>
                <td width="8%">{!! \CommonHelpers::getIncidentReportPdfViewing($dataRow->id, $dataRow->status) !!}</td>
                <td width="15%">
                    <a href="#rejected-incident-note" data-note="{{ $dataRow->comments ?? '' }}" data-due-date="{{ CommonHelpers::convertTimeStampToTime($dataRow->getOriginal('due_date') ?? 0) }}" data-survey-ref="{{ $dataRow->reference ?? '' }}" data-toggle="modal" alt="Rejection Note" title="Rejection Note">View</a>
                </td>
            </tr>
        @endforeach
    @endif
@overwrite
@push('css')
    <style>
        .approval_assessment {
            border: none !important;
            border-radius: unset !important;
        }
    </style>
@endpush

