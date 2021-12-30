<div class="row register-form parent-element" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
        <span style="color: red" class="d-none" id="{{$name}}-star">*</span>
    </label>
    <div class="col-md-5">
        <div class="form-group ">
            <select  class="form-control @error($name) is-invalid @enderror  {{isset($required) ? 'form-require' : ''}}" name="{{$name}}" id="{{isset($id) ? $id : $name}}">
            <option value="">------ Please select an option -------</option>
            @php
                $check = FALSE;
                if(isset($compare_value)){
                    if($compare_value == 'Other'){
                        $check = TRUE;
                    }else{
                        $check = FALSE;
                    }
                }
            @endphp
            @for($i = 0; $i < 16; $i++)
                <option value="{{ $i }}" {{ ($compare_value == $i) ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
            <option value='Other' {{ (isset($check)) && ($check) ? 'selected' : '' }}>Other</option>
        </select>
        <input class="form-control mt-4" type="text" name="{{ $other_name }}" id="{{ $other_name }}" value="{{ $other }}" style="display: none;"/>
        <span class="invalid-feedback" role="alert">
                <strong>
                     @error($name){{ $message }}
                    @enderror
                </strong>
        </span>
        </div>
    </div>

</div>

@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){

        if ($("#{{$name}}").val() == 'Other') {
            $("#{{ $other_name }}").show()
        } else {
            $("#{{ $other_name }}").hide()
        }
         $("#{{$name}}").change(function(){
            if ($("#{{$name}}").val() == 'Other') {
                $("#{{ $other_name }}").show()
            } else {
                $("#{{ $other_name }}").hide()
            }
        });
    });
</script>
@endpush
