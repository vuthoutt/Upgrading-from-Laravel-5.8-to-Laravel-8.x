@extends('shineCompliance.layouts.app')
@section('content')
    @include('partials.nav', ['breadCrumb' => 'incident_reports'])

    <div class="container prism-content">
        <h3 class="">
            Incident Reporting
        </h3>
        <div class="main-content mar-up">
            <div class="main-content">
                <!-- Nav tabs -->
                <input type="hidden" id="active_tab" value="">
                <ul class="nav nav-pills red_gradient_nav" id="myTab">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#live" title="Live"><strong>Live</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#completed" title="Completed"><strong>Completed</strong></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#decommissioned" title="Decommissioned"><strong>Decommissioned</strong></a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div id="live" class="container tab-pane active">
                        @include('shineCompliance.resources.incident_reporting.live', ['data' => $liveIncidents])
                    </div>

                    <div id="completed" class="container tab-pane fade">
                        @include('shineCompliance.resources.incident_reporting.complete', ['data' => $completedIncidents])
                    </div>

                    <div id="decommissioned" class="container tab-pane fade">
                        @include('shineCompliance.resources.incident_reporting.decommissioned', ['data' => $decommissionedIncidents])
                    </div>
                </div>
            </div>
        </div>
        {{--    <div class="main-content">--}}
        {{--        <div class="row">--}}
        {{--             @include('tables.department_management', [--}}
        {{--            'title' => 'Departments',--}}
        {{--            'tableId' => 'departments-table',--}}
        {{--            'collapsed' => false,--}}
        {{--            'count' => CommonHelpers::countAllUsersFromDepartment($departments ,\Auth::user()->client_id ),--}}
        {{--            'plus_link' => true,--}}
        {{--            'modal_id' => 'departments-add',--}}
        {{--            'data' => $departments,--}}
        {{--            'edit_permission' => true--}}
        {{--            ])--}}
        {{--            @include('modals.department_tool',['color' => 'red', 'modal_id' => 'departments-add', 'title' => 'Add Department','parent_id' => $parent_id, 'url' => route('ajax.depaertment_management'), 'type' => '-add','name' => 'Department Name'])--}}
        {{--            @include('modals.department_tool',['color' => 'red', 'modal_id' => 'departments-edit', 'title' => 'Edit Department','parent_id' => $parent_id, 'url' => route('ajax.depaertment_management'), 'type' => '-edit','name' => 'Department Name'])--}}
        {{--        </div>--}}
        {{--    </div>--}}
    </div>
@endsection
@push('javascript')

@endpush
