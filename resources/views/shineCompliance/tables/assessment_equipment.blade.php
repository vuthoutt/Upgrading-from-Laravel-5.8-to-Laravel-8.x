@extends('shineCompliance.tables.main_table', [
        'header' => ['Summary','Reference', 'Equipment Type', 'Area/floor Reference', 'Room/location Reference'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $key => $dataRow)
        <tr>
            <td><a href="{{ route('shineCompliance.equipment.detail', ['id' => $dataRow->id ?? '']) }}">{{ $dataRow->name ?? '' }}</a></td>
            <td>{{ $dataRow->reference ?? '' }}</td>
            <td>{{ $dataRow->equipmentType->description ?? '' }}</td>
            <td>{{ $dataRow->area->area_reference ?? 'N/A' }}</td>
            <td>{{ $dataRow->location->location_reference ?? 'N/A' }}</td>
        </tr>
    @endforeach
    @endif
@overwrite
