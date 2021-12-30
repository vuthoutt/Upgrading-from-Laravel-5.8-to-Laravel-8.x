<div class="row register-form">
    <label  class="col-md-3 col-form-label text-md-left font-weight-bold">
    {{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">
    @endif
    </label>
    <div class="col-md-5">
        <div class="form-group">
            <textarea class="form-control @error($name) is-invalid @enderror" name="{{ isset($name) ? $name : '' }}" name="{{$name}}" id="{{$name}}"
                      rows="6" >{{ isset($data) ? $data : '' }}</textarea>
            @error($name)
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>
