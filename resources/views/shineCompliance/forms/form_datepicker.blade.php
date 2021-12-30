<div class="row register-form parent-element {{ isset($class) ? $class :'' }}">
    <label for="email" class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt">
    {{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*</span>
    @endif
    </label>
    <div class="col-md-5">
        <div class="form-group">
            <input class="form-control {{isset($required) ? 'form-require' : ''}} @error($name) is-invalid @enderror" name="{{ isset($name) ? $name : ''}}" value="{{ isset($data) ? $data : '' }}" id="{{ isset($id) ? $id : $name}}" width="276">
            <span class="invalid-feedback" role="alert" style="display: block">
                <strong>@error($name){{ $message }}@enderror</strong>
            </span>
        </div>
    </div>
</div>

@push('javascript')

    <script type="text/javascript">
    $('#<?= isset($id) ? $id : $name ?>').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'dd/mm/yyyy',
        todayHighlight: true
    });
    </script>
@endpush
