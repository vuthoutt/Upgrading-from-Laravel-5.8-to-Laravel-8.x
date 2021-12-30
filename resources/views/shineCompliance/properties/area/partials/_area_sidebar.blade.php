<div class="column-left">
    <div  class="card-data mar-up">
        <div style="width:300px; " >
            <ul class="list-group">
                <div class="list-group-img">
                    <img src="{{ \ComplianceHelpers::getSystemFile($area->id,AREA_IMAGE) }}"  width="100%" height="230px" alt="">
                </div>
                <div class="list-group-button">
{{--                    <button class="list-group-btn" style="margin-left:0px" ><i class="fa fa-image fa-2x" title="Open"></i></button>--}}
{{--                    <button class="list-group-btn"><i class="fa fa-download fa-2x" title="Download"></i></button>--}}
{{--                    <button class="list-group-btn"><i class="fa fa-cubes fa-2x"></i></button>--}}
{{--                    <button class="list-group-btn"><i class="fa fa-qrcode fa-2x"></i></button>--}}
                </div>
                <a href="{{ route('shineCompliance.property.area_detail',['property_id' => $property_id ?? 0, 'area_id' =>$area_id ?? 0]) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.property.area_detail' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Details</a>
                <a href="{{ route('shineCompliance.location.list', ['area_id' =>$area_id ?? 0, 'status' => 0]) }}" class="list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.location.list') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Location</a>
            </ul>
        </div>
    </div>
</div>
