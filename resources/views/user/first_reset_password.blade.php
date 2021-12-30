@extends('layouts.app')

@section('content')
<div class="container-fluid red_gradient" style="position: fixed;z-index: 5">
    <nav class="navbar navbar-light bg-light red_gradient">
        <div class="container">
            <a href="#">
                <span class="navbar-brand mb-0 h1" style="color: white"><strong>shine</strong>Compliance</span>
            </a>
            <a href="/logout">
                <span class="navbar-brand mb-0" style="color: white">Sign out</span>
            </a>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                 <ul  class="navbar-nav ml-auto">
                    <!-- Dropdown -->
                      <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            Logged in as {!! Auth::user()->full_name !!}
                        </a>
                        <div class="dropdown-menu dropdown-menu-tip-nw">
                            <a class="dropdown-item" href="/logout">Sign out</a>
                        </div>
                    </li>
                 </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container prism-content">
    <h3>Reset Password On First Login</h3>
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" action="{{ route('user.post_change_password',['id' => $userData->id]) }}" class="form-shine">
                @csrf
                <input type="hidden" name="first_login" value="true">
                    <div class="row">
                        <div class="col-md-3 col-form-label text-md-left font-weight-bold">Password rules:</div>
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
                    @include('forms.form_text',['title' => 'Name:', 'data' => $userData->full_name ])
                    @include('forms.form_text',['title' => 'Username:', 'data' => $userData->username ])
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
                        <button type="submit" class="btn light_grey_gradient">
                            <strong>{{ __('Save') }}</strong>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
