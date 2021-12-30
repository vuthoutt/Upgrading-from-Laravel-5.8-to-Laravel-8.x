<div class="offset-top40">
    @include('shineCompliance.forms.form_input',['title' => 'Manufacturer:', 'name' => 'manufacturer', 'data' =>'' ,'id' => 'item','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_input',['title' => 'Model:', 'name' => 'model', 'data' =>'' ,'id' => 'item','class_other' => 'equipment_section'])
    <div class="row register-form equipment_section" id="dimensions-form">
        <label  class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">
            Dimensions:
        </label>
        <div class="col-md-9 row">
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Length:</label>
                <div class="form-group ml-2" style="width: 70px">
                    <input type="text" class="form-control" name="dimensions_length" value="">
                </div>
                <label class="ml-1 mt-1 font-weight-bold fs-8pt">m</label>
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Width:</label>
                <div class="form-group ml-2" style="width: 70px">
                    <input type="text" class="form-control" name="dimensions_width" value="">
                </div>
                <label class="ml-1 mt-1 font-weight-bold fs-8pt">m</label>
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Depth:</label>
                <div class="form-group ml-2" style="width: 70px">
                    <input type="text" class="form-control" name="dimensions_depth" value="">
                </div>
                <label class="ml-1 mt-1 font-weight-bold fs-8pt">m</label>
        </div>
    </div>

    @include('shineCompliance.forms.form_input_small',['title' => 'Capacity:', 'name' => 'capacity', 'data' =>'' ,'measurement' => 'ltrs','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_input_small',['title' => 'Volume of Stored Water:', 'name' => 'stored_water', 'data' =>'' ,'measurement' => 'ltrs','class_other' => 'equipment_section'])

</div>