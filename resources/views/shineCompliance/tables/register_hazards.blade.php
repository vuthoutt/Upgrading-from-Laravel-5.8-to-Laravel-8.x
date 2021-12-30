@extends('shineCompliance.tables.main_table', [
        'header' => $header ?? ['Summary','Reference','Hazard Type','Area/floor','Room/location Reference', 'FRA']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>
                    <a href="{{ route('shineCompliance.assessment.get_hazard_detail', ['id' => $dataRow->id]) }}">
                        {{ $dataRow->name ?? '' }}
                    </a>
                </td>
                <td>{{ $dataRow->reference ?? '' }}</td>
                <td>{{ $dataRow->hazardType->description ?? '' }}</td>
                <td>
                    <a href="{{ route('shineCompliance.property.area_detail',['property_id' => $dataRow->property_id, 'area_id' => $dataRow->area_id ])}}">
                        {{ $dataRow->area->title_presentation ?? '' }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('shineCompliance.location.detail',['location_id' => $dataRow->location_id]) }}">
                        {{ $dataRow->location->title_presentation ?? '' }}
                    </a>
                </td>
                <td style="width: 100px;">
                    <span  class="badge {{ \CommonHelpers::getTotalHazardText($dataRow->total_risk)['color'] }}" id="risk-color" style="width: 30px; color: {{\CommonHelpers::getTotalRiskHazardText($dataRow->total_risk)['color']}} !important;">
                        {{ $dataRow->total_risk ?? '' }}
                    </span>
                    &nbsp;
                    <span>{{ \CommonHelpers::getTotalHazardText($dataRow->total_risk)['risk'] }}</span>
                </td>
            </tr>
        @endforeach
    @endif
@overwrite
