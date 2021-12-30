@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" id="loginBox">
                <div class="card-header"><h3 id="login-header">Confirm new email</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('user.post-confirm-email') }}" class="form-shine">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="id" value="{{ $id }}">

                        <div class="form-group row">

                            <div class="col-md-6 offset-md-1">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Your Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-5 ">
                                <button type="submit" class="btn light_grey_gradient offset-left50">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                        @if(Session::get('msg'))
                            <div class="alert alert-danger" role="alert" style="text-align: center;">
                                {{ Session::get('msg') }}
                            </div>
                        @endif

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
