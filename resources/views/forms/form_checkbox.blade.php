<div class="row register-form {{ isset($class_other) ? $class_other : '' }}">
    <label for="first-name" class="col-md-{{ isset($width_label) ? $width_label : 3 }}  col-form-label text-md-left font-weight-bold">
    {{ isset($title) ? $title : '' }}
</label>
    <div class="col-md-5 mt-1">
            <label class="switch ">
            <input type="checkbox" name="{{ $name }}" class="primary {{isset($class) ? $class : ''}}" {{ ($data == $compare) ? 'checked' : '' }}>
            <span class="slider round"></span>
            </label>
    </div>
</div>
