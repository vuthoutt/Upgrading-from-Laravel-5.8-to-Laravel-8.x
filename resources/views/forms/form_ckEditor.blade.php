<div class="row register-form">
    <label for="first-name" class="col-md-2 col-form-label text-md-left font-weight-bold">{{ isset($title) ? $title : '' }} </label>
    <div class="col-md-9">
            <textarea name="{{ isset($name) ? $name : '' }}">{{ isset($data) ? $data : '' }}</textarea>
    </div>
</div>

@push('javascript')

    <script type="text/javascript">
        CKEDITOR.replace( '<?= isset($name) ? $name : '' ?>' );
    </script>
@endpush
