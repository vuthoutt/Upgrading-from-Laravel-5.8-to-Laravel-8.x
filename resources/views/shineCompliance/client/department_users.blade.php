<div class="row">
@if (count($department->childrens) > 0)
@include('shineCompliance.tables.client_departments', [
    'title' => \CommonHelpers::getDepartmentRecursiveName($department),
    'tableId' => 'deparments',
    'collapsed' => false,
    'plus_link' => false,
    'data' =>  $department->childrens,
    'type' => $type,
    'client_id' => $client_id
    ])
@else
@include('shineCompliance.tables.department_users', [
    'title' => 'Personnel',
    'data' => $data,
    'tableId' => 'department-user-table',
    'collapsed' => false,
    'plus_link' => (\CommonHelpers::isSystemClient()) || ($type == ORGANISATION_TYPE) and ($edit_department == true) ? true : false,
    'link' => route('shineCompliance.contractor.get_add_user', ['client_id' => $client_id, 'department_id' => $department_id, 'type' => $type]),
 ])
 @endif
</div>
