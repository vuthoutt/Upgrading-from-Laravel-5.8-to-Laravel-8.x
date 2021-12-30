@extends('layouts.app')
@section('content')

@include('partials.nav', ['breadCrumb' => 'survey_question_shinecompliance', 'data' => $survey, 'color' => 'orange'])
<div class="container prism-content">
    <h3>Edit Method By Questionnaire </h3>
    <div class="main-content">
    <form method="POST" action="{{ route('shineCompliance.post.method_question',['survey_id' => $survey_id]) }}" class="form-shine">
        @csrf
        @foreach($allQuestion as $question)
            @include('shineCompliance.forms.form_method_question',['question' => $question])
        @endforeach
        <div class="btn survey-information-submit" >
            <button type="submit" class="btn light_grey_gradient">
                <strong>{{ __('Save') }}</strong>
            </button>
        </div>
    </form>
    </div>
</div>
@endsection
