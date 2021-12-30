@extends('shineCompliance.layouts.app')

@section('content')
{{--    ['breadCrumb' => 'user-change-pass','data' => $userData]--}}
@include('shineCompliance.partials.nav', ['color' => 'red'])

<div class="container prism-content">
    <h3>Change Password</h3>
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('shineCompliance.user.post_change_password',['id' => $userData->id]) }}" class="form-shine">
                @csrf
                    <div class="row">
                        <div class="col-md-3 col-form-label text-md-left font-weight-bold">Password Rules:</div>
                        <div class="col-md-6 form-input-text" style="margin-left: -20px;">
                            <ul>
                                <li>New Password Length must be between {{ MIN_PASS_LENGTH }}
                                    &amp; {{ MAX_PASS_LENGTH }} Characters
                                </li>
                                <li>New Password must have at least one UPPER CASE Letter [A-Z]</li>
                                <li>New Password must have at least one Lower Case Letter [a-z]</li>
                                <li>New Password must have at least one Number [0-9]</li>
                                <li>New Password must not be the same as your last {{PREV_PASS_NOT_ALLOWED_NO}} Passwords</li>
                                <li>New Password must not be Blank</li>
                            </ul>
                        </div>
                    </div>
                    @include('shineCompliance.forms.form_text',['title' => 'Name:', 'data' => $userData->full_name ])
                    @include('shineCompliance.forms.form_text',['title' => 'Username:', 'data' => $userData->username ])
                    <div class="form-group row mt-3">
                        <label for="password" class="col-md-3 col-form-label text-md-left font-weight-bold">{{ __('Password:') }}<span style="color: red;">*</span></label>
                        <div class="col-md-5">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert" style="">
                                    <strong style="font-size: 105%;">{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-3 col-form-label text-md-left font-weight-bold">{{ __('Confirm Password:') }}<span style="color: red;">*</span></label>
                        <div class="col-md-5">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="col-md-6 offset-md-3 mt-3">
                        <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                            <strong>{{ __('Save') }}</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
