@extends('shineCompliance.tables.main_table', [
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @php
            $position = 0;
         @endphp
        @foreach($data as $key => $dataRow)
            <tr>
                @if(isset($property_head) and $property_head == true)
                <td>
                    <a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->property_id]) }}">
                        {{ $dataRow->property->name ?? '' }}
                    </a>
                </td>
                @endif
                <td>
                    <a href="{{ route('shineCompliance.item.detail', ['id' => $dataRow->id,'position' => $position,'category' => isset($type) ? $type : '']) }}">
                        {{ $dataRow->name }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('shineCompliance.property.area_detail',['property_id' => $dataRow->property_id, 'area_id' => $dataRow->area_id ])}}">
                        {{ $dataRow->area->area_reference ?? '' }}
                    </a>
                </td>
                <td>
                    <a href="{{ route('shineCompliance.location.detail',['location_id' => $dataRow->location_id]) }}">
                        {{ $dataRow->location->description ?? '' }}
                    </a>
                </td>
                <td>{{ optional($dataRow->productDebrisView)->product_debris }}</td>
                <td style="width: 100px;">
                    <span  class="badge {{ \CommonHelpers::getMasRiskColor($dataRow->total_mas_risk) }}" id="risk-color" style="width: 30px;">
                        &nbsp;
                        {{ $dataRow->total_mas_risk }}
                        &nbsp;
                    </span>
                    &nbsp;
                    <span>{{ \CommonHelpers::getMasRiskText($dataRow->total_mas_risk) }}</span>
                </td>
                <td style="display: none;">
                    {{ $dataRow->total_mas_risk }}
                </td>
            </tr>

            @php
                $position++;
            @endphp
        @endforeach
    @endif
@overwrite

