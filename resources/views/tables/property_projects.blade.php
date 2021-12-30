@extends('shineCompliance.tables.main_table', [
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if($dashboard)
                <td><a href="{{ route('property_detail', ['property_id' => $dataRow->property_id]) }}">{{ optional($dataRow->property)->property_reference }}</a></td>
                <td><a href="{{ route('property_detail', ['property_id' => $dataRow->property_id]) }}">{{ optional($dataRow->property)->name }}</a></td>
                <td><a href="{{ route('project.index', ['project_id' => $dataRow->id]) }}">{{ $dataRow->reference }}</a></td>
                <td>{{ $dataRow->title }}</td>
                <td>{{ $dataRow->project_type_text }}</td>
                <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->getOriginal('date')) }} </span>{{ $dataRow->date }}</td>
                <td><span class="d-none"> {{ CommonHelpers::convertTimeStamp($dataRow->getOriginal('due_date')) }} </span>{{ $dataRow->due_date }}</td>
            @else
                <td><a href="{{ route('project.index', ['project_id' => $dataRow->id]) }}">{{ $dataRow->reference }}</a></td>
                <td>{{ $dataRow->title }}</td>
                <td>{{ $dataRow->project_type_text }}</td>
                <td>{{ $dataRow->status_text }}</td>
                <td>{{ $dataRow->completed_date }}</td>
            @endif
        </tr>
        @endforeach
    @endif
@overwrite
