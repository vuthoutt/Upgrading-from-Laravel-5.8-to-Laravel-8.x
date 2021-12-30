@extends('shineCompliance.layouts.app')
@section('content')
@if($type == CONTRACTOR_TYPE)
    @include('partials.nav', ['breadCrumb' => 'contractor-edit', 'data' => $client])
@else
    @include('partials.nav', ['breadCrumb' => 'my-organisation-edit', 'data' => $client])
@endif
<div class="container prism-content">
    <h3>Edit {{ $client->name }} Details </h3>
    <div class="main-content">
        <form method="POST" action="{{ route('my_organisation.post_edit',['id' => $client->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            @include('forms.form_input',['title' => 'Name:', 'data' => $client->name, 'name' => 'name', 'required' => true ])
            @include('forms.form_text',['title' => 'Shine Reference:', 'data' => $client->reference ])

            @if($type == CONTRACTOR_TYPE)
                @include('forms.form_text',['title' => 'Organisation Type:', 'data' => 'Contractor' ])
            @endif

            @include('forms.form_input',['title' => 'Address 1:', 'data' => $client->clientAddress->address1, 'name' => 'address1' ])
            @include('forms.form_input',['title' => 'Address 2:', 'data' => $client->clientAddress->address2, 'name' => 'address2' ])
            @include('forms.form_input',['title' => 'Town:', 'data' => $client->clientAddress->address3, 'name' => 'address3' ])
            @include('forms.form_input',['title' => 'City:', 'data' => $client->clientAddress->address4, 'name' => 'address4' ])
            @include('forms.form_input',['title' => 'County:', 'data' => $client->clientAddress->address5, 'name' => 'address5' ])
            @include('forms.form_input',['title' => 'Postcode:', 'data' => $client->clientAddress->postcode, 'name' => 'postcode' ])
            @include('forms.form_input',['title' => 'Country:', 'data' => $client->clientAddress->country, 'name' => 'country' ])

            @include('forms.form_upload',['title' => 'Logo:', 'name' => 'logo', 'object_id' => $client->id, 'folder' => CLIENT_LOGO ])

            @if($client->client_type == 1)
                @include('forms.form_upload',['title' => 'UKAS Logo:', 'name' => 'ukas', 'object_id' => $client->id, 'folder' => UKAS_IMAGE ])
                @include('forms.form_input',['title' => 'UKAS Reference:', 'data' => $client->clientInfo->ukas_reference, 'name' => 'ukas_reference' ])
            @endif
            <div class="col-md-12">
                <div class="row">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold" ></label>
                    <div class="col-md-5 form-input-text" >
                        <strong>General Enquiries</strong>
                    </div>
                </div>
            </div>

            @include('forms.form_dropdown',['title' => 'My Organisation Contact:', 'data' => $client->users, 'name' => 'key_contact', 'key'=> 'id', 'value'=>'full_name', 'compare_value'=> $client->key_contact ])
            @include('forms.form_input',['title' => 'Telephone:', 'data' => $client->clientAddress->telephone, 'name' => 'telephone' ])
            @include('forms.form_input',['title' => 'Mobile:', 'data' => $client->clientAddress->mobile, 'name' => 'mobile' ])
            @include('forms.form_input',['title' => 'Email:', 'data' => $client->email, 'name' => 'email' ])
            @if(!\CommonHelpers::isSystemClient() and ($type == ORGANISATION_TYPE))
            @else
                @if($client->client_type == 1)
                    @include('forms.form_input',['title' => 'Tender Notifications Email Address:', 'data' => $client->email_notification, 'name' => 'email_notification' ])
                @endif
             @endif

            @include('forms.form_input',['title' => 'Fax:', 'data' => $client->clientAddress->fax, 'name' => 'fax' ])

            @if($client->client_type == 0)
                @include('forms.form_input',['title' => 'Account Management Email:', 'data' => $client->clientInfo->account_management_email, 'name' => 'account_management_email' ])
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
                    {{-- Asbestos --}}
                    <div class="asbestos">
                        <strong>Asbestos</strong>
                    </div>
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Surveying:', 'data' => $client->clientInfo->type_surveying,'compare' => 1, 'name' => 'type_surveying' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Remediation/Removal:', 'data' => $client->clientInfo->type_removal,'compare' => 1, 'name' => 'type_removal' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Demolition:', 'data' => $client->clientInfo->type_demolition,'compare' => 1, 'name' => 'type_demolition' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Analytical:', 'data' => $client->clientInfo->type_analytical,'compare' => 1, 'name' => 'type_analytical' ])
                    {{-- Fire --}}
                    <div class="fire">
                        <strong>Fire</strong>
                    </div>
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Fire Equipment Assessment:', 'data' => $client->clientInfo->type_fire_equipment ,'compare' => 1, 'name' => 'type_fire_equipment' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Fire Risk Assessment:', 'data' => $client->clientInfo->type_fire_risk,'compare' => 1, 'name' => 'type_fire_risk' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Fire Remedial Assessment:', 'data' => $client->clientInfo->type_fire_remedial,'compare' => 1, 'name' => 'type_fire_remedial' ])
                    @include('shineCompliance.forms.form_checkbox',['title' => 'Independent Survey:', 'data' => $client->clientInfo->type_independent_survey,'compare' => 1, 'name' => 'type_independent_survey' ])

                    {{-- Water --}}
                    @if(env('WATER_MODULE'))
                        <div class="water">
                            <strong>Water</strong>
                        </div>
                        @include('shineCompliance.forms.form_checkbox',['title' => 'Legionella Risk Assessment:', 'data' => $client->clientInfo->type_legionella_risk,'compare' => 1, 'name' => 'type_legionella_risk' ])
                        @include('shineCompliance.forms.form_checkbox',['title' => 'Water Testing Assessment:', 'data' => $client->clientInfo->type_water_testing,'compare' => 1, 'name' => 'type_water_testing' ])
                        @include('shineCompliance.forms.form_checkbox',['title' => 'Water Remedial Assessment:', 'data' => $client->clientInfo->type_water_remedial,'compare' => 1, 'name' => 'type_water_remedial' ])
                        @include('shineCompliance.forms.form_checkbox',['title' => 'Temperature Assessment:', 'data' => $client->clientInfo->type_temperature,'compare' => 1, 'name' => 'type_temperature' ])
                    @endif
                   <div class="hs">
                       <strong>Health & Safety</strong>
                   </div>
                   @include('shineCompliance.forms.form_checkbox',['title' => 'Health & Safety Assessment:', 'data' => $client->clientInfo->type_hs_assessment ?? 0,'compare' => 1, 'name' => 'type_hs_assessment' ])
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
