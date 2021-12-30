@extends('tables.main_table', [
        'header' => ["Departments", "Link"]
    ])
@section('datatable_content')
    @if(isset($data) and count($data) > 0)
        @foreach($data as $dataRow)
        <tr>
            <td>{{ $dataRow->name }}</td>
            <td>
                @if($type == CONTRACTOR_TYPE)
                    <a href="{{ route('contractor.department_users', ['department_id' => $dataRow->id, 'type' => CONTRACTOR_TYPE, 'client_id' => $client_id ]) }}">
                @elseif($type == CLIENT_TYPE)
                    <a href="{{ route('client.department_users', ['department_id' => $dataRow->id, 'type' => CLIENT_TYPE, 'client_id' => $client_id ]) }}">
                @else
                    <a href="{{ route('my_organisation.department_users', ['department_id' => $dataRow->id, 'type' => ORGANISATION_TYPE, 'client_id' => $client_id ]) }}">
                @endif
                    {{-- @if($dataRow->id == 1057) --}}
                    {{ CommonHelpers::countUsersFromDepartmentsRecursive($client_id, $dataRow) }}
                    {{-- @endif --}}
                </a>
            </td>
        </tr>
        @endforeach
    @endif
@overwrite

