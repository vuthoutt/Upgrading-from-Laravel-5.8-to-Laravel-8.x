@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <input type="hidden" name="summary_type" value="roomCheck">
    @include('form_search.search_property',['id' => 'property-search'])
    @include('form_summary.form_property_location',['id' => 'property-location'])
    <div class="form-summary">
    <button type="submit" id="submit" class="btn light_grey_gradient ml-3">
        Export to PDF
    </button>
</div>
</div>
@endsection
