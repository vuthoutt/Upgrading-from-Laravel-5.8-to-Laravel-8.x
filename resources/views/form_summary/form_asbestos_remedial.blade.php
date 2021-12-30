<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >What option would you like to view?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="asbestosRemedialType" id="{{ $id ?? '' }}">
                <option value='actionRecommendation'>Action/recommendation</option>
                <option value='location'>Location</option>
                <option value='riskType'>Risk Type</option>
                <option value='productDebris'>Product/debris Type</option>
            </select>
        </div>
    </div>
</div>