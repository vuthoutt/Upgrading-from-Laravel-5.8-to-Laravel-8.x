@extends('shineCompliance.layouts.app')
@section('content')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('shineCompliance.my_organisation', ['client_id' => \Auth::user()->client_id]) }}"><strong>My Organisation</strong></a>
    </li>
    @if(\CommonHelpers::isSystemClient())
        @include('shineCompliance.partials.nav', ['breadCrumb' => 'shineCompliance_contractor','data' => $client,'color' => 'red'])
    @else
        @include('shineCompliance.partials.nav', ['breadCrumb' => 'shineCompliance_system_owner','data' => $client,'color' => 'red'])
    @endif

<div class="container-cus prism-content">
    <div class="row">
        <h3 class="title-row">{{ $client->name }}</h3>
    </div>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            @if(\CommonHelpers::isSystemClient())
                <li class="nav-item active">
                    <a class="nav-link active" href="{{ route('shineCompliance.contractor.clients') }}"><strong>Contractors</strong></a>
                </li>
            @else
                <li class="nav-item active">
                    <a class="nav-link active" href="{{ route('shineCompliance.contractor', ['client_id' => 1]) }}"><strong>System Owner</strong></a>
                </li>
            @endif
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div  class="container tab-pane active">
                @if(isset($type_view) and $type_view == 'department_users')
                    @if($view_department)
                        @include('shineCompliance.client.department_users', ['data' => $users, 'type' => CONTRACTOR_TYPE,'department_id' => $department_id, 'client_id' => $client->id, 'edit_department' => $edit_department ])
                    @endif
                @else
                    @include('shineCompliance.client.my_organisation', ['data' => $client, 'type' => CONTRACTOR_TYPE, 'departments' => $departments, 'departments_contractor' => $departments_contractor])
                @endif
            </div>

        </div>
    </div>
</div>
</div>
@endsection
