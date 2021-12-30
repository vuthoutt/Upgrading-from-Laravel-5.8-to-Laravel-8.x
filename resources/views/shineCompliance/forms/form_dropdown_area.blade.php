<div class="row register-form {{ isset($class_other) ? $class_other : '' }} parent-element" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group ">
            <select  class="form-control {{isset($required) ? 'form-require' : ''}} @error($name.'_id') is-invalid @enderror {{ $form_class ?? '' }}" name="{{$name}}_id" id="{{isset($id) ? $id : $name}}">
                <option value="" data-option="0">------ Please select an option -------</option>
                 @if(isset($data) and !is_null($data))
                    @foreach($data as $val)
                        <option value="{{ $val->{$key} }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}"  {{ isset($compare_value) ? ($val->{$key} == $compare_value ? 'selected' : '' ) : ''}}>
                            {{ $val->title_presentation ?? '' }}
                        </option>
                    @endforeach
                @endif
                @if(isset($enableNA) && $enableNA)
                    <option value="0" {{isset($compare_value) ? (0 == $compare_value ? 'selected' : '' ) : ''}}>N/A</option>
                @endif
                <option value="-1" {{ old($name.'_id') == -1? "selected" : "" }}>Other</option>
            </select>
            <span class="invalid-feedback" role="alert">
                <strong>
                    @error($name.'_id'){{ $message }}
                    @enderror
                </strong>
            </span>
            <div class="parent-element" id="{{ $name ?? '' }}_other">
                <label class="font-weight-bold mt-3">Reference:<span style="color: red;">*</span></label>
                <input class="form-control {{isset($required) ? 'form-require' : ''}} @error($name.'_reference') is-invalid @enderror" type="text" name="{{ $name ?? '' }}_reference"  value="{{ old($name.'_reference') ?? '' }}" />
                <span class="invalid-feedback" role="alert">
                    <strong>
                         @error($name.'_reference'){{ $message }}
                        @enderror
                    </strong>
                </span>
{{--                <label class="font-weight-bold mt-3">Description:</label>--}}
{{--                <input class="form-control" type="text" name="{{ $name ?? '' }}_description" value="{{ old($name.'_description') ?? '' }}">--}}
            </div>

        </div>
    </div>
</div>
@push('javascript')

    <script type="text/javascript">
        $(document).ready(function(){

            $("#{{ $name ?? '' }}_other").hide();
            $("#{{$name}}").change(function(){

                if ($("#{{$name}}").find(":selected").text() == 'Other') {
                    $("#{{ $name ?? '' }}_other").show()
                } else {
                    $("#{{ $name ?? '' }}_other").hide()
                }
            });
            $("#{{$name}}").trigger('change');

        });
    </script>
@endpush
