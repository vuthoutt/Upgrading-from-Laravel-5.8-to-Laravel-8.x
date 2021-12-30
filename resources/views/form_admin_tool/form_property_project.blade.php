<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >{{ $title ?? 'Which survey would you like to use?' }}
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="project" id="{{ $id ?? '' }}">
            </select>
        </div>
    </div>
</div>
@push('javascript')
<script>
$(document).ready(function(){
    var property_id = $('#property-id-search').val();

});

</script>
@endpush