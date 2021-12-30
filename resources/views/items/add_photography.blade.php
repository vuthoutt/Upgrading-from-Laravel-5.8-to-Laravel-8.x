<div class="row offset-top40">
    <div class="col-md-2 mr-5">
        @include('forms.form_photo_item',['title' => 'Location:', 'name' => 'photoLocation'])
    </div>
    <div class="col-md-2 mr-5">
        @include('forms.form_photo_item',['title' => 'Item:', 'name' => 'photoItem'])
    </div>
    <div class="col-md-2">
        @include('forms.form_photo_item',['title' => 'Additional:', 'name' => 'photoAdditional'])
    </div>
</div>