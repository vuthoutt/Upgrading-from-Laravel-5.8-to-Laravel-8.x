<div class="row register-form" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-3 col-form-label text-md-left font-weight-bold" >{{ isset($title) ? $title : '' }}</label>
    <div class="col-md-5">
        <div class="form-group ">
            <select  class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="{{isset($id) ? $id : $name}}">
            <option></option>
            @php
                if(isset($compare_value)){
                    if((is_numeric($compare_value) && $compare_value > 16) || !is_numeric($compare_value)){
                        $check = TRUE;
                    }else{
                        $check = FALSE;
                    }
                }
            @endphp
            @for($i = 0; $i < 16; $i++)
                <option value="{{ $i }}" {{ (isset($check) && !$check && $compare_value == $i) ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
            <option value='Other' {{isset($check) && $check ? 'selected' : '' }}>Other</option>
        </select>
        <input class="form-control mt-4" type="text" name="{{ $name }}Other" id="{{ $name }}Other" value="{{ $other }}" />
        </div>
    </div>
</div>
@push('javascript')

    <script type="text/javascript">
    $(document).ready(function(){

        if ($("#{{$name}}").val() == 'Other') {
                $("#{{ $name }}Other").show()
        } else {
                $("#{{ $name }}Other").hide()
        }
         $("#{{$name}}").change(function(){
            if ($("#{{$name}}").val() == 'Other') {
                $("#{{ $name }}Other").show()
            } else {
                $("#{{ $name }}Other").hide()
            }
        });
    });
</script>
@endpush
