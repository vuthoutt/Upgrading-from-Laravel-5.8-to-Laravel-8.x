@extends('shineCompliance.tables.main_table', [
        'header' => ["Reference", "Type", "Reported By", "Property Reference","Property Name",'Status','File','Created Date']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
        <tr>
            <td><a href="{{ route('shineCompliance.incident_reporting.incident_Report',$dataRow->id) }}">{{ $dataRow->reference }} {{ ($dataRow->type == INCIDENT && $dataRow->confidential) ? '(Confidential)' : '' }}</a> </td>
            <td>{{ $dataRow->type_display ?? ''}}</td>
            <td>{{ \CommonHelpers::getUserFullname($dataRow->reported_by) }}</td>
            <td><a href="{{ route('shineCompliance.property.property_detail', $dataRow->property->id ?? 0) }}">{{ $dataRow->property->property_reference ?? '' }}</a></td>
            <td><a href="{{ route('shineCompliance.property.property_detail', $dataRow->property->id ?? 0) }}">{{ $dataRow->property->name ?? '' }}</a></td>
            <td>{{ $dataRow->status_display ?? '' }}</td>
            <td>{!! \CommonHelpers::getIncidentReportPdfViewing($dataRow->id, $dataRow->status) !!}</td>
            <td>{{ $dataRow->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        @endforeach
    @endif
@overwrite

