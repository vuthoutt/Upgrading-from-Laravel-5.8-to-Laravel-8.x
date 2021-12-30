@extends('tables.main_table', [
        'header' => ["Document Name", "Document Type", "Last Revision", "View", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->name }}</td>
            <td>{{ $dataRow->type }}</td>
            <td></td>
            <td>{{ $dataRow->added }}</td>
            <td></td>
        </tr>
        @endforeach
    @endif
@overwrite
