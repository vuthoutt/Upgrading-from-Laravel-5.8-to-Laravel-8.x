<div class="offset-top40">
    @include('shineCompliance.forms.form_text',['title' => 'Manufacturer:', 'data' => $equipment->equipmentModel->manufacturer ?? '', 'id' => 'manufacturer','class_other' => 'equipment_section' ])
    @include('shineCompliance.forms.form_text',['title' => 'Model:', 'data' => $equipment->equipmentModel->model ?? '' , 'id' => 'model','class_other' => 'equipment_section'])
    <div class="row register-form equipment_section" id="dimensions-form">
        <label  class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">
            Dimensions:
        </label>
        <div class="col-md-9 row">
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Length:</label>
                <span class="fs-8pt ml-2 mr-1 mt-1">{{ $equipment->equipmentModel->dimensions_length ?? '' }}</span>
                <label class="mt-1 fs-8pt">m</label>
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Width:</label>
                <span class="fs-8pt ml-2 mr-1 mt-1">{{ $equipment->equipmentModel->dimensions_width ?? '' }}</span>
                <label class="mt-1 fs-8pt">m</label>
                <label class="ml-3 mt-1 font-weight-bold fs-8pt">Depth:</label>
                <span class=" fs-8pt ml-2 mr-1 mt-1">{{ $equipment->equipmentModel->dimensions_depth ?? '' }}</span>
                <label class="mt-1 fs-8pt">m</label>
        </div>
    </div>
    @include('shineCompliance.forms.form_text_small',['title' => 'Capacity:', 'data' => $equipment->equipmentModel->capacity ?? '',
                                                        'measurement' => 'ltrs' , 'id' => 'capacity','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Volume of Stored Water:', 'data' => $equipment->equipmentModel->stored_water ?? '',
                                                        'measurement' => 'ltrs', 'id' => 'stored_water','class_other' => 'equipment_section'])
</div>