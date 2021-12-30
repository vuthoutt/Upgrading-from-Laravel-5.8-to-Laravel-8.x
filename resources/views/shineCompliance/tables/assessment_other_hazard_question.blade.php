@php($headers = ['Questions','Responses','Additional Comments'])
@extends('shineCompliance.tables.main_table', [
            'header' => $headers
        ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td style="width: 30%">{{ $dataRow->otherHazardQuestion->description ?? '' }}</td>
            <td style="width: 10%">{{ $dataRow->answerType->description ?? ''}}</td>
            <td >{{ $dataRow->other ?? ''}}</td>
        </tr>
        @endforeach
    @endif
@overwrite
