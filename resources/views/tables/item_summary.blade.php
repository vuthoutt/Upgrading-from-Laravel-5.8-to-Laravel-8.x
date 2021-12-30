@extends('tables.main_table', [
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
                    <a href="{{ route('property_detail', ['id' => $dataRow->property_id, 'section' => SECTION_DEFAULT]) }}">
                        {{ $dataRow->property->name ?? '' }}
                    </a>
                </td>
                @endif
                <td>
                    <a href="{{ route('item.index', ['id' => $dataRow->id,'position' => $position,'category' => isset($type) ? $type : '', 'pagination_type' => $pagination_type]) }}">
                        {{ $dataRow->name }}
                    </a>
                </td>
                @if($dataRow->survey_id == 0)
                    <td>
                        <a href="{{ route('property_detail',['id' => $dataRow->property_id,'section' => SECTION_AREA_FLOORS_SUMMARY,'area' => $dataRow->area_id])}}">
                            {{ $dataRow->area->area_reference ?? '' }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('property_detail',['id' => $dataRow->property_id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $dataRow->location_id] ) }}">
                            {{ $dataRow->location->location_reference ?? '' }}
                        </a>
                    </td>
                @else
                    <td>
                        <a href="{{ route('property.surveys',['survey_id' => $dataRow->survey_id,'section' => SECTION_AREA_FLOORS_SUMMARY,'area' => $dataRow->area_id]) }}">
                        {{ $dataRow->area->area_reference ?? '' }}
                        </a>
                    </td>
                     <td>
                        <a href="{{ route('property.surveys',['survey_id' => $dataRow->survey_id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $dataRow->location_id] ) }}">
                            {{ $dataRow->location->location_reference ?? '' }}
                        </a>
                    </td>
                @endif
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

