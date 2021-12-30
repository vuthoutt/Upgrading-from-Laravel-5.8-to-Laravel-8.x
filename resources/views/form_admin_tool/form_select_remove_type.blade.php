<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" > {{ isset($title) ? $title : 'Where would you like to look?' }}
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="type" id="{{ $id ?? '' }}">
                <option value='group'>Groups</option>
                <option value='properties'>Properties</option>
                <option value='register_area'>Floor Register</option>
                <option value='register_location'>Room Register</option>
                <option value='register_item'>Item Register</option>
                <option value='project'>Project</option>
                <option value='survey'>Survey</option>
                <option value='document'>Documents</option>
            </select>
        </div>
    </div>
</div>
