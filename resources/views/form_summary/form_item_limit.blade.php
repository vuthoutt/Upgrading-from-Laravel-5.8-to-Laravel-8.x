<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >How many items would you like to see?
    </label>
    <div>
        <div class="form-group col-md-12">
            <select  class="form-control input-summary" name="item_limit" id="{{ $id ?? '' }}">
                <option value='10'>10</option>
                <option value='25'>25</option>
                <option value='50'>50</option>
                <option value='75'>75</option>
                <option value='100'>100</option>
                <option value='250'>250</option>
                <option value='500'>500</option>
                <option value='1000'>1000</option>
                <option value='2000'>2000</option>
                <option value='4000'>4000</option>
            </select>
        </div>
    </div>
</div>