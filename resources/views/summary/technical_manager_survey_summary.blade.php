@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <input type="hidden" name="summary_type" value="survey">
    @include('form_search.search_survey',['id' => 'survey-search'])
    <div class="form-summary">
    <button type="submit" id="btnGenPdf" class="btn light_grey_gradient ml-3">
        Export to PDF
    </button>
</div>
</div>
@endsection
