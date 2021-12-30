<div class="offset-top40">
    @include('shineCompliance.forms.form_input',['title' => 'Manufacturer:', 'name' => 'manufacturer', 'data' => $equipment->equipmentModel->manufacturer ?? '' ,'id' => 'item','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_input',['title' => 'Model:', 'name' => 'model', 'data' => $equipment->equipmentModel->model ?? '' ,'id' => 'item','class_other' => 'equipment_section'])
    <div class="row register-form equipment_section" id="dimensions-form">
        <label  class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">
            Dimensions:
        </label>
        <div class="col-md-9 row">
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Length:</label>
                <div class="form-group ml-2" style="width: 70px">
                    <input type="text" class="form-control" name="dimensions_length" value="{{ $equipment->equipmentModel->dimensions_length ?? '' }}">
                </div>
                <label class="ml-1 mt-1 font-weight-bold fs-8pt">m</label>
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Width:</label>
                <div class="form-group ml-2" style="width: 70px">
                    <input type="text" class="form-control" name="dimensions_width" value="{{ $equipment->equipmentModel->dimensions_width ?? '' }}">
                </div>
                <label class="ml-1 mt-1 font-weight-bold fs-8pt">m</label>
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Depth:</label>
                <div class="form-group ml-2" style="width: 70px">
                    <input type="text" class="form-control" name="dimensions_depth" value="{{ $equipment->equipmentModel->dimensions_depth ?? '' }}">
                </div>
                <label class="ml-1 mt-1 font-weight-bold fs-8pt">m</label>
        </div>
    </div>

    @include('shineCompliance.forms.form_input_small',['title' => 'Capacity:', 'name' => 'capacity', 'data' => $equipment->equipmentModel->capacity ?? '' ,'measurement' => 'ltrs','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_input_small',['title' => 'Volume of Stored Water:', 'name' => 'stored_water', 'data' => $equipment->equipmentModel->stored_water ?? '' ,'measurement' => 'ltrs','class_other' => 'equipment_section'])

</div>