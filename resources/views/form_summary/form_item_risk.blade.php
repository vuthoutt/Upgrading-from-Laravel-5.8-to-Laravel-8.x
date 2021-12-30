<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >What type of Items would you like to see?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="risk" id="{{ $id ?? '' }}">
                <option value='allitem'>All Items</option>
                <option value='highrisk'>High Risk Items</option>
                <option value='mediumrisk'>Medium Risk Items</option>
                <option value='lowrisk'>Low Risk Items</option>
                <option value='verylowrisk'>Very Low Risk Items</option>
            </select>
        </div>
    </div>
</div>