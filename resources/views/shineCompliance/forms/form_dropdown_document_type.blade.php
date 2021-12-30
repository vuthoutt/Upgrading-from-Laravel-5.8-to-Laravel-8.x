<div class="row register-form parent-element {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group ">
            {{--            <select  class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="{{isset($id) ? $id : $name}}">--}}
            <select  class="form-control {{isset($required) ? 'form-require' : ''}} @error($name) is-invalid @enderror {{ $form_class ?? '' }}" name="{{$name}}" id="{{isset($id) ? $id : $name}}">
                @if(isset($no_first_op))
                @else
                    <option value="" data-option="0">------ Please select an option -------</option>
                @endif
                @if(isset($non_user))
                    <option value="-1" data-option="-1" {{ isset($compare_value) ? (-1 == $compare_value ? 'selected' : '' ) : ''}}>Non User</option>
                @endif
                @if(isset($data) and !is_null($data))
                    @foreach($data as $val)
                        <option value="{{ $val->{$key} }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}"  {{ isset($compare_value) ? ($val->{$key} == $compare_value ? 'selected' : '' ) : ''}}>{{ $val->{$value} }}</option>
                    @endforeach
                @endif
            </select>
            @if(isset($other))
                <input class="form-control mt-4 type_other" type="text" name="{{ $other }}" id="{{ $other_id }}id" value="{{ $other_value }}" />
                <input type="hidden" class="form-control" id="other-input" value="1">
            @else
                <input type="hidden" class="form-control" id="other-input-x" value="0">
            @endif
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
            // var other_input = $("#other-input").val();
            //
            // if (other_input == 1) {
                var name = '{{ $name }}';
                var other = '{{ isset($other_id) ? $other_id : '' }}'+ 'id';
                if ($(".doc_type").find(":selected").text() == 'Other') {
                    $('#'+other).show();
                } else {
                    $('#'+other).val('');
                    $('#'+other).hide();
                }
                $(".doc_type").change(function(){
                    if ($(this).find(":selected").text() == 'Other') {
                        $(this).closest('.form-group').find('#'+other).show();
                    } else {
                        $(this).closest('.form-group').find('#'+other).hide();
                        $(this).closest('.form-group').find('#'+other).val('');
                    }
                });

            // }

        });
    </script>
@endpush
