<div class="row register-form {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="form-group ">
            <select  class="form-control @error($name) is-invalid @enderror {{ $form_class ?? '' }}" name="{{$name}}">
                @if(isset($no_first_op))
                @else
                    <option value="" data-option="0">------ Please select an option -------</option>
                @endif
                @if(isset($non_user))
                    <option value="-1" data-option="-1" {{ isset($compare_value) ? (-1 == $compare_value ? 'selected' : '' ) : ''}}>Non User</option>
                @endif
                @if(isset($data) and !is_null($data))
                    @foreach($data as $val)
                        @if(isset($type) and $type == 'site_data')
                            <option value="{{ $val->{$key} }}"   {{ in_array($val->{$key}, $compare_value) ? 'selected' : ''}} >{{ $val->{$value} }}</option>
                        @else
                            <option value="{{ $val->{$key} }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}"  {{ isset($compare_value) ? ($val->{$key} == $compare_value ? 'selected' : '' ) : ''}}>{{ $val->{$value} }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
            <span class="invalid-feedback" role="alert">
                <strong>@error($name){{ $message }}@enderror</strong>
            </span>
        </div>
        <div class="form-group">
            <select  class="form-control mt-1 property_system" name="property_system" style="display: none">
                <option value="" data-option="0">------ Please select an option -------</option>
            </select>
            <span class="invalid-feedback" role="alert"><strong>@error('property_system'){{ $message }}@enderror</strong></span>
        </div>
        <div class="form-group">
            <select  class="form-control mt-1 property_equipment" name="property_equipment" style="display: none">
                <option value="" data-option="0">------ Please select an option -------</option>
            </select>
            <span class="invalid-feedback" role="alert"><strong>@error('property_equipment'){{ $message }}@enderror</strong></span>
        </div>
        <div class="form-group">
            <select  class="form-control mt-1 property_programme" name="property_programme" style="display: none">
                <option value="" data-option="0">------ Please select an option -------</option>
            </select>
            <span class="invalid-feedback" role="alert"><strong>@error('property_programme'){{ $message }}@enderror</strong></span>
        </div>
    </div>
</div>
