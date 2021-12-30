@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <input type="hidden" name="summary_type" value="reinspection_freeze">
    @include('form_summary.form_client_zone',['id' => 'client-zone'])
    @include('form_summary.form_input_summary')
    <div class="form-summary">
        <button type="submit" id="btnGenPdf" class="btn light_grey_gradient ml-3">
            Export to PDF
        </button>
    </div>
</div>
@endsection
