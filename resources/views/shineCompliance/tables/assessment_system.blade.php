@extends('shineCompliance.tables.main_table', [
        'header' => ['Summary','Reference', 'System Type', 'Classification'],
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
    @foreach($data as $key => $dataRow)
        <tr>
            <td><a href="{{ route('shineCompliance.assessment.system_detail', ['id' => $dataRow->id ?? '']) }}">{{ $dataRow->name ?? ''}}</a></td>
            <td>{{ $dataRow->reference ?? '' }}</td>
            <td>{{ $dataRow->systemType->description ?? '' }}</td>
            <td>{{ $dataRow->systemClassification->description ?? '' }}</td>
        </tr>
    @endforeach
    @endif
@overwrite
