@if($assessment->classification == ASSESSMENT_FIRE_TYPE)
    @php($headers = ['Questions','Responses','Additional Comments', 'Observations'])
@else
    @php($headers = ['Questions','Responses','Additional Comments'])
@endif
@extends('shineCompliance.tables.main_table', [
            'header' => $headers
        ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td style="width: 30%">{{ $dataRow->description ?? '' }}</td>
            <td style="width: 10%">{{ $dataRow->answer->answerType->description ?? ''}}</td>
            <td >{{ $dataRow->answer->other ?? ''}}</td>
            @if($assessment->classification == ASSESSMENT_FIRE_TYPE)
            <td  style="width: 30%">
                @if(isset($dataRow->answer))
                    {{ $dataRow->answer->observations ?? ''}}
                @endif
            </td>
            @endif
        </tr>
        @endforeach
    @endif
@overwrite
