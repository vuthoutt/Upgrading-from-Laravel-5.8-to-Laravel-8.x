<div class="row register-form {{ $class_other ?? '' }}" id="{{ $name ?? ''}}-form">
    <label for="first-name" class="col-md-{{ isset($width_label) ? $width_label : 3 }}  col-form-label text-md-left font-weight-bold fs-8pt">
    {{ isset($title) ? $title : '' }}
</label>
    <div class="col-md-5 mt-1">
            <label class="switch ">
            <input type="checkbox" name="{{ $name }}" id="{{ $id ?? $name }}" class="{{isset($class) ? $class : ''}}" {{ ($data == $compare) ? 'checked' : '' }}>
            <span class="slider round" id="{{ $id ?? $name }}_checkbox" ></span>
            </label>
    </div>
</div>
