@extends('shineCompliance.tables.main_table', [
        'header' => $header,
        'row_col' => "col-md-12"
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $key => $dataRow)
            <tr>
                <td>
                    <a href="{{ route('shineCompliance.item.detail', ['id' => $dataRow->id,'position' => $key,'category' => isset($type) ? $type : '', 'pagination_type' => $pagination_type]) }}">
                        {{ $dataRow->name ?? '' }}
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
                <td>{{ $dataRow->decommissionedReason->description ?? '' }}</td>

                @if(!isset($summary))
                    <td style="width: 350px;">{{ optional($dataRow->itemInfo)->comment }}</td>
                @endif
            </tr>
        @endforeach
    @endif
@overwrite

