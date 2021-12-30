<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" > {{ isset($title) ? $title : 'Where would you like to look?' }}
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="type" id="{{ $id ?? '' }}">
                {{-- <option value='survey'>Survey</option> --}}
                <option value='project'>Project</option>
                <option value='register_location'>Register Room/location</option>
                <option value='register_item'>Register Item</option>
                <option value='survey_location'>Survey Room/location</option>
                <option value='survey_item'>Survey Item</option>
            </select>
        </div>
    </div>
</div>
