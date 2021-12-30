@extends('tables.main_table', [
        'header' => ["Property Name", "Building Number", "Property Group", "Postcode"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('property_detail',['id' => $dataRow->id,'section' => SECTION_DEFAULT] ) }}">{{ $dataRow->name }}</a></td>
            <td>{{ $dataRow->pblock }}</td>
            <td><a href="{{ route('zone.group', ['zone_id' => $dataRow->zone_id, 'client_id' => $dataRow->client_id]) }}">{{ $dataRow->zone->zone_name ?? '' }}</a></td>
            <td>{{ $dataRow->propertyInfo->postcode ?? '' }}</td>
        </tr>
        @endforeach
    @endif
@overwrite

