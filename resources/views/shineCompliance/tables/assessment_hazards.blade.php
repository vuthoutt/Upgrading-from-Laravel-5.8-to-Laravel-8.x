@extends('shineCompliance.tables.main_table', [
        'header' => ['Summary','Reference','Hazard Type','Area/floor','Room/location Reference',$over_all_text, 'Confirmation']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>
                    <a href="{{ route('shineCompliance.assessment.get_hazard_detail', ['assess_id' => $dataRow->assess_id, 'id' => $dataRow->id]) }}">
                        {{ $dataRow->name ?? '' }}
                    </a>
                </td>
                <td>{{ $dataRow->reference ?? '' }}</td>
                <td>{{ $dataRow->hazardType->description ?? '' }}</td>
                <td>{{ $dataRow->area->title_presentation ?? 'N/A' }}</td>
                <td>{{ $dataRow->location->title_presentation ?? 'N/A' }}</td>
                <td style="width: 100px;">
                    <span  class="badge {{ \CommonHelpers::getTotalHazardText($dataRow->total_risk)['color'] }}" id="risk-color" style="width: 30px; color: {{\CommonHelpers::getTotalRiskHazardText($dataRow->total_risk)['color']}} !important;">
                        {{ $dataRow->total_risk ?? '' }}
                    </span>
                    &nbsp;
                    <span>{{ \CommonHelpers::getTotalHazardText($dataRow->total_risk)['risk'] }}</span>
                </td>
                <td>
                    @if($dataRow->is_temp == 1)
                        <button type="button" class="btn btn-success approval_survey" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->reference }}" data-type="approval" data-toggle="modal" data-target="#approval-survey" >Confirm</button>
                        &nbsp;
                        <button type="button" class="btn btn-danger approval_survey" data-survey-id="{{ $dataRow->id }}" data-survey-ref="{{ $dataRow->reference }}" data-type="reject" data-toggle="modal" data-target="#rejected-survey" >Dismiss</button>
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
@overwrite
