@extends('shineCompliance.tables.main_table', [
        'header' => ['Reference','Programme Title','Previous Upload Date', 'Frequency', 'Risk']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>{{ $dataRow->reference ?? '' }}</td>
                <td>{{ $dataRow->name ?? '' }}</td>
                <td>{{ $dataRow->documentInspection->date ?? '' }}</td>
                <td>{{ $dataRow->inspection_period ?? '' }} Days</td>
                <td style="width: 100px;">
                    @if(isset($dataRow->documentInspection))
                        <span  class="badge {{ \CommonHelpers::getRiskProgrammeText($dataRow->days_remaining ?? '')['color'] }}" id="risk-color" style="width: 30px; color: {{\CommonHelpers::getRiskProgrammeText($dataRow->days_remaining ?? '')['color']}} !important;">
                            {{ $dataRow->days_remaining ?? '' }}
                        </span>
                        &nbsp;
                        <span>{{ \CommonHelpers::getRiskProgrammeText($dataRow->days_remaining ?? '')['risk'] }}</span>
                    @else
                        <span class="badge red" id="risk-color" style="margin-right: 10px">
                            Missing
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
@overwrite
