@extends('layouts.app')
@section('content')

@include('partials.nav', ['breadCrumb' => 'home'])
<div class="container prism-content">
    <h3>Publish Manually</h3>
    <div class="main-content">
    <form method="POST" action="{{ route('survey.post_publish_manual') }}" class="form-shine">
        @csrf
        <div class="row register-form">
            <label  class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold">
            Survey Ids
            </label>
            <div class="col-md-{{ isset($width) ? $width : 5 }}">
                <div class="form-group">
                    <input type="text" class="form-control " name="surveyIds" placeholder="1,2,3,4,..." >
                </div>
            </div>
        </div>

        <div class="btn survey-information-submit" >
            <button type="submit" class="btn light_grey_gradient">
                <strong>{{ __('Publish') }}</strong>
            </button>
        </div>
    </form>
    </div>
</div>
@endsection