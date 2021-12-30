<div class="row register-form {{ $class ?? '' }}" id="form-{{ $id ?? ''}}">
    <label for="first-name" class="col-md-4 col-form-label text-md-left font-weight-bold">{{ isset($title) ? $title : '' }}</label>
    <div class="col-md-5 mt-2">
            <label class="switch">
            <input type="checkbox" name="{{ $name }}" class="primary {{ $class ?? '' }}" {{ ($data == $compare) ? 'checked' : '' }} id="{{ $id ?? ''}}" style="-webkit-appearance: none;
-moz-appearance: none;
appearance: none;" value="1">
            <span class="slider round"></span>
            </label>
            <span style="margin-left: 15px;margin-top: 5px;">{{ isset($title_right) ? $title_right : '' }}</span>
    </div>
</div>
