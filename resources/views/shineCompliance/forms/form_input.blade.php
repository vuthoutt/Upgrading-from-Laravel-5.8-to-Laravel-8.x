<div class="row register-form {{ $class_other ?? '' }} parent-element" id="{{ $name ?? ''}}-form">
    <label  class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt">
        {{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group">
            <input maxlength="{{isset($maxlength) ? $maxlength : ''}}" type="{{$type ?? 'text'}}" class="form-control {{isset($required) ? 'form-require' : ''}} @error($name)
                is-invalid @enderror" name="{{ isset($name) ? $name : '' }}" id="{{ isset($id) ? $id : '' }}"
                   value="{{ isset($data) ? $data : old($name) }}" placeholder="{{ isset($placeholder) ? $placeholder : '' }}" {{ $required_html ?? '' }}>

            <span class="invalid-feedback" role="alert">
                <strong>
                @error($name)
                    {{ $message }}
                    @enderror
                </strong>
            </span>
        </div>
    </div>
</div>
