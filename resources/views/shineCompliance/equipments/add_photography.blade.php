<div class="row offset-top40">
    <div class="col-md-2 mr-5">
        @if ($is_water_temperature)
            @include('shineCompliance.forms.form_photo_item',['title' => 'Location:', 'name' => 'photoLocation','object_id' => 0,'folder' => EQUIPMENT_LOCATION_PHOTO])
        @else
            @include('shineCompliance.forms.form_photo_item',['title' => 'Location:', 'name' => 'photoLocation' , 'required' => true,'object_id' => 0,'folder' => EQUIPMENT_LOCATION_PHOTO])
        @endif
    </div>
    <div class="col-md-2 mr-5">
        @if ($is_water_temperature)
            @include('shineCompliance.forms.form_photo_item',['title' => 'Equipment:', 'name' => 'photoEquipment','object_id' => 0, 'folder' => EQUIPMENT_PHOTO])
        @else
            @include('shineCompliance.forms.form_photo_item',['title' => 'Equipment:', 'name' => 'photoEquipment' , 'required' => true,'object_id' => 0, 'folder' => EQUIPMENT_PHOTO])
        @endif
    </div>
    <div class="col-md-2">
        @include('shineCompliance.forms.form_photo_item',['title' => 'Additional:', 'name' => 'photoAdditional','object_id' => 0,'folder' => EQUIPMENT_ADDITION_PHOTO])
    </div>
</div>

