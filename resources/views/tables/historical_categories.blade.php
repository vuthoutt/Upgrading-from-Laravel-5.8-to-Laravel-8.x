@extends('tables.main_table', [
        'header' => ["Historical Categories"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            @if(isset($edit_permission) and $edit_permission == true)
            <td><a data-id="{{ $dataRow->id }}" data-title="{{ $dataRow->category }}" data-toggle="modal" href="#historic-cat-edit">{{ $dataRow->category }}</a></td>
            @else
            <td>{{ $dataRow->category }}</td>
            @endif
        </tr>
        @endforeach
    @endif
@overwrite

