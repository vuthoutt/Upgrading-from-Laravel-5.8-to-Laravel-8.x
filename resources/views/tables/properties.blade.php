@extends('tables.main_table', [
        'header' => ["Property Name", "Block Reference", "Property UPRN", "Shine Reference", "Property Group", "Postcode"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('property_detail', ['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->name }}</a></td>
            <td>{{ $dataRow->pblock }}</td>
            <td>{{ $dataRow->property_reference }}</td>
            <td>{{ $dataRow->reference }}</td>
            <td><a href="{{ route('zone.group', ['zone_id' => $dataRow->zone_id, 'client_id' => $dataRow->client_id ?? 1]) }}">{{ $dataRow->zone->zone_name ?? '' }}</a></td>
            <td>{{ optional($dataRow->propertyInfo)->postcode }}</td>
        </tr>
        @endforeach
    @endif
@overwrite

