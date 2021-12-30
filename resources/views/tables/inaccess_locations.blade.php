@extends('tables.main_table',[
        'header' => ['Property Name','Reference','Description','Accessibility','Items'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @php
         $position_reg = 0;// cause Collection->where() will not reorder keys of data
        @endphp
        @foreach($data as $key => $dataRow)
            <tr>
                <td><a href="{{ route('property_detail', ['id' => $dataRow->property_id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property->name ?? '' }}</a></td>
                @if($dataRow->survey_id == 0)
                    <td><a href="{{ route('property_detail',['id' => $dataRow->property_id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $dataRow->id,
                    'position' => $key, 'category' => isset($type) ? $type : '', 'pagination_type' => $pagination_type] ) }}">{{ $dataRow->location_reference }}</a></td>
                    @php
                       // $position_reg ++;
                    @endphp
                @else
                    <td><a href="{{ route('property.surveys',['survey_id' => $dataRow->survey_id,'section' => SECTION_ROOM_LOCATION_SUMMARY, 'location' => $dataRow->id,
                    'position' => $key, 'category' => isset($type) ? $type : '', 'pagination_type' => $pagination_type] ) }}">{{ $dataRow->location_reference }}</a></td>
                    @php
                        $position_reg ++;
                    @endphp
                @endif
                <td>{{ $dataRow->description }}</td>
                <td>{{ $dataRow->state_text }}</td>
                <td>{{ count($dataRow->allItems) }}</td>
            </tr>
        @endforeach
    @endif
@overwrite
