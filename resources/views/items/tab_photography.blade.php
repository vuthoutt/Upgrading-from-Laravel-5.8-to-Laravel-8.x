<div class="row offset-top40">
    <div class="row col-md-3 mr-4">
        <div style="max-height: 450px;">
            <div class="col-md-12 client-image-show mb-3">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Location:</label>
            </div>
            <div class="col-md-12 client-image-show mb-3">
                <img class="image-item" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_LOCATION) }}">
            </div>
        </div>
        <div class="col-md-12 client-image-show mb-3">
            <a title="Download Item Location Image" href="{{ route('retrive_image',['type'=>  ITEM_PHOTO_LOCATION ,'id'=> $item->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
    </div>
    <div class="row col-md-3 mr-4">
        <div style="max-height: 450px;">
            <div class="col-md-12 client-image-show mb-3">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Item:</label>
            </div>
             <div class="col-md-12 client-image-show mb-3">
                <img class="image-item" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO) }}">
            </div>
        </div>
        <div class="col-md-12 client-image-show mb-3">
            <a title="Download Asbestos Item Image" href="{{ route('retrive_image',['type'=>  ITEM_PHOTO ,'id'=> $item->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
    </div>
    <div class="row col-md-3">
        @if(\CommonHelpers::checkFile($item->id, ITEM_PHOTO_ADDITIONAL))
        <div style="max-height: 450px;">
            <div class="col-md-12 client-image-show mb-3">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Additional:</label>
            </div>
            <div class="col-md-12 client-image-show mb-3">
                    <img class="image-item" src="{{ CommonHelpers::getFile($item->id, ITEM_PHOTO_ADDITIONAL) }}">
            </div>
        </div>
        <div class="col-md-12 client-image-show mb-3">
                <a title="Download Item Additional Image" href="{{ route('retrive_image',['type'=>  ITEM_PHOTO_ADDITIONAL ,'id'=> $item->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
        @endif
    </div>
</div>