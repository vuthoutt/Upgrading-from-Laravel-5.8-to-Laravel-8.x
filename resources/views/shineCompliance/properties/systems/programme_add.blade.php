@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav', ['color' => 'red'])
<div class="container-cus prism-content pad-up pl-0">
    <div class="row">
        <h3 class="col-12 title-row">Add Programme</h3>
    </div>
    <div class="main-content">
        <form id="form_edit_property" enctype="multipart/form-data" class="form-shine" method="POST" action="{{  route('shineCompliance.programme.post_add',['system_id' => $system_id ?? 1]) }}">
            @csrf

            @include('shineCompliance.forms.form_input',['title' => 'System Name:', 'data' => null, 'name' => 'name','required' =>true ])
            @include('shineCompliance.forms.form_datepicker',['title' => 'Date Inspected:','data' => date('d/m/Y'), 'name' => 'date_inspected','required' =>true ])
            @include('shineCompliance.forms.form_upload_system',['title' => 'Photo:', 'name' => 'photo', 'folder' => COMPLIANCE_PROGRAMME_PHOTO ])

            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    Add
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
