@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'survey_info', 'color' => 'orange', 'data' => $survey])

<div class="container prism-content">
    <h3>Edit {{ $surveyInfo['title'] }} </h3>
    <div class="main-content">
    </div>
    <form method="POST" action="{{ route('post.survey-information',['survey_id' => $survey_id, 'type' => $type]) }}" class="form-shine">
        @csrf
        @include('forms.form_ckEditor',['title' => $surveyInfo['title'], 'name' => $surveyInfo['name'], 'data' => $surveyInfo['data'] ])
        <div class="btn survey-information-submit" >
            <button type="submit" class="btn light_grey_gradient">
                <strong>{{ __('Save') }}</strong>
            </button>
        </div>
    </form>
</div>
@endsection