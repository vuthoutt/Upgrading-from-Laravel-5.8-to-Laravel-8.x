@extends('tables.main_table', [
        'header' => ["Contractor", "Link"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->name }}</td>
            <td>
                @if(isset($client_list))
                    <a href="{{ route('client.detail', ['client_id' => $dataRow->id]) }}">View</a>
                @else
                    <a href="{{ route('contractor', ['client_id' => $dataRow->id]) }}">View</a>
                @endif
            </td>
        </tr>
        @endforeach
    @endif
@overwrite

