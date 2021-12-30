<div class="row register-form">
    @if(isset($title))
        <label for="first-name" class="col-md-2 col-form-label text-md-left font-weight-bold">{{ isset($title) ? $title : '' }} </label>
        <div class="col-md-9">
                <textarea name="{{ isset($name) ? $name : '' }}">{{ isset($data) ? $data : '' }}</textarea>
        </div>
    @else
        <div class="col-md-12">
            <textarea name="{{ isset($name) ? $name : '' }}">{{ isset($data) ? $data : '' }}</textarea>
        </div>
    @endif
</div>

@push('javascript')

    <script type="text/javascript">
        CKEDITOR.replace( '<?= isset($name) ? $name : '' ?>' );
    </script>
@endpush
