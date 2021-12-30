<div class="row register-form parent-element {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
        <span style="color: red" class="d-none" id="{{$name}}-star">*</span>
    </label>
    <div class="col-md-{{ $width ?? 5 }}">
        <div class="form-group">
            <select class="form-control @error($name) is-invalid @enderror {{ $form_class ?? '' }} {{isset($required) ? 'form-require' : ''}}" name="{{$name}}" id="{{isset($id) ? $id : $name}}">
            </select>
            @error('department')
            <span class="invalid-feedback" role="alert">
                    <strong>
                         @error($name){{ $message }}
                        @enderror
                    </strong>
            </span>
            @enderror
        </div>
    </div>
</div>
