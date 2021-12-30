<div class="row register-form parent-element {{ $class_other ?? '' }}" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >
        {{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
        <span style="color: red" class="d-none" id="{{$name}}-star">*</span>
    </label>
    <div class="col-md-5">
        <select class="js-example-basic-multiple js-states form-control {{isset($required) ? 'form-require' : ''}}"  id="{{ $name }}" name="{{ $name}}[]" multiple="multiple" style="width:100%">
            @foreach($data_multis as $data_multi)
                <option value="{{ $data_multi->{$key}  }}">{{ $data_multi->{$value} }}</option>
            @endforeach
        </select>
        <span class="invalid-feedback" role="alert">
                <strong>
                     @error($name){{ $message }}
                    @enderror
                </strong>
        </span>
    </div>
</div>

@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){
        $('#{{ $name }}').select2({
            minimumResultsForSearch: -1,
            placeholder: "Please select option"
        });
        $('#{{ $name }}').val(<?php echo json_encode($data ?? ''); ?>);
        $('#{{ $name }}').trigger('change'); // Notify any JS components that the value changed
    });
    </script>
@endpush