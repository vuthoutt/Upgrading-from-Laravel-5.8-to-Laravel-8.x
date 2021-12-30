@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'shineCompliance-my-organisation', 'color' => 'red', 'data' => $client])
<div class="container-cus prism-content">
    <div class="row">
        <h3 class="title-row">{{ $client->name }}</h3>
    </div>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            <li class="nav-item active">
                <a class="nav-link active" href="{{ route('shineCompliance.my_organisation', ['client_id' => \Auth::user()->client_id]) }}"><strong>My Organisation</strong></a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div id="my-organisation" class="container tab-pane active">
                @if(isset($type_view) and $type_view == 'department_users')
                    @if($view_department)
                        @include('shineCompliance.client.department_users', ['data' => $users, 'type' => ORGANISATION_TYPE,'department_id' => $department_id, 'client_id' => $client->id])
                    @endif
                @else
                    @include('shineCompliance.client.my_organisation', ['data' => $client, 'type' => ORGANISATION_TYPE, 'departments' => $departments, 'departments_contractor' => $departments_contractor])
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
