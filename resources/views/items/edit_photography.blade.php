<div class="row offset-top40">
    <div class="col-md-2 mr-5">
        @include('forms.form_photo_item',['title' => 'Location:', 'name' => 'photoLocation', 'object_id' => $item->id, 'folder' => ITEM_PHOTO_LOCATION])
    </div>
    <div class="col-md-2 mr-5">
        @include('forms.form_photo_item',['title' => 'Item:', 'name' => 'photoItem', 'object_id' => $item->id, 'folder' => ITEM_PHOTO])
    </div>
    <div class="col-md-2">
        @include('forms.form_photo_item',['title' => 'Additional:', 'name' => 'photoAdditional', 'object_id' => $item->id, 'folder' => ITEM_PHOTO_ADDITIONAL])
    </div>
</div>