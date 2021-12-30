@extends('shineCompliance.tables.main_table', [
        'header' => ["Property UPRN","Block", "Property Name", "Re-Inspection Date","Type", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->UPRN }}</a></td>
            <td>{{ $dataRow->BlockName }}</td>
            <td><a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->name }}</a></td>
            @php
                if(isset($dataRow->completed_date)) {
                    $dueDate = $dataRow->completed_date + (730 * 86400);
                } else {
                     $dueDate = time();
                }
                $overDueDate = \CommonHelpers::getDaysRemaninng($dueDate);
            @endphp
            <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dueDate) }} </span>{{ CommonHelpers::convertTimeStampToTime($dueDate) }}</td>
            <td></td>
            @if($overDueDate > 0)
                <td>
                    <span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>{{ $overDueDate  }}</span> Days Remaining
                </td>
            @else
                <td>
                    <span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>Overdue</span>
                </td>
            @endif
        </tr>
        @endforeach
    @endif
@overwrite
