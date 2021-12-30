@extends('shineCompliance.tables.main_table', [
        'header' => ["Reference","Property UPRN", "Property Name", "Missing Date", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT])  }}">{{ $dataRow->reference }}</a></td>
            <td><a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT])  }}">{{ $dataRow->property_reference }}</a></td>
            <td><a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT])  }}">{{ $dataRow->name }}</a></td>
            <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp(\Carbon\Carbon::createFromFormat('d/m/Y', $dataRow->created_at ?? '')->timestamp) }} </span>{{ $dataRow->created_at ?? ''}}</td>
            <td>
                <span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>{{ CommonHelpers::getCriticalDaysDate($dataRow->created_at) }}</span> Days Remaining
            </td>
        </tr>
        @endforeach
    @endif
@overwrite
