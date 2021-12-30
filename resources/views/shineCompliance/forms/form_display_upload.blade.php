<div class="row register-form">
    <label class="col-md-3 col-form-label text-md-left font-weight-bold">{{ isset($title) ? $title : '' }} </label>
    <div class="col-md-5">
        <img src="{{ asset(\CommonHelpers::getFile($object_id, $folder)) }}" class="image-signature">
    </div>
</div>
