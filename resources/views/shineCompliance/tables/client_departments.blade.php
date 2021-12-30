@extends('shineCompliance.tables.main_table', [
        'header' => ["Departments", "Link"],
        'row_col' => 'col-md-12'
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->name }}</td>
            <td>
                <a href="{{ route('shineCompliance.my_organisation.department_users', ['department_id' => $dataRow->id, 'type' => ORGANISATION_TYPE, 'client_id' => $client_id ]) }}">
                {{ CommonHelpers::countUsersFromDepartmentsRecursive($client_id, $dataRow) }}
                </a>
            </td>
        </tr>
        @endforeach
    @endif
@overwrite

