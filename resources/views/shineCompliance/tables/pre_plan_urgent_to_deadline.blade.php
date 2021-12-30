@extends('shineCompliance.tables.main_table', [
        'header' => ["UPRN", "Block", "Property Name", "Programme", "PPM Date", "Risk Warning"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>

        </tr>
        @endforeach
    @endif
@overwrite
