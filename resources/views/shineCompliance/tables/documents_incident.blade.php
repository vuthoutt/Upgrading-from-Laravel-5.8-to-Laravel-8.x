@extends('shineCompliance.tables.main_table', [
        'header' => ['Document Name','Reference','Last Revision','View']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
                <td><a href="#edit-incident-reporting-doc-{{$dataRow->id}}" data-toggle="modal">{{ $dataRow->filename }}</a></td>
            @else
                <td>{{ $dataRow->filename }}</td>
            @endif
            <td>{{ $dataRow->reference ?? '' }}</td>
            <td>{{ $dataRow->created_at ? $dataRow->created_at->format('d/m/Y') : '' }}</td>
            <td>{!! \CommonHelpers::getIncidentDocumentFilePdfViewing($dataRow->id, $dataRow->filename, $status) !!}</td>
        </tr>
        @include('shineCompliance.modals.add_incident_reporting_doc',[ 'modal_id' => 'edit-incident-reporting-doc-'.$dataRow->id ,'action' => 'edit',
                            'url' => route('shineCompliance.incident_reporting.post_document'),
                            'incident_report_id' => $dataRow->incident_report_id,
                            'unique' => \Str::random(5),
                            'data' => $dataRow ])
        @endforeach
    @endif
@overwrite
