@extends('shineCompliance.layouts.app')
@section('content')
@if(\CommonHelpers::isSystemClient())
    @if(isset($type_view) and $type_view == 'department_users')
        @include('partials.nav', ['breadCrumb' => 'contractor-department-users','data' => $department])
    @else
            @include('partials.nav', ['breadCrumb' => 'contractor','data' => $client])
    @endif
@else
    @if(isset($type_view) and $type_view == 'department_users')
        @include('partials.nav', ['breadCrumb' => 'user_system_owner','data' => $department])
    @else
            @include('partials.nav', ['breadCrumb' => 'system_owner','data' => $client])
    @endif

@endif

<div class="container prism-content">
    <h3>{{ $client->name }}</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            @if(empty(\CompliancePrivilege::getContractorIdPermission()) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('my_organisation', ['client_id' => \Auth::user()->client_id]) }}"><strong>My Organisation</strong></a>
                </li>
            @endif
            @if(\CommonHelpers::isSystemClient())
{{--             <li class="nav-item">
                <a class="nav-link"  href="{{ route('client_list') }}"><strong>Clients</strong></a>
            </li> --}}
                <li class="nav-item active">
                    <a class="nav-link active" href="{{ route('contractor.clients') }}"><strong>Contractors</strong></a>
                </li>
            @else
                <li class="nav-item active">
                    <a class="nav-link active" href="{{ route('contractor', ['client_id' => 1]) }}"><strong>System Owner</strong></a>
                </li>
            @endif
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div  class="container tab-pane active">
                @if(isset($type_view) and $type_view == 'department_users')
                    @if($view_department)
                        @include('client.department_users', ['data' => $users, 'type' => CONTRACTOR_TYPE,'department_id' => $department_id, 'client_id' => $client->id, 'edit_department' => $edit_department ])
                    @endif
                @else
                    @include('client.my_organisation', ['data' => $client, 'type' => CONTRACTOR_TYPE, 'departments' => $departments, 'departments_contractor' => $departments_contractor])
                @endif
            </div>

        </div>
    </div>
</div>
</div>
@endsection
