<div class="row offset-top40">
    <div class="row col-md-3 mr-4">
        <div style="max-height: 450px;">
            <div class="col-md-12 client-image-show mb-3">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Location:</label>
            </div>
            <div class="col-md-12 client-image-show mb-3">
                <img class="image-item" src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_LOCATION_PHOTO) }}">
            </div>
        </div>
        <div class="col-md-12 client-image-show mb-3">
            <a title="Download Item Location Image" href="{{ route('shineCompliance.retrive_image',['type'=>  EQUIPMENT_LOCATION_PHOTO ,'id'=> $equipment->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
    </div>
    <div class="row col-md-3 mr-4">
        <div style="max-height: 450px;">
            <div class="col-md-12 client-image-show mb-3">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Equipment:</label>
            </div>
             <div class="col-md-12 client-image-show mb-3">
                <img class="image-item" src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO) }}">
            </div>
        </div>
        <div class="col-md-12 client-image-show mb-3">
            <a title="Download Asbestos Item Image" href="{{ route('shineCompliance.retrive_image',['type'=>  EQUIPMENT_PHOTO ,'id'=> $equipment->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
    </div>
    <div class="row col-md-3">
        @if(\ComplianceHelpers::checkFile($equipment->id, EQUIPMENT_ADDITION_PHOTO))
        <div style="max-height: 450px;">
            <div class="col-md-12 client-image-show mb-3">
                <label class="col-md-3 col-form-label text-md-left font-weight-bold" >Additional:</label>
            </div>
            <div class="col-md-12 client-image-show mb-3">
                    <img class="image-item" src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_ADDITION_PHOTO) }}">
            </div>
        </div>
        <div class="col-md-12 client-image-show mb-3">
                <a title="Download Item Additional Image" href="{{ route('shineCompliance.retrive_image',['type'=>  EQUIPMENT_ADDITION_PHOTO ,'id'=> $equipment->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
        @endif
    </div>
</div>
