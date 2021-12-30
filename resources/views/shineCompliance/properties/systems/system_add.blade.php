@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'system_add', 'color' => 'red', 'data' => $property])
<div class="container-cus prism-content pad-up pl-0">
    <div class="row">
        <h3 class="col-12 title-row">Add System</h3>
    </div>
    <div class="main-content">
        <form id="form_edit_property" enctype="multipart/form-data" class="form-shine" method="POST" action="{{  route('shineCompliance.systems.post_add',['property_id' => $property->id ?? 1]) }}">
            @csrf
            <input type="hidden" name="property_id" value="{{ $property->id ?? 0 }}">
            <input type="hidden" name="assess_id" value="0">
            @include('shineCompliance.forms.form_input',['title' => 'System Name:', 'data' => null, 'name' => 'name','required' =>true ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'System Type:', 'data' => $system_types,
                                                            'key' => 'id', 'value' => 'description',
                                                            'name' => 'type','required' =>true  ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Classification:', 'data' => $classifications,
                                                            'key' => 'id', 'value' => 'description',
                                                            'name' => 'classification','required' =>true  ])
            @include('shineCompliance.forms.form_upload',['title' => 'Photo:', 'name' => 'photo', 'folder' => COMPLIANCE_SYSTEM_PHOTO ])
            @include('shineCompliance.forms.form_text_area',['title' => 'Comment:', 'name' => 'comment' ])

            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    Add
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
