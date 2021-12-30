<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >What would you like to look for?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="inaccessible_type" id="{{ $id ?? '' }}">
                <option value='room'>Room</option>
                <option value='void'>Void</option>
                <option value='item'>Item</option>
            </select>
        </div>
    </div>
</div>