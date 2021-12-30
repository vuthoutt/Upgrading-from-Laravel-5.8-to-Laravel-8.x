@extends('layouts.app')
@section('content')

@include('partials.nav', ['breadCrumb' => 'client-add', 'data' => ''])

<div class="container prism-content">
    <h3>Add Client </h3>
    <div class="main-content">
        <form method="POST" action="{{ route('client.post_add') }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('forms.form_input',['title' => 'Name:', 'data' => '', 'name' => 'name', 'required' => true ])
            @include('forms.form_text',['title' => 'Organisation Type:', 'data' => 'Client' ])
            @include('forms.form_input',['title' => 'Address 1:', 'data' => '', 'name' => 'address1' ])
            @include('forms.form_input',['title' => 'Address 2:', 'data' => '', 'name' => 'address2' ])
            @include('forms.form_input',['title' => 'Town:', 'data' => '', 'name' => 'address3' ])
            @include('forms.form_input',['title' => 'City:', 'data' => '', 'name' => 'address4' ])
            @include('forms.form_input',['title' => 'County:', 'data' => '', 'name' => 'address5' ])
            @include('forms.form_input',['title' => 'Postcode:', 'data' => '', 'name' => 'postcode' ])
            @include('forms.form_input',['title' => 'Country:', 'data' => '', 'name' => 'country' ])
            @include('forms.form_upload',['title' => 'Logo:', 'name' => 'logo', 'folder' => CLIENT_LOGO ])

            <div class="col-md-12">
                <div class="row">
                    <label class="col-md-3 col-form-label text-md-left font-weight-bold" ></label>
                    <div class="col-md-5 form-input-text" >
                        <strong>General Enquiries</strong>
                    </div>
                </div>
            </div>

            @include('forms.form_input',['title' => 'Telephone:', 'data' => '', 'name' => 'telephone' ])
            @include('forms.form_input',['title' => 'Mobile:', 'data' => '', 'name' => 'mobile' ])
            @include('forms.form_input',['title' => 'Email Address:', 'data' => '', 'name' => 'email' ])
            @include('forms.form_input',['title' => 'Fax:', 'data' => '', 'name' => 'fax' ])
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    <div>
</div>
@endsection