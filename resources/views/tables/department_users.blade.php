@extends('tables.main_table', [
        'header' => ["Name", "Access Level"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)

        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->full_name }}</td>
            <td><a href="{{ route('profile',['user' => $dataRow->id]) }}">{{ (($dataRow->clients->client_type == 1) ? "Basic User" : $dataRow->userRole->name ) ?? "Basic User"  }}</a></td>
        @endforeach
    @endif
@overwrite

