@extends('tables.main_table', [
        'header' => ["Property UPRN", "Property Block", "Property Name", "Re-Inspection Date", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->UPRN }}</a></td>
            <td>{{ $dataRow->BlockName }}</td>
            <td><a href="{{ route('property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->name }}</a></td>
            <td>{{ CommonHelpers::convertTimeStampToTime($dataRow->lastDate) }}</td>
            <td>
                @if($dataRow->lastDay > 0)
                    <span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>{{ $dataRow->lastDay }}</span> Days Remaining
                @else
                    <span class="badge {{$risk_color}}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }} style="font-size: 85% !important">Overdue</span>
                @endif
            </td>
        </tr>
        @endforeach
    @endif
@overwrite
