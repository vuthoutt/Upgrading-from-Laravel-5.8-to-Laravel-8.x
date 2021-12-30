@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_system_edit', 'color' => 'orange', 'data'=> $system])

<div class="container prism-content pad-up pl-0">
    <div class="row">
        <h3 class="col-12">Edit System</h3>
    </div>
    <div class="main-content">
        <form id="form_edit_property" enctype="multipart/form-data" class="form-shine" method="POST" action="{{  route('shineCompliance.system.post_edit_system',['id' => $system->id ?? 0]) }}">
            @csrf
            <input type="hidden" name="property_id" value="{{ $system->property_id ?? 0 }}">
            <input type="hidden" name="assess_id" value="{{ $system->assess_id ?? 0 }}">
            @include('shineCompliance.forms.form_input',['title' => 'System Name:', 'data' => $system->name ?? '', 'name' => 'name','required' =>true,  ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'System Type:', 'data' => $system_types,
                                                            'key' => 'id', 'value' => 'description', 'compare_value' => $system->type ?? '',
                                                            'name' => 'type','required' =>true  ])
            @include('shineCompliance.forms.form_dropdown',['title' => 'Classification:', 'data' => $classifications,
                                                            'key' => 'id', 'value' => 'description', 'compare_value' => $system->classification ?? '',
                                                            'name' => 'classification','required' =>true  ])
            @include('shineCompliance.forms.form_upload_system',['title' => 'Photo:', 'name' => 'photo', 'folder' => COMPLIANCE_SYSTEM_PHOTO,'object_id' => $system->id ?? '' ])
            @include('shineCompliance.forms.form_text_area',['title' => 'Comment:', 'name' => 'comment', 'data' => $system->comment ?? '' ])

            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
