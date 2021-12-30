<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >What action type would you like to look at?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="user-action" id="{{ $id ?? '' }}">
                <option value='all'>All</option>
                <option value='view'>View</option>
                <option value='add'>Add</option>
                <option value='edit'>Edit</option>
                <option value='decommission'>Decommission</option>
                <option value='recommission'>Recommission</option>
                <option value='send'>Send</option>
                <option value='publish'>Publish</option>
                <option value='export'>Export to PDF (Generate)</option>
                <option value='external'>External PDF File (Generate)</option>
            </select>
        </div>
    </div>
</div>