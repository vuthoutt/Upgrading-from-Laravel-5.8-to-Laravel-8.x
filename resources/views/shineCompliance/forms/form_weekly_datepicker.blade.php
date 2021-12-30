<div class="row register-form">
    <label for="email" class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold">
    {{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*</span>
    @endif
    </label>
    <div class="col-md-3">
        <div class="form-group ">
            <input type="hidden" id="start_wk" name="start">
            <input type="hidden" id="end_wk" name="finish">
            <input type="text" id="{{ isset($id) ? $id : $name}}" class="form-control @error($name) is-invalid @enderror" name="{{ isset($name) ? $name : ''}}" value="{{ isset($data) ? $data : '' }}"
                   width="276" autocomplete="off">
        </div>
            @error($name)
            <span class="invalid-feedback" role="alert" style="display: block">
            <strong>{{ $message }}</strong>
            </span>
            @enderror
    </div>
</div>

@push('javascript')

    <script type="text/javascript">
        $(document).ready(function(){
            convertToWeekPicker($("#{{ isset($id) ? $id : $name}}"));
            // $('#ui-datepicker-div').css('z-index', 9999);
        })
    </script>
@endpush
