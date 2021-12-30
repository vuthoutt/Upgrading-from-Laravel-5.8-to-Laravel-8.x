<div class="row register-form {{ isset($class_other) ? $class_other : '' }}" style="margin-bottom: 30px">
    <label class="col-md-3 col-form-label text-md-left font-weight-bold" >
        {{ isset($title) ? $title : '' }}
        @if(isset($required))
            <span style="color: red;">*</span>
        @endif
    </label>
    <div class="col-md-5" style="height: 150%">
        <textarea class="text-area-form {{ $class ?? '' }}"  maxlength="{{$max_length ?? 10000}}" name="{{ $name }}" id="{{ $name }}" style="height: 150% !important;">{{ isset($data) ? $data : old($name) }}</textarea>
        <span class="invalid-feedback" role="alert">
            <strong>
                @error($name)
                {{ $message }}
                @enderror
            </strong>
        </span>
    </div>
</div>
