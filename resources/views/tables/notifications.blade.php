@extends('tables.main_table', [
        'header' => $header
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td></td>
            <td></a></td>
        </tr>
        @endforeach
    @endif
@overwrite

