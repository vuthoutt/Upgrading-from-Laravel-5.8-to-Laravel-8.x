<div class="row register-form {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*
    @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group ">
            <select  class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="{{isset($id) ? $id : $name}}">
                @if(isset($no_first_op))
                @else
                <option value="" data-option="0">------ Please select an option -------</option>
                @endif
                @if(isset($data) and !is_null($data))
                    @foreach($data as $val)
                        @if(isset($type) and $type == 'site_data')
                            <option value="{{ $val->{$key} }}" data-parent="{{ $val->parent_id }}"   {{ in_array($val->{$key}, $compare_value) ? 'selected' : ''}} >{{ $val->{$value} }}</option>
                        @else
                            <option value="{{ $val->{$key} }}" data-parent="{{ $val->parent_id }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}"  {{ isset($compare_value) ? ($val->{$key} == $compare_value ? 'selected' : '' ) : ''}}>{{ $val->{$value} }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
            @if(isset($other))
                <input class="form-control mt-4" type="text" name="{{ $other }}" id="{{ $other }}id" value="{{ $other_value }}" />
                <input type="hidden" class="form-control" id="other-input" value="1">
            @else
                <input type="hidden" class="form-control" id="other-input-x" value="0">
            @endif
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
        var other_input = $("#other-input").val();

        if (other_input == 1) {
            var name = '{{ $name }}';
            var other = '{{ isset($other) ? $other : '' }}'+ 'id'
            if ($("#{{$name}}").find(":selected").text() == 'Other') {
                    $("#" + other).show()
                } else {
                    $("#" + other).hide()
                }
             $("#{{$name}}").change(function(){
                if ($("#{{$name}}").find(":selected").text() == 'Other') {
                    $("#" + other).show()
                } else {
                    $("#" + other).hide()
                }
            });

        }

    });
</script>
@endpush
