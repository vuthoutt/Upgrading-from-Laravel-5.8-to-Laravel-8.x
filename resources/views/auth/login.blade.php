@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8" style="text-align: center;">
                <div class="card" id="loginBox" style="width: 620px;border-radius: 0px">
                    <div class="card-header " style="background-color:#5a5a5a!important;border-radius: 0px">
                        <h5 style="float: left;color: white">{{ env('APP_DOMAIN') ?? 'COW' }} <strong>shine</strong>Compliance Gateway</h5></div>
                    <div class="col-md-12 row mb-0">
                        <div class="col-md-4" style="border-right: 1px solid #b3b3b3;margin: 15px 0px ">
                            <img src="{{ asset('img/westminster_logo.png') }}" alt="" style="margin: 55px 0px">
                        </div>
                        <div class="card-body col-md-8">
                            <div>

                            </div>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <p style="text-align: left;margin-left: 40px">Login</p>
                                <div class="form-group row">
                                    <div class="col-md-11 offset-md-1">
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
                                    <div class="col-md-11 offset-md-1">
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
                                    <div class="col-md-7 offset-md-1" style="display: flex;">
                                        <div class="form-check" style="margin-top: 8px">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{  \Cookie::get('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">
                                                {{ __('Remember Me') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="margin-right: 20px">
                                        <button type="submit" class="btn light_grey_gradient_button submit-login" style="border-radius:5px!important; ">
                                            {{ __('Sign in') }}
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
                                @if(env('SETTING_CREATE_ACCOUNT') == false)
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
                                                <div class="col-md-6 offset-md-3">
                                                    @if (Route::has('password.request'))
                                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                                            {{ __('Forgotten Password?') }}
                                                        </a>
                                                    @endif
                                                </div>
                                                {{--                            <div class="col-md-6">--}}
                                                {{--                                @if (Route::has('register'))--}}
                                                {{--                                <a class="btn btn-link" href="{{ route('register') }}">--}}
                                                {{--                                    {{ __('Create new account') }}--}}
                                                {{--                                </a>--}}
                                                {{--                                @endif--}}
                                                {{--                            </div>--}}
                                            </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        $(document).ready(function(){
            $('.submit-login').click(function () {
                localStorage.clear();
                localStorage.setItem('search-organisation', true);
                localStorage.setItem('search-group', true);
                localStorage.setItem('search-property', true);
                localStorage.setItem('search-project', true);
                localStorage.setItem('search-work', true);
            });
        });
    </script>
@endpush
