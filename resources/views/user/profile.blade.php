@extends('layouts.app')

@section('content')
@include('partials.nav', ['breadCrumb' => 'profile','data' => $userData])

<div class="container prism-content">
    <h3>{{ $userData->full_name }}</h3>
    <div class="main-content">
        <div class="row">
            <div class="col-md-12">
                @include('forms.form_text',['title' => 'Forename:', 'data' => $userData->first_name ?? '' ])
                @include('forms.form_text',['title' => 'Surname:', 'data' => $userData->last_name ?? '' ])
                @include('forms.form_text',['title' => 'Shine Reference:', 'data' => $userData->shine_reference ?? '' ])
                @include('forms.form_text',['title' => 'Organisation:', 'data' => $userData->clients->name ?? '' ])
                @if($userData->client_id == 1)
                @include('forms.form_text',['title' => 'Department:', 'data' => $userData->department->name ?? '' ])
                @else
                @include('forms.form_text',['title' => 'Department:', 'data' => $userData->departmentContractor->name ?? '' ])
                @endif
                @include('forms.form_text',['title' => 'Job Title:', 'data' => $userData->contact->job_title ?? '' ])
                @include('forms.form_text',['title' => 'Telephone:', 'data' => $userData->contact->telephone ?? '' ])
                @include('forms.form_text',['title' => 'Mobile:', 'data' => $userData->contact->mobile ?? '' ])
                @include('forms.form_text',['title' => 'Email:', 'data' => $userData->email ?? '' ])
                @if($userData->client_id == 1)
                @include('forms.form_text',['title' => 'Role:', 'data' => $userData->userRole->name ?? ''])
                @endif
            </div>

            {{-- @if(\Auth::user()->is_admin) --}}
            <div class="col-md-12">
                <div class="row">
                    <label for="last-name" class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ __('User Type:') }}</label>
                    <div class="col-md-5">
                        @if($userData->is_site_operative)
                            <span>Site Operative </span>
                        @endif
                    </div>
                </div>
                @include('forms.form_text',['title' => 'Notes:', 'data' => $userData->notes ])
            </div>
            {{-- @endif --}}

            <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ __('Signature:') }}</label>
            <div class="col-md-5 mt-2">
                <img src="{{ asset(\CommonHelpers::getFile($userData->id, USER_SIGNATURE)) }}" class="image-signature">
            </div>

            <div class="col-md-12 mt-5">
                <div class="row">
                    @include('tables.training_record_user', [
                        'title' => 'Training Records',
                        'tableId' => 'e-learning-table-1',
                        'collapsed' => false,
                        'plus_link' => false,
                        'data' => $data_trainings
                        ])
                </div>
            </div>
            <div class="mt-5 ml-2 pb-5">
                @if(\Auth::user()->role == SUPER_USER_ROLE)
                    <a href="{{ route('user.get-edit',['user' => $userData->id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient ">
                            <strong>{{ __('Edit') }}</strong>
                        </button>
                    </a>
                @endif
                    <a  href="{{ route('user.get_change_password',['id' => $userData->id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient ">
                            <strong>{{ __('Change Password') }}</strong>
                        </button>
                    </a>
                 @if(\Auth::user()->role == SUPER_USER_ROLE)
                    <a href="{{ route('user.lock',['id' => $userData->id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn light_grey_gradient ">
                            @if($userData->is_locked == USER_LOCKED)
                                <strong>{{ __('Unlock User') }}</strong>
                            @else
                                <strong>{{ __('Lock User') }}</strong>
                            @endif
                        </button>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
