@extends('tables.main_table', [
        'header' => ["Property Group Name", "Shine Reference", "Properties", "Link"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->zone_name }}</td>
            <td>{{ $dataRow->reference }}</td>
            <td>{{ $dataRow->prop_id }}</td>
            <td><a href="{{ route('zone.group', ['zone_id' => $dataRow->id, 'client_id' => $client_id]) }}">View</a></td>
        </tr>
        @endforeach
    @endif
@overwrite

