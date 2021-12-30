<div class="row register-form {{ isset($class_other) ? $class_other : '' }}">
    <label  class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold">
    {{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*</span>
    @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group row">
            <div class="col-md-3">
                <input type="text" class="form-control @error($name) is-invalid @enderror" name="{{ isset($name) ? $name : '' }}" id="{{ isset($name) ? $name : '' }}" value="{{ isset($data) ? $data : old($name) }}" placeholder="{{ isset($placeholder) ? $placeholder : '' }}" {{ $required_html ?? '' }}>
            </div>
            <div class="col-md-2 mt-1" style="max-width: 70%">
                <span>{{ $amount ?? '' }}</span>
            </div>
            @error($name)
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>
