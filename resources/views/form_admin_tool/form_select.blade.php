<div class="row register-form form-summary d-none {{$class ?? ' '}}" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" > {{ isset($title) ? $title : 'Where would you like to look?' }}
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="mt-3 form-control input-summary" name="{{ $id ?? '' }}" id="{{ $id ?? '' }}">
            </select>
            <span class="invalid-feedback" role="alert">
            <strong>The {{$name ?? ''}} field is required.</strong>
        </span>
        </div>
    </div>
</div>
