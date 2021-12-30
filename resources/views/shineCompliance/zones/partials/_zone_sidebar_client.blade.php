<div class="col-md-3 pl-0">
    <div  class="card-data mar-up">
        <div style="width:100%;" >
            <ul class="list-group">
                <div class="list-group-img">
                    <img src="{{ (\CommonHelpers::getFile(1, CLIENT_LOGO)) }}" width="100%" height="230px" alt="" style="object-fit: contain;">
                </div>
                <div class="list-group-button">
                    <a href="{{ route('retrive_image',['type'=>  CLIENT_LOGO ,'id'=> 1, 'is_view' => true ]) }}" target="_blank"><button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button></a>
                    <a href="{{ route('retrive_image',['type'=>  CLIENT_LOGO ,'id'=> 1 ]) }}"><button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
                </div>
                <a href="{{ route('zone_map') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.zone' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Details</a>
                @if(\CompliancePrivilege::checkRegisterDataPermission())
                    <a href="{{ route('shineCompliance.all_zone.register') }}" class="list-group-item list-group-item-action {{ isset($active_summary) ? 'list-group-active' : 'list-group-item-danger' }} list-group-details">Register</a>
                @endif
            </ul>
        </div>
    </div>
</div>
