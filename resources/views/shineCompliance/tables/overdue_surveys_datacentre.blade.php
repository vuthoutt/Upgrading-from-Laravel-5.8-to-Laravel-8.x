@extends('shineCompliance.tables.main_table', [
        'header' => ["Survey Reference", "Project Reference", "Project Title", "Overdue Date", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if($dataRow->status ==  LOCKED_SURVEY_STATUS)
                <td>{{ $dataRow->reference }} <span class='orange_text'> (Locked)</span></td>
            @else
                <td><a href="{{ route('property.surveys', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT])  }}">{{ $dataRow->reference }}</a></td>
            @endif
            <td>{{ $dataRow->project_reference }}</td>
            <td><a href="{{ route('project.index', ['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_title }}</a></td>
            <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->due_date) }} </span>{{ CommonHelpers::convertTimeStampToTime($dataRow->due_date) }}</td>
            <td>
                @if(CommonHelpers::getDaysRemaninng($dataRow->due_date) > 0)
                    <span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>{{ CommonHelpers::getDaysRemaninng($dataRow->due_date) }}</span> Days Remaining
                @else
                    <span class="badge {{$risk_color}}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }} style="font-size: 85% !important">Overdue</span>
                @endif
            </td>
        </tr>
        @endforeach
    @endif
@overwrite
