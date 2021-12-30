@extends('shineCompliance.tables.main_table', [
        'header' => ["Property Group Name", "Shine Reference", "Properties", "Link"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td><a href="#edit-zone-{{ $dataRow->id }}" data-toggle="modal">{{ $dataRow->zone_name }}</a></td>
                <td>{{ $dataRow->reference }}</td>
                <td>{{ $dataRow->prop_id }}</td>
                <td><a href="{{ route('shineCompliance.zone.details', ['zone_id' => $dataRow->id, 'client_id' => $client->id]) }}">View</a></td>
            </tr>

            @include('shineCompliance.modals.edit_zone',['color' => 'red', 'modal_id' => 'edit-zone-'.$dataRow->id,'action' => 'edit',
                    'url' => route('shineCompliance.zone.update_or_create'),'client_id' => $client->id,'unique_value' => $dataRow->id , 'zone' =>$dataRow])
        @endforeach
    @endif
@overwrite

