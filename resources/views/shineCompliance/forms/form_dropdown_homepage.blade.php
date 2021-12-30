<div class="row register-form {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form" {{isset($hide) && $hide ? "style=display:none" : ''}}>
    <label class="col-md-{{ isset($width_label) ? $width_label : 4 }} col-form-label text-md-left font-weight-bold" >{{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*</span>
    @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 6 }}">
        <div class="form-group ">
            <select  class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="{{isset($id) ? $id : $name}}">
                @if(isset($no_first_op))
                    <option value="-1" data-option=""  selected> ------- Please select an option ------- </option>
                @else
                <option value="0" data-option="0">Overview</option>
                @endif
                @if(isset($data) and !is_null($data))
                    @foreach($data as $val)
                        <option value="{{ $val->{$key} }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}"  {{ isset($compare_value) ? ($val->{$key} == $compare_value ? 'selected' : '' ) : ''}}>{{ $val->{$value} }}</option>
                    @endforeach
                @endif
            </select>
            @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
@push('javascript')

    <script type="text/javascript">

    $(document).ready(function(){


    });
</script>
@endpush
