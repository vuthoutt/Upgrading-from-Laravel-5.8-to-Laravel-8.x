@extends('shineCompliance.tables.main_table',[
        'header' => ['Reference','Description','Room/locations','Items', 'Reason']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @php
            //$position_reg = $position_survey = 0;
        @endphp
        @foreach($data as $key => $dataRow)
            <tr>
                @if($dataRow->survey_id == 0)
                    <td><a href="{{ route('shineCompliance.property_detail',['id' => $dataRow->property_id,'section' => SECTION_AREA_FLOORS_SUMMARY, 'area' => $dataRow->id, 'position' => $key] ) }}">{{ $dataRow->area_reference }}</a></td>
                    @php
                        //$position_reg ++;
                    @endphp
                @else
                    <td><a href="{{ route('property.surveys',['survey_id' => $dataRow->survey_id,'section' => SECTION_AREA_FLOORS_SUMMARY, 'area' => $dataRow->id, 'position' => $key] ) }}">{{ $dataRow->area_reference }}</a></td>
                    @php
                        //$position_survey ++;
                    @endphp
                @endif
                <td>{{ $dataRow->description }}</td>
                <td>{{ $dataRow->allLocations->count() ?? '' }}</td>
                <td>{{ $dataRow->allItems->count() ?? '' }}</td>
                <td>{{ $dataRow->decommissionedReason->description ?? '' }}</td>
            </tr>
        @endforeach
    @endif
@overwrite
