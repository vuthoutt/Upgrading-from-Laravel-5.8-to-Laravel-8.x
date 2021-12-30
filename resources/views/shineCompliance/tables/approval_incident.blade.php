@extends('shineCompliance.tables.main_table', [
        'header' => ['Reference','Type', 'Published Date', 'Property Name', 'File', 'Confirmation'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
            <tr>
                <td width="8%"><a href="{{ route('shineCompliance.incident_reporting.incident_Report',$dataRow->id) }}">{{ $dataRow->reference }}</a></td>
                <td>{{ $dataRow->incidentType->description ?? ''}}</td>
                <td width="15%">{{ $dataRow->published_date ?? ''}}</td>
                <td><a href="{{ route('shineCompliance.property.property_detail', $dataRow->property->id) }}">{{ $dataRow->property->name ?? '' }}</a></td>
                <td width="8%">{!! \CommonHelpers::getIncidentReportPdfViewing($dataRow->id, $dataRow->status) !!}</td>
                <td class="approval-button" width="18%">
                    @if(\Auth::user()->id == $dataRow->asbestos_lead || \Auth::user()->id == $dataRow->second_asbestos_lead)
                        <input type="image" data-incident-id="{{ $dataRow->id }}" data-incident-ref="{{ $dataRow->reference }}" data-type="cancel" data-toggle="modal" data-target="#cancel-incident" src="{{asset('img/cancel.png')}}" alt="Cancel" title="Cancel Document Publish">
                        <input type="image" data-incident-id="{{ $dataRow->id }}" data-incident-ref="{{ $dataRow->reference }}" data-type="approval" data-toggle="modal" data-target="#approval-incident" src="{{asset('img/approval.png')}}" alt="Approval" title="Approve Document">
                        <input type="image" data-incident-id="{{ $dataRow->id }}" data-incident-ref="{{ $dataRow->reference }}" data-type="reject" data-toggle="modal" data-target="#rejected-incident" src="{{asset('img/reject.png')}}" alt="Reject" title="Reject Document">
                    @endif
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

