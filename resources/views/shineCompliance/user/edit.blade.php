@extends('shineCompliance.layouts.app')
@section('content')

@include('shineCompliance.partials.nav', ['breadCrumb' => 'user_edit_shineCompliance', 'color' => 'red', 'data' => $userData])
<div class="container prism-content">
    <h3>Edit {{ $userData->full_name }} Details </h3>
    <div class="main-content">
        <form method="POST" action="{{ route('shineCompliance.user.post-edit-profile',['id' => $userData->id]) }}" enctype="multipart/form-data" class="form-shine">
            @csrf
            <input type="hidden" name="client_type" id="client_type" value="{{ $userData->clients->client_type }}">
            @include('shineCompliance.forms.form_text',['title' => 'Shine Reference:', 'data' => $userData->shine_reference ])
            @include('shineCompliance.forms.form_input',['title' => 'Fore Name:', 'data' => $userData->first_name, 'name' => 'first-name' ])
            @include('shineCompliance.forms.form_input',['title' => 'Surname:', 'data' => $userData->last_name, 'name' => 'last-name' ])
            @include('shineCompliance.forms.form_upload',['title' => 'Avatar:', 'name' => 'avatar', 'object_id' => $userData->id, 'folder' => AVATAR ])
            @include('shineCompliance.forms.form_text',['title' => 'Organisation:', 'data' => $userData->clients->name ])
            <div class="row register-form">
                <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold" >Department:
                @if(isset($required))
                        <span style="color: red;">*</span>
                @endif
                </label>
                <div class="col-md-{{ isset($width) ? $width : 5 }}">
                    <div class="form-group">
                        @if(count($departments) > 0)
                            @include('shineCompliance.forms.form_dropdown_department_edit',['title' => 'Department:', 'data' => $departments, 'name' => '', 'key'=> 'id', 'value'=>'name' ])
                        @endif
                        @error('departments')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            </div>
            @include('shineCompliance.forms.form_input',['title' => 'Username:', 'data' => $userData->username, 'name' => 'username' ])
            @if(\Auth::user()->role == SUPER_USER_ROLE)
                @include('shineCompliance.forms.form_dropdown',['title' => 'Role:', 'data' => $roleSelect, 'name' => 'role', 'key'=> 'id', 'value'=>'name', 'compare_value'=> $userData->role, 'required' => true ])
            @else
                <input type="hidden" name="role" value="{{ $userData->role }}">
            @endif
            @include('shineCompliance.forms.form_input',['title' => 'Job Title:', 'data' => $userData->contact->job_title, 'name' => 'job-title' ])
            @include('shineCompliance.forms.form_input',['title' => 'Mobile:', 'data' => $userData->contact->mobile, 'name' => 'mobile' ])
            @include('shineCompliance.forms.form_input',['title' => 'Telephone:', 'data' => $userData->contact->telephone, 'name' => 'telephone' ])
            @include('shineCompliance.forms.form_input',['title' => 'Email:', 'data' => $userData->email, 'name' => 'email' ])
            <div class="row col-md-6 offset-md-3" style="color:red">
                <i>If you change email above, user need to confirm new email changes before use.</i>
            </div>

            @include('shineCompliance.forms.form_upload',['title' => 'Signature:', 'name' => 'signature', 'object_id' => $userData->id, 'folder' => USER_SIGNATURE ])
            {{-- @include('shineCompliance.forms.form_checkbox',['title' => 'Administrator:', 'data' => $userData->is_admin,'compare' => 1, 'name' => 'is-admin' ]) --}}
            {{-- @include('shineCompliance.forms.form_checkbox',['title' => 'Site Operative View:', 'data' => $userData->is_site_operative,'compare' => 1, 'name' => 'site-operative' ]) --}}
            <input type="hidden" name="site-operative" value="{{ $userData->is_site_operative }}">
            @include('shineCompliance.forms.form_datepicker',['title' => 'Last shineAsbestos Awareness Training:', 'data' => date("d/m/Y", $userData->last_asbestos_training) ??  date('d/m/Y'), 'name' => 'asbestos-awareness' ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Last shineAsbestos Training:', 'data' => date("d/m/Y", $userData->last_shine_asbestos_training) ??  date('d/m/Y'), 'name' => 'shine-asbestos' ])

            @include('shineCompliance.forms.form_input',['title' => 'Notes:', 'data' => $userData->notes, 'name' => 'notes' ])

            <div class="row register-form {{ isset($class_other) ? $class_other : '' }}">
                <label for="first-name" class="col-md-{{ isset($width_label) ? $width_label : 3 }}  col-form-label text-md-left font-weight-bold">
                    Housing Officer:
                </label>

                <div class="col-md-5 mt-1">
                    <label class="switch">
                        <input type="checkbox" name="housing_officer"
                               class="primary {{isset($class) ? $class : ''}}" value="1" {{ isset($userData->housing_officer) ? (($userData->housing_officer == 1) ? 'checked' : '') : ''}}>
                        <span class="slider round" ></span>
                    </label>
                </div>
            </div>

            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    <strong>{{ __('Save') }}</strong>
                </button>
            </div>
        </form>
    <div>
</div>
@endsection
