<div class="row register-form parent-element">
    <label for="email" class="col-md-{{ isset($width_label) ? $width_label : 3 }} col-form-label text-md-left font-weight-bold">
    {{ isset($title) ? $title : '' }}
    @if(isset($required))
        <span style="color: red;">*</span>
    @endif
    </label>
    <div class="col-md-5">
    <div class="md-form md-outline form-group">
        <input class="form-control {{isset($required) ? 'form-require' : ''}} @error($name) is-invalid @enderror" type="time" class="form-control" placeholder="Select time"
        style="width:276px !important" name="{{ $name ?? ''}}" id="{{ $id ?? $name ?? ''}}" value="{{ $data ?? '' }}">
    </div>
    </div>
</div>
