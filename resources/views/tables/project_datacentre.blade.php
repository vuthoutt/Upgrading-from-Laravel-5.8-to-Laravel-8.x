@extends('tables.main_table', [
        'header' => [  "Project Reference","Project Title","Property Name", "Company Name","Status","Risk Warning" ]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('project.index', ['project_id' => $dataRow->id]) }}">{{ $dataRow->reference }}</a></td>
            <td><a href="{{ route('project.index', ['project_id' => $dataRow->id]) }}">{{ $dataRow->title }}</a></td>
            <td><a href="{{ route('property_detail', ['property_id' =>$dataRow->property_id,'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property->name ?? '' }}</a></td>
             <td>{{ $dataRow->checked_contractor_name ?? ''}}</td>
            <td style="width: 200px;">{{ $dataRow->status_text }}</td>
            <td style="width: 210px;">
                <span class="badge {{ $dataRow->risk_color }}" {{ ($dataRow->risk_color == 'yellow') ? "style=\"color:#000\";" : '' }}>
                    {{ $dataRow->project_days_remain }}
                </span> Days Remaining
            </td>
        </tr>
        @endforeach
    @endif
@overwrite
