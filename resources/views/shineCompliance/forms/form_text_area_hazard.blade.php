<div class="row register-form parent-element {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
        <span style="color: red" class="d-none" id="{{$name}}-star">*</span>
    </label>
    <div class="row form-group col-md-6 {{$text_area_class ?? ''}}" >
        <textarea class="text-area-form {{isset($required) ? 'form-require' : ''}}" style="{{ isset($css) ? $css : ''}}" name="{{ $name }}" id="{{ isset($id) ? $id : $name }}" >{{ isset($data) ? $data : old($name) }}</textarea>
        <span style="color:#dc3545;width: 100%;margin-top: 10px" role="alert">
            <strong></strong>
        </span>
    </div>
</div>
