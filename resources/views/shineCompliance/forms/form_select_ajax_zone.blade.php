<div class="row register-form">
    <label class="col-md-3 col-form-label text-md-left font-weight-bold">{{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*
    @endif
    </label>
    <div class="col-md-5">
        <div class="form-group">
            <select  class="form-control mb-3" name="{{ isset($name) ? $name.'_parent' : '' }}" id="{{ isset($name) ? $name.'_parent' : '' }}">
            </select>
            <select  class="form-control @error($name) is-invalid @enderror" name="{{ isset($name) ? $name : '' }}" id="{{ isset($name) ? $name : '' }}">
            </select>
            @error('department')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
</div>
