<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >Who would you like to check?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="user-group" id="{{ $id ?? '' }}">
                <option value='all'>Everyone</option>
                <option value='organisation'>Organisation</option>
                <!--<option value='department'>Department</option>-->
                <option value='user'>User</option>
            </select>
        </div>
    </div>
</div>