@extends('shineCompliance.layouts.app')
@section('content')
@if($type == CONTRACTOR_TYPE)
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'contractor-edit', 'color' => 'red', 'data' => $client])
@else
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'shineCompliance-my-organisation-edit', 'color' => 'red', 'data' => $client])
@endif
<div class="container-cus prism-content">
    <div class="row">
        <h3 class="title-row">Edit {{ $client->name }} Details </h3>
    </div>
    <div class="main-content">
        <form method="POST" action="{{ route('shineCompliance.my_organisation.post_edit',['id' => $client->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            @include('shineCompliance.forms.form_input',['title' => 'Name:', 'data' => $client->name, 'name' => 'name', 'required' => true ])
            @include('shineCompliance.forms.form_text',['title' => 'Shine Reference:', 'data' => $client->reference ])

            @if($type == CONTRACTOR_TYPE)
                @include('shineCompliance.forms.form_text',['title' => 'Organisation Type:', 'data' => 'Contractor' ])
            @endif

            @include('shineCompliance.forms.form_input',['title' => 'Address 1:', 'data' => $client->clientAddress->address1, 'name' => 'address1' ])
            @include('shineCompliance.forms.form_input',['title' => 'Address 2:', 'data' => $client->clientAddress->address2, 'name' => 'address2' ])
            @include('shineCompliance.forms.form_input',['title' => 'Town:', 'data' => $client->clientAddress->address3, 'name' => 'address3' ])
            @include('shineCompliance.forms.form_input',['title' => 'City:', 'data' => $client->clientAddress->address4, 'name' => 'address4' ])
            @include('shineCompliance.forms.form_input',['title' => 'County:', 'data' => $client->clientAddress->address5, 'name' => 'address5' ])
            @include('shineCompliance.forms.form_input',['title' => 'Postcode:', 'data' => $client->clientAddress->postcode, 'name' => 'postcode' ])
            @include('shineCompliance.forms.form_input',['title' => 'Country:', 'data' => $client->clientAddress->country, 'name' => 'country' ])

            @include('shineCompliance.forms.form_upload',['title' => 'Logo:', 'name' => 'logo', 'object_id' => $client->id, 'folder' => CLIENT_LOGO ])

            @if($client->client_type == 1)
                @include('shineCompliance.forms.form_upload',['title' => 'UKAS Logo:', 'name' => 'ukas', 'object_id' => $client->id, 'folder' => UKAS_IMAGE ])
                @include('shineCompliance.forms.form_input',['title' => 'UKAS Reference:', 'data' => $client->clientInfo->ukas_reference, 'name' => 'ukas_reference' ])
                @include('shineCompliance.forms.form_input',['title' => 'Removal Licence Reference:', 'data' => $client->clientInfo->removal_licence_reference ?? '', 'name' => 'removal_licence_reference' ])
            @endif
            <div class="col-md-12">
                <div class="row">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold" ></label>
                    <div class="col-md-5 form-input-text" >
                        <strong>General Enquiries</strong>
                    </div>
                </div>
            </div>

            @include('shineCompliance.forms.form_dropdown',['title' => 'My Organisation Contact:', 'data' => $client->users, 'name' => 'key_contact', 'key'=> 'id', 'value'=>'full_name', 'compare_value'=> $client->key_contact ])
            @include('shineCompliance.forms.form_input',['title' => 'Telephone:', 'data' => $client->clientAddress->telephone, 'name' => 'telephone' ])
            @include('shineCompliance.forms.form_input',['title' => 'Mobile:', 'data' => $client->clientAddress->mobile, 'name' => 'mobile' ])
            @include('shineCompliance.forms.form_input',['title' => 'Email:', 'data' => $client->email, 'name' => 'email' ])
            @if(!\CommonHelpers::isSystemClient() and ($type == ORGANISATION_TYPE))
            @else
                @if($client->client_type == 1)
                    @include('shineCompliance.forms.form_input',['title' => 'Tender Notifications Email Address:', 'data' => $client->email_notification, 'name' => 'email_notification' ])
                @endif
             @endif

            @include('shineCompliance.forms.form_input',['title' => 'Fax:', 'data' => $client->clientAddress->fax, 'name' => 'fax' ])
            @if(!\CommonHelpers::isSystemClient() and ($type == ORGANISATION_TYPE))
            @else
                @if($client->client_type == 1)
                    @include('shineCompliance.forms.form_dropdown',['title' => 'Contractor Setup:', 'data' => $contractor_setup, 'name' => 'contractor_setup_id', 'key'=> 'id', 'value'=>'description', 'compare_value'=> $client->clientInfo->contractor_setup_id ?? 0 ])
                @endif
            @endif

            @if($client->client_type == 0)
                @include('shineCompliance.forms.form_input',['title' => 'Account Management Email:', 'data' => $client->clientInfo->account_management_email, 'name' => 'account_management_email' ])
            @endif


            @if(!\CommonHelpers::isSystemClient() and ($type == ORGANISATION_TYPE))
            @else
                @if($client->client_type == 1)
                    <div class="col-md-12">
                        <div class="row">
                            <label class="col-md-3 col-form-label text-md-left font-weight-bold" ></label>
                            <div class="col-md-5 form-input-text" >
                                <strong>Contractor Type (Tick all that apply)</strong>
                            </div>
                        </div>
                    </div>
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Surveying:', 'data' => $client->clientInfo->type_surveying,'compare' => 1, 'name' => 'type_surveying' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Remediation/Removal:', 'data' => $client->clientInfo->type_removal,'compare' => 1, 'name' => 'type_removal' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Demolition:', 'data' => $client->clientInfo->type_demolition,'compare' => 1, 'name' => 'type_demolition' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Analytical:', 'data' => $client->clientInfo->type_analytical,'compare' => 1, 'name' => 'type_analytical' ])
                @endif
            @endif
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    <div>
</div>
@endsection
