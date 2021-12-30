<div class="row offset-top40">
    <div class="col-md-2 mr-5">
        @include('shineCompliance.forms.form_photo_equipment',['title' => 'Location:', 'name' => 'photoLocation', 'object_id' => $equipment->id ?? 0, 'folder' => EQUIPMENT_LOCATION_PHOTO])
    </div>
    <div class="col-md-2 mr-5">
        @include('shineCompliance.forms.form_photo_equipment',['title' => 'Equipment:', 'name' => 'photoEquipment', 'object_id' => $equipment->id ?? 0, 'folder' => EQUIPMENT_PHOTO])
    </div>
    <div class="col-md-2">
        @include('shineCompliance.forms.form_photo_equipment',['title' => 'Additional:', 'name' => 'photoAdditional', 'object_id' => $equipment->id ?? 0, 'folder' => EQUIPMENT_ADDITION_PHOTO])
    </div>
</div>
