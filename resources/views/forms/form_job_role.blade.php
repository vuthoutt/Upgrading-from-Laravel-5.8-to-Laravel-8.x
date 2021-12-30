<div class="row register-form" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >{{ isset($title) ? $title : 'Which job role would you like to update?' }}
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="{{ $id ?? '' }}" id="{{ $id ?? '' }}">
                @if(isset($first_option) && $first_option)
                    <option value="-1"> ------- Please select an option ------- </option>
                @endif
                @if(isset($data) && !$data->isEmpty())
                    @foreach($data as $val)
                        <option value="{{$val->id}}" {{ isset($compare_value) ? ($val->id == $compare_value ? 'selected' : '' ) : ''}}>{{$val->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){


});

</script>
@endpush
