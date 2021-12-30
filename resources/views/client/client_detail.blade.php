@extends('layouts.app')
@section('content')
@if(isset($view) and $view == 'department_users')
    @include('partials.nav', ['breadCrumb' => 'client-department-users','data' => $department])
@else
    @include('partials.nav', ['breadCrumb' => 'client','data' => $data])
@endif
<div class="container prism-content">
    <h3>{{ $data->name }}</h3>
    <div class="main-content">
        <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            <li class="nav-item">
                <a class="nav-link " href="{{ route('my_organisation', ['client_id' => \Auth::user()->client_id]) }}"><strong>My Organisation</strong></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link active"  href="{{ route('client_list') }}"><strong>Clients</strong></a>
            </li>
            @if(\CommonHelpers::isSystemClient())
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
            @if(isset($view) and $view == 'department_users')
                 @if($view_department)
                     @include('client.department_users', ['data' => $users, 'type' => CLIENT_TYPE,'department_id' => $department_id, 'client_id' => $data->id])
                 @endif
            @else
                <div id="my-organisation" class="container tab-pane active">
                    <div class="row">
                       @if($view_detail)
                       <div class="col-md-12 client-image-show mt-3" >
                                <div class="col-md-5">
                                    <img class="image-signature" src="{{ CommonHelpers::getFile($data->id, CLIENT_LOGO) }}">
                                </div>
                                <div class="col-md-5 offset-top20" style="display: flex;">
                                    <a title="Download Asbestos Register Image" href="{{ route('retrive_image',['type'=>  CLIENT_LOGO ,'id'=> $data->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
                                </div>
                       </div>
                       @endif
                       <div class="row col-md-12 mt-5">
                                @if($view_detail)
                                    <div class="col-md-12">
                                        @include('forms.form_text',['title' => 'Name:', 'data' => $data->name ])
                                        @include('forms.form_text',['title' => 'Shine Reference:', 'data' => $data->reference ])
                                        @include('forms.form_text',['title' => 'Address 1:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address1 : ''])
                                        @include('forms.form_text',['title' => 'Address 2:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address2 : ''])
                                        @include('forms.form_text',['title' => 'Town:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address3 : ''])
                                        @include('forms.form_text',['title' => 'City:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address4 : ''])
                                        @include('forms.form_text',['title' => 'County:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->address5 : ''])
                                        @include('forms.form_text',['title' => 'Postcode:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->postcode : ''])
                                        @include('forms.form_text',['title' => 'Country:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->country : ''])
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-5 form-input-text offset-md-3" >
                                                <strong>General Enquiries</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @include('forms.form_text',['title' =>  'Client Contact:', 'link' => route('profile',['user' => $data->key_contact]),'data' => $data->mainUser->full_name ?? ''])
                                        @include('forms.form_text',['title' => 'Telephone:', 'data' => $data->clientAddress->telephone ?? ''])
                                        @include('forms.form_text',['title' => 'Mobile:', 'data' => $data->clientAddress->mobile ?? ''])
                                        @include('forms.form_text',['title' => 'Email:', 'data' =>  $data->email ?? ''])
                                        @include('forms.form_text',['title' => 'Fax:', 'data' => !is_null($data->clientAddress) ? $data->clientAddress->fax : ''])
                                    </div>
                                    <div class="col-md-4 col-form-label text-md-left">
                                            <a href="{{ route('client.get_edit',['client_id' => $data->id]) }}" style="text-decoration: none">
                                                @if($edit_detail)
                                                <button type="submit" class="btn light_grey_gradient ">
                                                    <strong>{{ __('Edit') }}</strong>
                                                </button>
                                                @endif
                                        </a>
                                    </div>
                                @endif

                                @if($view_traning_record)
                                    {{-- system owner --}}
                                        @include('tables.trainning_records', [
                                            'title' => 'Training Record',
                                            'tableId' => 'training-records',
                                            'collapsed' => false,
                                            'plus_link' => $edit_traning_record,
                                            'modal_id' => 'add-traning-record',
                                            'data' => $data->traningRecord,
                                            'edit_permission' => $edit_traning_record,
                                            ])
                                        @include('modals.training_record_add',['color' => 'red', 'modal_id' => 'add-traning-record','action' => 'edit', 'client_id' => $data->id, 'url' => route('ajax.training_record'), 'doc_type' => 'training'])
                                @endif

                                @if($view_policy)
                                    @include('tables.policies', [
                                        'title' => 'Policy Documents',
                                        'tableId' => 'policy-table',
                                        'collapsed' => true,
                                        'plus_link' => true,
                                        'edit_permission' => true,
                                        'modal_id' => 'add-policy',
                                        'data' => $data->policy
                                        ])
                                    @include('modals.training_record_add',['color' => 'red', 'modal_id' => 'add-policy','action' => 'edit', 'client_id' => $data->id, 'url' => route('ajax.training_record'), 'doc_type' => 'policy', 'title' => 'Add Policy Document'])
                                @endif

                                @if($view_department)
                                    @include('tables.client_departments', [
                                        'title' => 'Departments',
                                        'tableId' => 'deparments',
                                        'collapsed' => false,
                                        'plus_link' => false,
                                        'count' => CommonHelpers::countAllUsersFromDepartment($departments_contractor,$data->id ),
                                        'data' =>  $departments_contractor,
                                        'type' => CLIENT_TYPE,
                                        'client_id' => $data->id
                                        ])
                                @endif
                       </div>
                   </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@endsection
