@extends('tables.main_table', [
        'header' => ["Project Reference", "Project Reference", "Project Title", "Document Type", "Date", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>

            <td><a href="{{ route('project.index', ['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_reference }}</a></td>
            <td>{{ $dataRow->project_reference }}</td>
            <td><a href="{{ route('project.index', ['project_id' => $dataRow->project_id]) }}">{{ $dataRow->project_title }}</a></td>
            <td>{{ $dataRow->doc_type }}</td>
            <td>{{ CommonHelpers::convertTimeStampToTime($dataRow->deadline, 'N/A') }}</td>
            <td>
                @if(!isset($dataRow->deadline))
                    <span class="badge red_gradient"> QA Required </span>
                @else
                    @if(CommonHelpers::getDaysRemaninng($dataRow->deadline) > 0)
                        <span class="badge {{ $risk_color }}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }}>{{ CommonHelpers::getDaysRemaninng($dataRow->deadline) }}</span> Days Remaining
                    @else
                        <span class="badge {{$risk_color}}" {{ ($risk_color == 'yellow_gradient') ? "style=color:#000!important" : "" }} style="font-size: 85% !important">Overdue</span>
                    @endif
                @endif
            </td>
        </tr>
        @endforeach
    @endif
@overwrite

