<div class="col-md-3 pl-0">
    <div  class="card-data mar-up">
        <div style="width:100%;" >
            <ul class="list-group">
                <div class="list-group-img">
                    <img src="{{ (\CommonHelpers::getFile($zone->id, ZONE_PHOTO)) }}" width="100%" height="230px" alt="" style="object-fit: contain;">
                </div>
                <div class="list-group-button">
                    <a href="{{ route('retrive_image',['type'=>  ZONE_PHOTO ,'id'=> $zone->id ?? 0, 'is_view' => true ]) }}" target="_blank"><button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button></a>
                    <a href="{{ route('retrive_image',['type'=>  ZONE_PHOTO ,'id'=> $zone->id ?? 0]) }}"><button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
                </div>
                <a href="{{route('shineCompliance.zone.zone_details', ['id' => $zone->id ?? 0])}}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.zone.zone_details' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Details</a>

                @if(\CompliancePrivilege::checkRegisterDataPermission())
                    <a href="{{ route('shineCompliance.zone.register', $zone->id ?? 0) }}" class="list-group-item list-group-item-action {{ isset($active_summary) ? 'list-group-active' : 'list-group-item-danger' }} list-group-details">Register</a>
                @endif
                <a href="{{route('shineCompliance.zone.details', ['id' => $zone->id ?? 0])}}" class="list-group-item {{ Route::currentRouteName() == 'shineCompliance.zone.details' ? 'list-group-active' : 'list-group-item-danger'}} list-group-item-action list-group-details border-unset" disabled>Properties</a>
            </ul>
        </div>
    </div>
</div>
