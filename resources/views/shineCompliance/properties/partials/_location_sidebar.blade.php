<div class="col-md-3 pl-0">
    <div  class="card-data mar-up">
        <div style="width:100%;" >
            <ul class="list-group">
                <div class="list-group-img">
                    <img src="{{ $image ?? asset('img/item5.jpg') }}"  width="100%" height="230px" alt="">
                </div>
                <div class="list-group-button">
                    <a href="{{ route('retrive_image',['type'=>  LOCATION_IMAGE ,'id'=> $location_id ?? 0, 'is_view' => true ]) }}" target="_blank">
                        <button class="list-group-btn" style="margin-left:0px"  title="Open"><i class="fa fa-image fa-2x"></i></button>
                    </a>
                    <a href="{{ route('retrive_image',['type'=>  LOCATION_IMAGE ,'id'=> $location_id ?? 0]) }}">
                        <button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button>
                    </a>
                </div>
                <a href="{{ route('shineCompliance.location.detail', $location_id ?? 0) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.location.detail' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Details</a>
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_REGISTER_ASBESTOS, JOB_ROLE_ASBESTOS))
                @else
                    <a href="{{ route('shineCompliance.item.list', $location_id ?? 0) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.item.list' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Items</a>
                @endif
            </ul>
        </div>
    </div>
</div>
