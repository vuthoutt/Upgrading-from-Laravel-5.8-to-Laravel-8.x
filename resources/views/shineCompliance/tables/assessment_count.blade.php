@extends('shineCompliance.tables.main_table', [
        'header' => ['Risk Warning','Record Count']
    ])
@section('datatable_content')
    <tr>
        <td>Hazard Risk Count</td>
        <td>{{$data->unDecommissionHazard->count() ?? 0}}</td>
    </tr>
    <tr>
        <td>Equipment Count</td>
        <td>{{$data->equipments->count() ?? 0}}</td>
    </tr>
    <tr>
        <td>Nonconformity Risk Count</td>
        <td>{{ $data->assessmentNonconformities->count() ?? 0 }}</td>
    </tr>
@overwrite
