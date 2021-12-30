@extends('shineCompliance.layouts.app')
@section('content')

@include('partials.nav', ['breadCrumb' => 'all-contractor','data' => ''])

<div class="container prism-content">
    <h3></h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            @if(empty(\CompliancePrivilege::getContractorIdPermission()) and \CommonHelpers::isSystemClient())
            @else
                <li class="nav-item ">
                    <a class="nav-link " href="{{ route('my_organisation', ['client_id' => \Auth::user()->client_id]) }}"><strong>My Organisation</strong></a>
                </li>
            @endif
{{--             <li class="nav-item">
                <a class="nav-link"  href="{{ route('client_list') }}"><strong>Clients</strong></a>
            </li> --}}
            <li class="nav-item active">
                <a class="nav-link active"  href="#"><strong>Contractors</strong></a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            @include('tables.contractors', [
                'title' => 'Contractors',
                'data' => $client_lists,
                'tableId' => 'all-contractor-table',
                'collapsed' => false,
                'plus_link' => true,
                'link' => route('contractor.get_add')
             ])

           {{--  @include('tables.trainning_records', [
                'title' => 'Training Records',
                'data' => $trainning_records,
                'tableId' => 'all-client-traning-record-table',
                'modal_id' => 'add-traning-record',
                'collapsed' => false,
                'plus_link' => true,
             ])
             @include('modals.training_record_add',['color' => 'red', 'modal_id' => 'add-traning-record','action' => 'edit', 'client_id' => ALL_CLIENT_TRAINING_ID, 'url' => route('ajax.training_record'), 'doc_type' => 'training']) --}}
        </div>
    </div>
</div>
</div>
@endsection
