@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" id="loginBox">
                <div class="card-header loginBox-header"><h3 id="login-header">shineGateway - Forgotten password</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">

                            <div class="col-md-7 offset-md-0">
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"  autocomplete="email" placeholder="Email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn light_grey_gradient offset-left50">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                        @if( session('msg'))
                            <div class="alert alert-login-success" role="alert">
                                <i class="fa fa-check"></i> &nbsp; &nbsp; {{ session('msg') }}
                            </div>
                        @endif
                        @if( session('err'))
                            <div class="alert alert-login-error" role="alert">
                                <i class="fa fa-warning" style="font-size:16px;color:red"></i> &nbsp; &nbsp; {{ session('err') }}
                            </div>
                        @endif
                        <div class="form-group row mb-0">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
