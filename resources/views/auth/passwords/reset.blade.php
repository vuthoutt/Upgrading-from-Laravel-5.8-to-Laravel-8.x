@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" id="loginBox">
                <div class="card-header loginBox-header"><h3 id="login-header">Reset Password</h3></div>

                <div class="card-body">

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="id" value="{{ $id }}">

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-7 offset-md-0">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-7 offset-md-0">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>
                        @if( session('msg'))
                            <div class="alert alert-login-success" role="alert" style="text-align: center;">
                                <i class="fa fa-check"></i> &nbsp; &nbsp; {{ session('msg') }}
                            </div>
                        @endif
                        @if( session('err'))
                            <div class="alert alert-login-error" role="alert" style="text-align: center;">
                                <i class="fa fa-warning" style="font-size:16px;color:red"></i> &nbsp; &nbsp; {{ session('err') }}
                            </div>
                        @endif
                        <div class="form-group row mb-0">
                            <div class="col-md-5 offset-md-3">
                                <button type="submit" class="btn light_grey_gradient offset-left50">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
