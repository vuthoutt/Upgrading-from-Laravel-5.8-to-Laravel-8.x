@extends('shineCompliance.layouts.app')
@section('content')

@include('partials.nav', ['breadCrumb' => 'all-client','data' => ''])

<div class="container prism-content">
    <h3></h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            @if(empty(\CompliancePrivilege::getContractorIdPermission()) and \CommonHelpers::isSystemClient())
            @else
            <li class="nav-item active">
                <a class="nav-link active" href="{{ route('my_organisation', ['client_id' => \Auth::user()->client_id]) }}"><strong>My Organisation</strong></a>
            </li>
            @endif
            @if(\CommonHelpers::isSystemClient())
            <li class="nav-item active">
                <a class="nav-link active"  href="{{ route('client_list') }}"><strong>Clients</strong></a>
            </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contractor.clients') }}"><strong>Contractors</strong></a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contractor',['client_id' => 1]) }}"><strong>System Owner</strong></a>
                </li>
            @endif
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            @include('tables.contractors', [
                'title' => 'Clients',
                'data' => $clients,
                'tableId' => 'all-client-table',
                'collapsed' => false,
                'plus_link' => true,
                'client_list' => true,
                'header' => ["Client", "Link"],
                'link' => route('client.get_add')
             ])

        </div>
    </div>
</div>
</div>
@endsection
