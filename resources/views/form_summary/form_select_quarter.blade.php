<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >Which Financial Quarter would you like to run the summary for?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select name='form_select_quarter' id="{{ $id ?? '' }}" class="form-control input-summary">
                {{ \CommonHelpers::generateQuarterTime() }}
            </select>
        </div>
    </div>
</div>
