<div class="row register-form parent-element {{ $class_other ?? '' }}" id="{{$name ?? ''}}-form">
    <label  class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt">
        {{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
        <span style="color: red" class="d-none" id="{{$name}}-star">*</span>
    </label>
    <div class="col-md-{{ isset($width) ? $width : 1 }}">
        <div class="form-group">
            <input  type="text" class="form-control @error($name)
                is-invalid @enderror {{isset($required) ? 'form-require' : ''}}" name="{{ isset($name) ? $name : '' }}" id="{{ $name ?? ''}}"
                   value="{{ isset($data) ? $data : old($name) }}">
            <span class="invalid-feedback" role="alert" style="width: 300px">
                <strong>
                @error($name)
                    {{ $message }}
                    @enderror
                </strong>
            </span>
        </div>
    </div>
    <label class="col-md-1 mt-1 font-weight-bold fs-8pt">{{ $measurement ?? '' }}</label>
    <span class="mt-1 validate_tmv" id="{{ $name ?? ''}}_validate" style="color: red"></span>
</div>
