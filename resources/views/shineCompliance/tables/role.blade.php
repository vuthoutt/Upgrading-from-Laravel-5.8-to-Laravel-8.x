@extends('tables.main_table', [
        'header' => ["Name", "Action"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a data-id="{{ $dataRow->id }}" data-title="{{$dataRow->name}}" data-toggle="modal" href="#job-modal">{{ $dataRow->name }}</a></td>
            <td><a href="{{route('shineCompliance.get_job_role.compliance',['id' => $dataRow->id, 'type' => JOB_ROLE_GENERAL]) }}">View</a></td>
        </tr>
        @endforeach
    @endif
@overwrite

