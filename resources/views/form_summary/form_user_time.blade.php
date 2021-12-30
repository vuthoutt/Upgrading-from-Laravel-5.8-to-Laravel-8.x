<div class="row register-form form-summary" id="form-{{ $id ?? '' }}">
    <label class="font-weight-bold col-md-12" >What time period would you like to look at?
    </label>
    <div>
        <div class="form-group col-md-12">
            @include('forms.form_datepicker',['title' => 'Start Date:', 'name' => 'user-start-date','data' => date('d/m/Y') ])
            @include('forms.form_datepicker',['title' => 'Date:', 'name' => 'user-finish-date','data' => date('d/m/Y') ])
        </div>
    </div>
</div>