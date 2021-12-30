<div class="row register-form {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form">
    <label class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold fs-8pt" >{{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-{{ isset($width) ? $width : 5 }}">
        <div class="parent-element">
            <label class="row col-form-label text-md-left font-weight-bold fs-8pt" >Verb
                @if(isset($required))
                    <span style="color: red;">*</span>
                @endif
            </label>
            <div class="row form-group ">
                <select  class="form-control action_recommendation_dr {{isset($required) ? 'form-require' : ''}} @error($name) is-invalid @enderror {{ $form_class ?? '' }}" name="act_recommendation_verb">
                    <option value="" data-option="0">------ Please select an option -------</option>
                    @if(isset($data_verb) and !is_null($data_verb))
                        @foreach($data_verb as $val)
                            <option value="{{ $val->{$key} }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}"  {{ isset($compare_verb_value) ? ($val->{$key} == $compare_verb_value ? 'selected' : '' ) : ''}}>{{ $val->{$value} }}</option>
                        @endforeach
                    @endif
                    {{--                <option value="-1">Other</option>--}}
                </select>
                <input class="form-control other_input mt-4" type="text" name="act_recommendation_verb_other"  value="{{ $other_value_verb ?? '' }}" {{isset($other_value_verb) ? '' : 'style=display:none' }} />
                <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
            </div>
        </div>
        <div class="parent-element">
            <label class="row col-form-label text-md-left font-weight-bold fs-8pt" >Noun
                @if(isset($required))
                    <span style="color: red;">*</span>
                @endif
            </label>
            <div class="row form-group ">
                <select  class="form-control action_recommendation_dr {{isset($required) ? 'form-require' : ''}} @error($name) is-invalid @enderror {{ $form_class ?? '' }}" name="act_recommendation_noun" id="action_recommendation_noun">
                    <option value="" data-option="0">------ Please select an option -------</option>
                    @if(isset($data_noun) and !is_null($data_noun))
                        @foreach($data_noun as $val)
                            <option value="{{ $val->{$key} }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}" data-hazard-type="{{ $val->hazard_type_id ?? ''  }}" {{ isset($compare_noun_value) ? ($val->{$key} == $compare_noun_value ? 'selected' : '' ) : ''}}>{{ $val->{$value} }}</option>
                        @endforeach
                    @endif
                    {{--                <option value="-1">Other</option>--}}
                </select>
                <input class="form-control other_input mt-4" type="text" name="act_recommendation_noun_other" value="{{ $other_value_noun ?? '' }}" {{isset($other_value_noun) ? '' : 'style=display:none' }} />
                <span class="invalid-feedback" role="alert">
                <strong></strong>
            </span>
            </div>
        </div>
    </div>
</div>
@push('javascript')

    <script type="text/javascript">
        $(document).ready(function(){
            $('.action_recommendation_dr').change(function(){
                if($(this).find(":selected").text() == 'Other'){
                    $(this).closest('.row').find('.other_input').show();
                } else {
                    $(this).closest('.row').find('.other_input').hide();
                }
                  //validate
                if(!$(this).val()){
                    $(this).closest('.row').find('span strong').html('This field can not be empty.');
                    $(this).addClass('is-invalid');
                } else {
                    $(this).closest('.row').find('span strong').html('');
                    $(this).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
