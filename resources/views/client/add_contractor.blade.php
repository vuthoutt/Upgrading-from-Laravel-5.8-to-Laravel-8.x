@extends('shineCompliance.layouts.app')
@section('content')

@include('partials.nav', ['breadCrumb' => 'contractor-add', 'data' => ''])

<div class="container prism-content">
    <h3>Add Contractor </h3>
    <div class="main-content">
        <form method="POST" action="{{ route('contractor.post_add') }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            @include('forms.form_input',['title' => 'Name:', 'data' => '', 'name' => 'name', 'required' => true ])
            @include('forms.form_text',['title' => 'Organisation Type:', 'data' => 'Contractor' ])
            @include('forms.form_input',['title' => 'Address 1:', 'data' => '', 'name' => 'address1' ])
            @include('forms.form_input',['title' => 'Address 2:', 'data' => '', 'name' => 'address2' ])
            @include('forms.form_input',['title' => 'Town:', 'data' => '', 'name' => 'address3' ])
            @include('forms.form_input',['title' => 'City:', 'data' => '', 'name' => 'address4' ])
            @include('forms.form_input',['title' => 'County:', 'data' => '', 'name' => 'address5' ])
            @include('forms.form_input',['title' => 'Postcode:', 'data' => '', 'name' => 'postcode' ])
            @include('forms.form_input',['title' => 'Country:', 'data' => '', 'name' => 'country' ])
            @include('forms.form_upload',['title' => 'Logo:', 'name' => 'logo', 'folder' => CLIENT_LOGO ])
            @include('forms.form_upload',['title' => 'UKAS Logo:', 'name' => 'ukas', 'folder' => UKAS_IMAGE ])
            @include('forms.form_input',['title' => 'UKAS Reference:', 'data' => '', 'name' => 'ukas_reference' ])

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
            @include('forms.form_input',['title' => 'Tender Notifications Email Address:', 'data' => '', 'name' => 'email_notification' ])
            @include('forms.form_input',['title' => 'Fax:', 'data' => '', 'name' => 'fax' ])

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
            @include('shineCompliance.forms.form_checkbox',['title' => 'Surveying:', 'data' => '','compare' => 1, 'name' => 'type_surveying' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Remediation/Removal:', 'data' => '','compare' => 1, 'name' => 'type_removal' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Demolition:', 'data' => '','compare' => 1, 'name' => 'type_demolition' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Analytical:', 'data' => '','compare' => 1, 'name' => 'type_analytical' ])

            {{-- Fire --}}
            <div class="fire">
                <strong>Fire</strong>
            </div>
            @include('shineCompliance.forms.form_checkbox',['title' => 'Fire Equipment Assessment:', 'data' => '','compare' => 1, 'name' => 'type_fire_equipment' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Fire Risk Assessment:', 'data' => '','compare' => 1, 'name' => 'type_fire_risk' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Fire Remedial Assessment:', 'data' => '','compare' => 1, 'name' => 'type_fire_remedial' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Independent Survey:', 'data' => '','compare' => 1, 'name' => 'type_independent_survey' ])

            {{-- Water --}}
            @if(env('WATER_MODULE'))
            <div class="water">
                <strong>Water</strong>
            </div>
            @include('shineCompliance.forms.form_checkbox',['title' => 'Legionella Risk Assessment:', 'data' => '','compare' => 1, 'name' => 'type_legionella_risk' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Water Testing Assessment:', 'data' => '','compare' => 1, 'name' => 'type_water_testing' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Water Remedial Assessment:', 'data' => '','compare' => 1, 'name' => 'type_water_remedial' ])
            @include('shineCompliance.forms.form_checkbox',['title' => 'Temperature Assessment:', 'data' => '','compare' => 1, 'name' => 'type_temperature' ])
            @endif
            <div class="water">
                <strong>Health and Safety</strong>
            </div>
            @include('shineCompliance.forms.form_checkbox',['title' => 'Health and Safety Assessment Project:', 'data' => '','compare' => 1, 'name' => 'type_hs_assessment' ])
            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    <div>
</div>
@endsection
