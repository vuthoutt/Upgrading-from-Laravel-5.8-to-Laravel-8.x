@extends('shineCompliance.layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8" style="text-align: center;">
            <div class="card" id="loginBox">
                <div class="card-header loginBox-header"><h3 id="login-header">{{ env('APP_DOMAIN') ?? 'GSK' }} <strong>shine</strong>Gateway</h3></div>

                <div class="card-body">
                    <form method="POST" action="{{ route('shineCompliance.post_login') }}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username"
                                value="{{ \Cookie::get('username') ?? '' }}" placeholder="Username"  autocomplete="email" autofocus>

                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password"
                                name="password"  autocomplete="current-password" value="{{ \Cookie::get('password') ?? '' }}">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3" style="display: flex;">
                                <div class="form-check" style="margin-top: 8px">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{  \Cookie::get('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                <button type="submit" class="btn light_grey_gradient offset-left50">
                                    {{ __('Sign in') }}
                                </button>
                            </div>
                        </div>
                        @if( session('msg'))
                            <div class="alert alert-login-success" role="alert">
                                <i class="fa fa-check"></i> &nbsp; &nbsp; {{ session('msg') }}zv
                            </div>
                        @endif
                        @if( session('err'))
                            <div class="alert alert-login-error" role="alert">
                                <i class="fa fa-warning" style="font-size:16px;color:red"></i> &nbsp; &nbsp; {{ session('err') }}
                            </div>
                        @endif
                        @if(env('SETTING_CREATE_ACCOUNT_SHINECOMPLIANCE') == false)
                            <div class="form-group row mb-0">
                            <div class="col-md-12">
                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgotten Password?') }}
                                </a>
                                @endif
                            </div>
                        @else
                        <div class="form-group row mb-0">
{{--                            <div class="col-md-6">--}}
{{--                                @if (Route::has('password.request'))--}}
{{--                                <a class="btn btn-link" href="{{ route('password.request') }}">--}}
{{--                                    {{ __('Forgotten Password?') }}--}}
{{--                                </a>--}}
{{--                                @endif--}}
{{--                            </div>--}}
                            <div class="col-md-6">
                                <a class="btn btn-link" href="">
                                    {{ __('Create new account') }}
                                </a>
                            </div>
                        </div>
                        @endif
                  </form>
              </div>
          </div>
      </div>
  </div>
</div>
@endsection
