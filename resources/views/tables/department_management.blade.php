@extends('tables.main_table', [
        'header' => ["Departments", "Link"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if($edit_permission)
            <td><a data-id="{{ $dataRow->id }}" data-title="{{ $dataRow->name }}" data-toggle="modal" href="#departments-edit">{{ $dataRow->name }}</a></td>
            @else
            <td>{{ $dataRow->name }}</td>
            @endif
            <td><a href="{{ route('department_list', ['parent_id' => $dataRow->id]) }}">view</a></td>
        </tr>
        @endforeach
    @endif
@overwrite

