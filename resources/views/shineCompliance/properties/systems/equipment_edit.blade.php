@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' =>'equipment_edit', 'color' => 'red', 'data' => $equipment])
<div class="container-cus prism-content pad-up pl-0">
    <div class="row">
        <h3 class="col-12 title-row">Edit Equipment</h3>
    </div>
    <div class="main-content">
        <form id="form_edit_property" enctype="multipart/form-data" class="form-shine" method="POST" action="{{  route('shineCompliance.equipment.post_edit',['id' => $equipment->id ?? 0]) }}">
            @csrf

            @include('shineCompliance.forms.form_input',['title' => 'System Name:', 'data' => $equipment->name ?? '', 'name' => 'name','required' =>true ])
            <div class="row register-form acm">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Accessibility:<span style="color: red;">*</label>
                <div class="col-md-5">
                    <div class="form-group ">
                        <select  class="form-control  @error('state') is-invalid @enderror" name="state" id="accessibility">
                            <option value="">------ Please select an option -------</option>
                            <option value="{{EQUIPMENT_ACCESSIBLE_STATE}}" {{ ($equipment->state == EQUIPMENT_ACCESSIBLE_STATE ? 'selected' : '') }} >Accessible</option>
                            <option value="{{EQUIPMENT_INACCESSIBLE_STATE}}" {{ ($equipment->state == EQUIPMENT_INACCESSIBLE_STATE ? 'selected' : '') }}>Inaccessible</option>
                        </select>
                    </div>
                </div>
                @error('state')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <div class="row register-form acm" id="accessibility-reason">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Inaccessible Reason:<span style="color: red;"></label>
                <div class="col-md-5">
                    <input type="text" name="reason" class="form-control mb-2" value="{{ $equipment->reason ?? '' }}">
                </div>
                @error('reason')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            @include('shineCompliance.forms.form_dropdown',['title' => 'Equipment Type:', 'data' => $types,
                                                            'key' => 'id', 'value' => 'description','compare_value' => $equipment->type ?? 0,
                                                            'name' => 'type','required' =>true  ])
            @include('shineCompliance.forms.form_upload_system',['title' => 'Photo:', 'name' => 'photo',
                                                            'folder' => EQUIPMENT_PHOTO , 'object_id' => $equipment->id ?? 0 ])

            <div class="col-md-6 offset-md-3">
                <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('javascript')

<script type="text/javascript">
$(document).ready(function(){
    $('body').on('change','#accessibility',function(){
        var accessibility = $(this).val();
        if (accessibility == {{EQUIPMENT_INACCESSIBLE_STATE}}) {
            $('#accessibility-reason').show();
        } else {
            $('#accessibility-reason').hide();
        }
    });
     $('#accessibility').trigger('change');
});
</script>
@endpush
