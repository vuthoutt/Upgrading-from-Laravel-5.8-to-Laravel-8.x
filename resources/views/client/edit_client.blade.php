@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'client-edit', 'data' => $client])
<div class="container prism-content">
    <h3>Edit {{ $client->name }} Details </h3>
    <div class="main-content">
        <form method="POST" action="{{ route('client.post_edit',['id' => $client->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="type" value="{{ CLIENT_TYPE }}">
            @include('forms.form_input',['title' => 'Name:', 'data' => $client->name, 'name' => 'name', 'required' => true ])
            @include('forms.form_text',['title' => 'Shine Reference:', 'data' => $client->reference ])

            @include('forms.form_text',['title' => 'Organisation Type:', 'data' => 'Client' ])

            @include('forms.form_input',['title' => 'Address 1:', 'data' => $client->clientAddress->address1, 'name' => 'address1' ])
            @include('forms.form_input',['title' => 'Address 2:', 'data' => $client->clientAddress->address2, 'name' => 'address2' ])
            @include('forms.form_input',['title' => 'Town:', 'data' => $client->clientAddress->address3, 'name' => 'address3' ])
            @include('forms.form_input',['title' => 'City:', 'data' => $client->clientAddress->address4, 'name' => 'address4' ])
            @include('forms.form_input',['title' => 'County:', 'data' => $client->clientAddress->address5, 'name' => 'address5' ])
            @include('forms.form_input',['title' => 'Postcode:', 'data' => $client->clientAddress->postcode, 'name' => 'postcode' ])
            @include('forms.form_input',['title' => 'Country:', 'data' => $client->clientAddress->country, 'name' => 'country' ])

            @include('forms.form_upload',['title' => 'Logo:', 'name' => 'logo', 'object_id' => $client->id, 'folder' => CLIENT_LOGO ])
            <div class="col-md-12">
                <div class="row">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold" ></label>
                    <div class="col-md-5 form-input-text" >
                        <strong>General Enquiries</strong>
                    </div>
                </div>
            </div>

            {{-- @include('forms.form_dropdown',['title' => 'My Organisation Contact:', 'data' => $client->users, 'name' => 'key_contact', 'key'=> 'id', 'value'=>'full_name', 'compare_value'=> $client->key_contact ]) --}}
            @include('forms.form_input',['title' => 'Telephone:', 'data' => $client->clientAddress->telephone, 'name' => 'telephone' ])
            @include('forms.form_input',['title' => 'Mobile:', 'data' => $client->clientAddress->mobile, 'name' => 'mobile' ])
            @include('forms.form_input',['title' => 'Email:', 'data' => $client->email, 'name' => 'email' ])
            @include('forms.form_input',['title' => 'Fax:', 'data' => $client->clientAddress->fax, 'name' => 'fax' ])
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    <div>
</div>
@endsection