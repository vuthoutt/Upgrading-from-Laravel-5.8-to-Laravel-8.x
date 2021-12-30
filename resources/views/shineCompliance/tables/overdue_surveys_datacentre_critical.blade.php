@extends('shineCompliance.tables.main_table', [
        'header' => ["Reference", "Type", "UPRN", "Block","Property Name", "Project", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(($dataRow->status ==  LOCKED_SURVEY_STATUS) and ($type != 'audit'))
                <td>{{ $dataRow->reference }} <span class='orange_text'> (Locked)</span></td>
            @else
                @if($type == 'assessment')
                    <td><a href="{{ route('shineCompliance.assessment.show', ['id' => $dataRow->id])  }}">{{ $dataRow->reference }}</a></td>
                @elseif($type == 'audit')
                    <td><a href="{{ route('au.details', ['id' => $dataRow->id])  }}">{{ $dataRow->reference }}</a></td>
                @else
                    <td><a href="{{ route('property.surveys', ['id' => $dataRow->id])  }}">{{ $dataRow->reference }}</a></td>
                @endif
            @endif
            <td>{{ ComplianceHelpers::getCriticalTypeText($type, $dataRow->type ?? 0) }}</td>
            <td>{{ $dataRow->uprn }}</td>
            <td>{{ $dataRow->pblock }}</td>
            <td><a href="{{ route('shineCompliance.property.property_detail', ['id' => $dataRow->prop_id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->prop_name }}</a></td>
            <td><a href="{{ route('project.index', ['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_title }}</a></td>
            <td>
                <span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>{{ CommonHelpers::getCriticalDaysRemaninng($dataRow->due_date) }}</span> Days Remaining
            </td>
        </tr>
        @endforeach
    @endif
@overwrite
