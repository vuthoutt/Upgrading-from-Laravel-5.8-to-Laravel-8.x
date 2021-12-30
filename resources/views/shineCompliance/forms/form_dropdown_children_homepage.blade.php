<div class="row register-form {{ isset($class_other) ? $class_other : '' }}" id="{{isset($id) ? $id : $name}}-form"
    @isset($hide)
        {{ 'style=display:none' }}
    @endisset>
    <label class="col-md-{{ isset($width_label) ? $width_label : 4 }} col-form-label text-md-left" >{{ isset($title) ? $title : '' }}
    </label>
    <div class="col-md-{{ isset($width) ? $width : 6 }}">
        <div class="form-group ">
            <select  class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="{{isset($id) ? $id : $name}}">
                @if(isset($no_first_op) && $no_first_op)
                @else
                <option value="" data-option="0">Overview</option>
                @endif
                @if(isset($data) and !is_null($data))
                    @foreach($data as $key => $val)
                        <option value="{{ $key }}" data-option="{{ isset($option_data) ? $val->{$option_data} : '' }}"  >{{ $val }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
@push('javascript')

    <script type="text/javascript">

    $(document).ready(function(){


    });
</script>
@endpush
