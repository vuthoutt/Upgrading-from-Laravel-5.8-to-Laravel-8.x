@extends('shineCompliance.tables.main_table', [
        'header' => ["Reference", "Type", "UPRN", "Block", "PropertyName", "File", "Rejected Note"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    @endif

@overwrite

