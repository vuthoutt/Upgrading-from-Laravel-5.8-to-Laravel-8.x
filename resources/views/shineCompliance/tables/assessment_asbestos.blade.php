@extends('shineCompliance.tables.main_table', [
        'header' => ['Summary', 'Area/floor Reference', 'Room/location Reference', 'Product/debris type', 'MAS']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
            <tr>
                <td>
                    <a href="{{ route('shineCompliance.item.detail', ['id' => $dataRow->id ?? '', 'section' => 'assessment-asbestos', 'assess_id' => $assess_id]) }}">
                        {{ $dataRow->name }}
                    </a>
                </td>
                <td>
                    {{ $dataRow->area->area_reference ?? '' }}
                </td>
                <td>
                    {{ $dataRow->location->location_reference ?? '' }}
                </td>
                <td>{{ optional($dataRow->productDebrisView)->product_debris }}</td>
                <td style="width: 100px;">
                    <span  class="badge {{ \CommonHelpers::getMasRiskColor($dataRow->total_mas_risk ?? 0) }}" id="risk-color" style="width: 30px;">
                        &nbsp;
                        {{ $dataRow->total_mas_risk ?? 0 }}
                        &nbsp;
                    </span>
                    &nbsp;
                    <span>{{ \CommonHelpers::getMasRiskText($dataRow->total_mas_risk ?? 0) }}</span>
                </td>
            </tr>
        @endforeach
    @endif
@overwrite

