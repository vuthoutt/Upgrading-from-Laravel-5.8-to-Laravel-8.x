@extends('shineCompliance.tables.main_table', [
        'header' => ["Document", "Last Updated", "File"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
        <tr>
            <td>{{ $dataRow->name }} {{ $key == 0 ? '(Current)' : '' }}</td>
            <td>{{ $dataRow->created_at->format('d/m/Y H:i')}}</td>
            @if($key == 0)
                <td>{!! \CommonHelpers::getIncidentReportPdfViewing($dataRow->id, $status, false) !!}</td>
            @else
                <td>{!! \CommonHelpers::getIncidentReportPdfViewing($dataRow->id, 5, false) !!}</td>
            @endif
        </tr>
        @endforeach
    @endif
@overwrite

