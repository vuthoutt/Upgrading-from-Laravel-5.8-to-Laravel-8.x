<div class="row register-form {{ isset($class_other) ? $class_other : '' }}">
    <label for="first-name" class="col-md-{{ isset($width_label) ? $width_label : 3 }}  col-form-label text-md-left font-weight-bold">
    {{ isset($title) ? $title : '' }}
</label>
    <div class="col-md-1 mt-1">
            <label class="switch ">
            <input type="checkbox" name="{{ $name }}" class="primary {{isset($class) ? $class : ''}}" {{ ($data == $compare) ? 'checked' : '' }} id="{{ $name }}">
            <span class="slider round"></span>
            </label>
    </div>
    <div class="col-md-4 mt-1 mb-5">
        <textarea class="text-area-form" name="{{ $name }}_comment" id="{{ $name }}_comment" style="height: 150%">{{ $data_comment ?? '' }}</textarea>
    </div>
</div>
@push('javascript')

<script type="text/javascript">

    $(document).ready(function(){

        $("#{{ $name }}").change(function () {
            var checked = $(this).prop('checked');
            if (checked) {
                $("#{{ $name }}_comment").show();
            } else {
                $("#{{ $name }}_comment").hide();
            }
        });
        $("#{{ $name }}").trigger('change');
    });
</script>
@endpush
