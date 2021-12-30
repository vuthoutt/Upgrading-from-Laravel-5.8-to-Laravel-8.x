@extends('shineCompliance.tables.main_table', [
        'header' => ['Summary','Reference','Area/floor Reference','Room/location Reference']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>
                    @if($dataRow instanceof \App\Models\ShineCompliance\FireExit)
                        <a href="{{ route('shineCompliance.assessment.get_fire_exit', ['fire_exit_id' => $dataRow->id]) }}">
                            {{ $dataRow->name ?? '' }}
                        </a>
                    @elseif($dataRow instanceof \App\Models\ShineCompliance\AssemblyPoint)
                        <a href="{{ route('shineCompliance.assessment.get_assembly_point', ['assembly_point_id' => $dataRow->id]) }}">
                            {{ $dataRow->name ?? '' }}
                        </a>
                    @elseif($dataRow instanceof \App\Models\ShineCompliance\VehicleParking)
                        <a href="{{ route('shineCompliance.assessment.get_vehicle_parking', ['vehicle_parking_id' => $dataRow->id]) }}">
                            {{ $dataRow->name ?? '' }}
                        </a>
                    @endif
                </td>
                <td>{{ $dataRow->reference ?? '' }}</td>
                <td>{{ $dataRow->area->title_presentation ?? 'N/A' }}</td>
                <td>{{ $dataRow->location->title_presentation ?? 'N/A' }}</td>
            </tr>
        @endforeach
    @endif
@overwrite
