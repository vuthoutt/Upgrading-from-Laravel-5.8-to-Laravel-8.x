@extends('layouts.app')

@section('content')
<div class="container-fluid red_gradient" style="position: fixed;z-index: 5;margin-top: -72px;">
    <nav class="navbar navbar-light bg-light red_gradient">
        <div class="container">
            <a href="{{ route('home') }}">
                <span class="navbar-brand mb-0 h1" style="color: white"><strong>shine</strong>Asbetos</span>
            </a>
        </div>
    </nav>
</div>
<div class="container" style="font-size: 14px!important">
    <div style="margin-top: 70px;margin-bottom: 50px;padding-top: 50px;">
        <h2>Create a New Account</h2>
    </div>

    <div>
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
        <form method="POST" action="{{ route('post_register') }}" class="form-shine">
            @csrf
            @include('forms.form_input',['title' => 'User Name:', 'name' => 'user-name', 'required' => true ])
            @include('forms.form_input',['title' => 'First Name:', 'name' => 'first-name', 'required' => true ])
            @include('forms.form_input',['title' => 'Surname:', 'name' => 'last-name', 'required' => true ])
            @include('forms.form_input',['title' => 'Email Address:', 'name' => 'email', 'required' => true ])
            @include('forms.form_input',['title' => 'Mobile:', 'name' => 'mobile' ])
            @include('forms.form_input',['title' => 'Telephone:', 'name' => 'telephone' ])

{{--             <div class="row" style="color:red;margin-left: 10px">
                <i>( If you are a Contractor for the University of Brighton please select University of Brighton from the below drop-down).</i>
            </div> --}}
            <div class="row register-form">
                <label for="email" class="col-md-3 col-form-label text-md-left font-weight-bold">{{ __('Organisation:') }} <span style="color: red;">*</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        <select  class="form-control @error('organisation') is-invalid @enderror" name="organisation" id="organisation">
                        <option value=""></option>
                                @if(!is_null($clients))
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }} ">{{ $client->name }}</option>
                                    @endforeach
                                @endif
                        </select>

                        @error('organisation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row register-form">
                <label for="email" class="col-md-3 col-form-label text-md-left font-weight-bold">{{ __('Department:') }} <span style="color: red;">*</label>
                <div class="col-md-5">
                    <div class="form-group">
                        <select  class="form-control @error('department') is-invalid @enderror" name="department" id="department">
                        </select>
                        @error('department')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group" id="departmentOther">
                        <input type="text" class="form-control @error('department-other') is-invalid @enderror" name="department-other" placeholder="Please add new department">
                        @error('department-other')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </div>

            @include('forms.form_dropdown',['title' => 'Role:', 'data' => $roleSelect, 'name' => 'role', 'key'=> 'id', 'value'=>'name', 'required' => true ])
{{--             <div class="row register-form">
                <label for="email" class="col-md-3 col-form-label text-md-left font-weight-bold">Captcha: <span style="color: red;">*</label>
                <div class="col-md-5">
                    <div class="form-group @error('g-recaptcha-response') is-invalid @enderror}">
                            {!! app('captcha')->display() !!}
                            @error('g-recaptcha-response')
                                <span style="color: #dc3545;font-weight: bolder;font-size: 90%;">
                                    {{ $message }}
                                </span>
                            @enderror
                    </div>
                </div>
            </div> --}}

            <div class="col-md-6 offset-md-3 mt-4">
                <button type="submit" class="btn light_grey_gradient ">
                    {{ __('Send Account Request') }}
                    </button>
            </div>
        </form>
    </div>
</div>

@endsection
