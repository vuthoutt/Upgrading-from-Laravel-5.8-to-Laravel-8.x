<div class="row register-form" style="margin-bottom: 30px" id="{{ $name ? $name : $id }}-form">
    <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}</label>
    <div class="col-md-5 {{$text_area_class ?? ''}}" style="height: 150%">
        <textarea class="text-area-form" name="{{ $name }}" id="{{ $name }}" style="height: 150%">{{ isset($data) ? $data : old($name) }}</textarea>
    </div>
</div>
