@extends('shineCompliance.tables.main_table', [
        'header' => ["Contractor", "Link"],
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->name }}</td>
            <td>
                <a href="{{ route('shineCompliance.contractor', ['client_id' => $dataRow->id]) }}">View</a>
            </td>
        </tr>
        @endforeach
    @endif
@overwrite

