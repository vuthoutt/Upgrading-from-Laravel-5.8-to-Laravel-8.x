@extends('shineCompliance.tables.main_table', [
        'header' => ['Nonconformity Type','Reference','Summary','Reference','Equipment Type']
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
            <tr>
                <td>{{ $dataRow->type ?? '' }}</td>
                <td>{{ $dataRow->reference ?? '' }}</td>
                <td>{{ $dataRow->equipment->name ?? '' }}</td>
                <td>{{ $dataRow->equipment->reference ?? '' }}</td>
                <td>{{ $dataRow->equipment->equipmentType->description ?? '' }}</td>
            </tr>
        @endforeach
    @endif
@overwrite
