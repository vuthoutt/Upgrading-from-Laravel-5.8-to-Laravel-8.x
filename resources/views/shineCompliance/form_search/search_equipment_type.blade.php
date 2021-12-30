<div class="row register-form">
    <div class="col-md-3 fs-8pt">
            <strong>{{ $title ?? '' }} <span style="color: red;">*</span></strong>
    </div>
    <div class="col-md-7 mb-3">
        <input class="form-control @error($name) is-invalid @enderror" id="equipment-search-{{$id}}" value="{{$data ?? ''}}" style="width: 457px !important" >
        <input type ="hidden" id="equipment-type-holder" name="{{ $name }}" value="{{ $value ?? null }}" />
        <span class="invalid-feedback" role="alert" style="display: block">
            <strong>@error($name){{ $message }}@enderror</strong>
        </span>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){

});

</script>
@endpush
