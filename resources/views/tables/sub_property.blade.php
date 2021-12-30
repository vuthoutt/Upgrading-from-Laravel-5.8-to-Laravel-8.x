@extends('tables.main_table', [
        'header' => ['Property UPRN','Property Block','Property Name',]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td><a href="{{ route('property_detail',['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->property_reference ?? '' }}</a></td>
            <td><a href="{{ route('property_detail',['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->pblock ?? '' }}</a></td>
            <td><a href="{{ route('property_detail',['id' => $dataRow->id, 'section' => SECTION_DEFAULT]) }}">{{ $dataRow->name ?? '' }}</a></td>
        </tr>
        @endforeach
    @endif
@overwrite

