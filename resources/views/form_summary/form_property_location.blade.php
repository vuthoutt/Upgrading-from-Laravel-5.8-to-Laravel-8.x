<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >Which room/location would you like to use?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select class="form-control" name="location[]" id="property-location" multiple="multiple">
            </select>
        </div>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    $('#property-location').select2({
        placeholder: "Please select location",
        width:300
    });

});

</script>
@endpush