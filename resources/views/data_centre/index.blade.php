@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'data_centre','data' => $route->title])

<div class="container prism-content">
    <h3>Data Centre</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
{{--             <li class="nav-item {{ $route->name == 'data_centre.my_notifications' ? 'active' : '' }}">
                <a class="nav-link {{ $route->name == 'data_centre.my_notifications' ? 'active' : '' }}"  href="{{ route('data_centre.my_notifications') }}"><strong>My Notifications</strong></a>
            </li> --}}
            @if(!\CompliancePrivilege::checkPermission(SURVEYS_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.surveys' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.surveys' ? 'active' : '' }}" href="{{ route('data_centre.surveys') }}"><strong>Surveys</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(PROJECTS_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.projects' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.projects' ? 'active' : '' }}" href="{{ route('data_centre.projects') }}"><strong>Projects</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(CRITICAL_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.critical' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.critical' ? 'active' : '' }}" href="{{ route('data_centre.critical') }}"><strong>Critical</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(URGENT_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.urgent' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.urgent' ? 'active' : '' }}" href="{{ route('data_centre.urgent') }}"><strong>Urgent</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(IMPORTANT_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.important' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.important' ? 'active' : '' }}" href="{{ route('data_centre.important') }}"><strong>Important</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(ATTENTION_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.attention' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.attention' ? 'active' : '' }}" href="{{ route('data_centre.attention') }}"><strong>Attention</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(DEADLINE_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.deadline' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.deadline' ? 'active' : '' }}" href="{{ route('data_centre.deadline') }}"><strong>Deadline</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(APPROVAL_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.aprroval' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.aprroval' ? 'active' : '' }}" href="{{ route('data_centre.aprroval') }}"><strong>Approval</strong></a>
                </li>
            @endif

            @if(!\CompliancePrivilege::checkPermission(REJECTED_VIEW_PRIV) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item {{ $route->name == 'data_centre.rejected' ? 'active' : '' }}">
                    <a class="nav-link {{ $route->name == 'data_centre.rejected' ? 'active' : '' }}" href="{{ route('data_centre.rejected') }}"><strong>Rejected</strong></a>
                </li>
            @endif
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
                @yield('data_centre_content')
        </div>

    </div>
</div>
@endsection
