@extends('shineCompliance.tables.main_table', [
        'header' => ["Name", "Access Level"],
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->full_name }}</td>
            <td><a href="{{ route('shineCompliance.profile-shineCompliance',['user' => $dataRow->id]) }}">{{ (($dataRow->clients->client_type == 1) ? "Basic User" : $dataRow->userRole->name )?? "Basic User"  }}</a></td>
        </tr>
        @endforeach
    @endif
@overwrite

