@extends('summary.index')
@section('summary_content')
<div class="mt-5">
    <div class="row register-form form-summary action-recommendation">
        <label class="font-weight-bold col-md-12" >What option would you like to check duplication?
        </label>
        <div>
            <div class="form-group col-md-12">
                <select  class="form-control input-summary" name="duplicate_action">
                    <option value='Room'> Check Room Duplication</option>
                    <option value='Floor'> Check Floor Duplication</option>
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
