<div class="row register-form" id="form-{{ $id ?? ''}}">
    <label for="first-name" class="col-md-3 col-form-label text-md-left font-weight-bold">{{ isset($title) ? $title : '' }}</label>
    <div class="col-md-5 mt-2" id="form-checkbox-{{ $id ?? ''}}">
            <label class="switch ">
            <input type="checkbox" name="{{ $name }}" class="primary" {{ ($data == $compare) ? 'checked' : '' }} id="{{ $id ?? ''}}">
            <span class="slider round"></span>
            </label>
            <span style="margin-left: 15px;margin-top: 5px;">{{ isset($title_right) ? $title_right : '' }}</span>
    </div>
</div>
