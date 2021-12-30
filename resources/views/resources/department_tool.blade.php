@extends('shineCompliance.layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'department_management','data' => $department_current ?? []])

<div class="container prism-content">
    <h3 class="">
        Department Management
    </h3>
    <div class="main-content">
        <div class="row">
             @include('tables.department_management', [
            'title' => 'Departments',
            'tableId' => 'departments-table',
            'collapsed' => false,
            'count' => CommonHelpers::countAllUsersFromDepartment($departments ,\Auth::user()->client_id ),
            'plus_link' => \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_RESOURCES, JR_UPDATE_DEPARTMENT_LIST) ? true : false ,
            'modal_id' => 'departments-add',
            'data' => $departments,
            'edit_permission' => \CompliancePrivilege::checkUpdatePermission(JR_UPDATE_RESOURCES, JR_UPDATE_DEPARTMENT_LIST) ? true : false
            ])
            @include('modals.department_tool',['color' => 'red', 'modal_id' => 'departments-add', 'title' => 'Add Department','parent_id' => $parent_id, 'url' => route('ajax.depaertment_management'), 'type' => '-add','name' => 'Department Name'])
            @include('modals.department_tool',['color' => 'red', 'modal_id' => 'departments-edit', 'title' => 'Edit Department','parent_id' => $parent_id, 'url' => route('ajax.depaertment_management'), 'type' => '-edit','name' => 'Department Name'])
        </div>
    </div>
</div>
@endsection
@push('javascript')

@endpush
