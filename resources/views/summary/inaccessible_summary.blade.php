@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    @include('form_summary.form_select_inaccessible_type')
    <div class="form-summary">
    <button type="submit" id="submit" class="btn light_grey_gradient ml-3">
        Export CSV File
    </button>
</div>
</div>
@endsection
