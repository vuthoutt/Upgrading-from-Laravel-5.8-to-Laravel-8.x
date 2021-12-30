@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'programmes_edit', 'color' => 'red', 'data' => $programme])
<div class="container-cus prism-content pad-up pl-0">
    <div class="row">
        <h3 class="col-12 title-row">Edit Programme</h3>
    </div>
    <div class="main-content">
        <form id="form_edit_property" enctype="multipart/form-data" class="form-shine" method="POST" action="{{  route('shineCompliance.programme.post_edit',['id' => $programme->id ?? 0]) }}">
            @csrf

            @include('shineCompliance.forms.form_input',['title' => 'System Name:', 'data' => $programme->name ?? '', 'name' => 'name','required' =>true, ])
{{--            @include('shineCompliance.forms.form_datepicker',['title' => 'Date Inspected:','data' => $programme->date_inspected_display ?? '', 'name' => 'date_inspected' ])--}}
            @include('shineCompliance.forms.form_input',['title' => 'Inspection Period:', 'data' => $programme->inspection_period ?? '', 'name' => 'inspection_period','required' =>true, 'type' => "number"])
{{--             @include('shineCompliance.forms.form_upload_system',['title' => 'Photo:', 'name' => 'photo',
            'folder' => COMPLIANCE_PROGRAMME_PHOTO,
            'object_id' => $programme->id ]) --}}

            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
