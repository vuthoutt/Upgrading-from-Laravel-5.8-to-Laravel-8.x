@extends('tables.main_table', [
        'header' => ["Property UPRN", "Property Name", "Re-Inspection Date", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->UPRN }}</a></td>
            {{-- <td>{{ $dataRow->BlockName }}</td> --}}
            <td><a href="{{ route('property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->name }}</a></td>
            @php
                if(isset($dataRow->completed_date)) {
                    $dueDate = $dataRow->completed_date + (730 * 86400);
                } else {
                     $dueDate = time();
                }
                $overDueDate = \CommonHelpers::getDaysRemaninng($dueDate);
            @endphp
            <td>{{ CommonHelpers::convertTimeStampToTime($dueDate) }}</td>
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
