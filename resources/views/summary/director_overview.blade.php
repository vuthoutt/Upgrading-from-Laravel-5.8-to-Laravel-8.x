@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <input type="hidden" name="summary_type" value="directorOverview">
    @include('form_summary.form_select_quarter',['id' => 'select-quarter'])
    <div class="form-summary">
    <button type="submit" id="btnGenPdfChart" class="btn light_grey_gradient ml-3">
        Export to PDF
    </button>
</div>
</div>
@endsection
