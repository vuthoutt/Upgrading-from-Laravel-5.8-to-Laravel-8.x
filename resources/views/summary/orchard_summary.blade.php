@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <div class="row register-form form-summary">
        <label class="font-weight-bold col-md-12" >What option would you like to check?
        </label>
        <div>
            <div class="form-group col-md-12">
                <select  class="form-control input-summary" name="orchard_type">
                    <option value='1'>3rd Party Display Address</option>
                    <option value='2'>Properties found in Shine but not found in 3rd party software</option>
                    <option value='3'>Properties found in both</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-summary">
        <button type="submit" id="submit" class="btn light_grey_gradient ml-3">
            Export CSV File
        </button>
    </div>
</div>
@endsection
