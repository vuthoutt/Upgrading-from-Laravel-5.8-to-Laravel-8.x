@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    @include('form_search.search_property',['id' => 'property-search'])
    <div class="form-summary">
        <button type="submit" id="submit" class="btn light_grey_gradient ml-3">
            Export CSV File
        </button>
    </div>
</div>
@endsection
