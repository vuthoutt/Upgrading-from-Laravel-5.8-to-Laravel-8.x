@extends('tables.main_table', [
        'header' => ["Name", "Action"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a data-id="{{ $dataRow->id }}" data-title="{{$dataRow->name}}" data-toggle="modal" href="#job-modal">{{ $dataRow->name }}</a></td>
            <td><a href="{{route('get_job_role',['job_role' => $dataRow->id]) }}">View</a></td>
        </tr>
        @endforeach
    @endif
@overwrite

