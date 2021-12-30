@extends('shineCompliance.tables.main_table', [
        'header' => ['System & Equipment'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $key => $dataRow)
        <tr>
            <td>{{ $dataRow ?? '' }}</td>
        </tr>
    @endforeach
    @endif
@overwrite
