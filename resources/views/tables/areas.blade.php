@extends('tables.main_table',[
        'header' => ['Reference','Description','Shine Reference','Details'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @php
            //$position = $position_survey = 0;
        @endphp
        @foreach($data as $key => $dataRow)
            <tr>
                <td>{{ $dataRow->area_reference }}</td>
                <td>{{ $dataRow->description }}</td>
                <td>{{ $dataRow->reference }}</td>
                @if($dataRow->survey_id == 0)
                    <td><a href="{{ route('property_detail',['id' => $dataRow->property_id,'section' => SECTION_AREA_FLOORS_SUMMARY,
                    'area' => $dataRow->id, 'position' => $key])}}">View</a></td>
                    @php
                       // $position_reg ++;
                    @endphp
                @else
                    <td><a href="{{ route('property.surveys',['survey_id' => $dataRow->survey_id,'section' => SECTION_AREA_FLOORS_SUMMARY,
                     'area' => $dataRow->id, 'position' => $key]) }}">View</a></td>
                    @php
                       // $position_survey ++;
                    @endphp
                @endif
            </tr>
        @endforeach
    @endif
@overwrite
