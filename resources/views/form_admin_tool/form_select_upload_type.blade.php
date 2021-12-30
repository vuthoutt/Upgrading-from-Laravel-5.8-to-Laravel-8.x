<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" > {{ isset($title) ? $title : 'Where would you like to look?' }}
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="type" id="{{ $id ?? '' }}">
                <option value='property'>Properties</option>
                <option value='user'>Users</option>
                <option value='programmes'>Programmes</option>
                <option value='systems'>Systems and Programmes</option>
            </select>
        </div>
    </div>
</div>
