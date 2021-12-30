@extends('shineCompliance.layouts.app')

@section('content')
    @if( request()->route()->getName() == 'shineCompliance.home_shineCompliance')
        @include('shineCompliance.partials.nav',['breadCrumb' => 'home_shineCompliance', 'color' => 'red'])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'profile_shineCompliance', 'color' => 'red', 'data' =>$data ])
    @endif

<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $data->full_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.user.partials._user_profile_sidebar', ['data' => $data])
        <div class="row mt-4">
            @include('shineCompliance.user.partials._data_centre_sidebar')
            <div class="col-md-5" >
                <div class="card discard-border-radius">
                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                    <div class="card-body">
                        @include('shineCompliance.forms.form_text',['title' => 'Forename:', 'data' =>  $data->first_name ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Surname:', 'data' => $data->last_name ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Shine:', 'data' =>$data->shine_reference ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Organisation:', 'data' =>  $data->clients->name ?? '' ])
                        @if($data->client_id == 1)
                            @include('shineCompliance.forms.form_text',['title' => 'Department:', 'data' => isset($data->department->parents->name) ?  ($data->department->parents->name ?? '')." - ".($data->department->name ?? '') : ($data->department->name ?? '') ])
                        @else
                            @include('shineCompliance.forms.form_text',['title' => 'Department:', 'data' => $data->departmentContractor->name ?? '' ])
                        @endif
                        @include('shineCompliance.forms.form_text',['title' => 'Job Title:', 'data' => $data->contact->job_title ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Telephone:', 'data' => $data->contact->telephone ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Mobile:', 'data' => $data->contact->mobile ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Email:', 'data' => $data->email ?? '' ])
                        @include('shineCompliance.forms.form_text',['title' => 'Role:', 'data' => $data->userRole->name ?? ''])
{{--                        @include('shineCompliance.forms.form_text',['title' => 'Last shineAsbestos Awareness Training:', 'data' => ($data->last_asbestos_training > 0) ? (date("d/m/Y", $data->last_asbestos_training) ??  date('d/m/Y'))  : '' ])--}}
{{--                        @include('shineCompliance.forms.form_text',['title' => 'Last shineAsbestos Training:', 'data' => ($data->last_shine_asbestos_training > 0) ? (date("d/m/Y", $data->last_shine_asbestos_training) ??  date('d/m/Y'))  : '' ])--}}
{{--                        <div class="row">--}}
{{--                            <label for="last-name" class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >{{ __('User Type:') }}</label>--}}
{{--                            <div class="col-md-5">--}}
{{--                                @if($data->is_site_operative)--}}
{{--                                    <span>Site Operative </span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        @include('shineCompliance.forms.form_text',['title' => 'Notes:', 'data' => $data->notes ?? '' ])
                        <div class="row">
                            <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >{{ __('Signature:') }}</label>
                            <div class="col-md-5 mt-2">
                                <img src="{{ asset(\ComplianceHelpers::getFileImage($data->id, USER_SIGNATURE)) }}" class="image-signature">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
