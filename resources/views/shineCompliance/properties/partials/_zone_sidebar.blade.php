<div class="col-md-3 pl-0">
    <div  class="card-data mar-up">
        <div style="width:100%; " >
            <ul class="list-group">
                <div class="list-group-img">
                    <img src="{{ asset('img/client_logo_padding.png') }}">
                </div>
                <div class="list-group-button">
                    <a href="{{ route('retrive_image',['type'=>  CLIENT_LOGO ,'id'=> $client->id, 'is_view' => true ]) }}" target="_blank"><button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button></a>
                    <a href="{{ route('retrive_image',['type'=>  CLIENT_LOGO ,'id'=> $client->id ]) }}"><button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
{{--                    <button class="list-group-btn"><i class="fa fa-cubes fa-2x"></i></button>--}}
{{--                    <button class="list-group-btn"><i class="fa fa-qrcode fa-2x"></i></button>--}}
                </div>
                <a href="{{ route('shineCompliance.zone', ['client_id' => 1]) }}" class="list-group-item {{ request()->route()->getName() == ($current ?? '') ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Details</a>
                @if(\CompliancePrivilege::checkRegisterDataPermission())
                    <a href="{{ route('shineCompliance.all_zone.register', ['client_id' => 1]) }}" class="list-group-item list-group-item-action {{ isset($active_summary) ? 'list-group-active' : 'list-group-item-danger' }} list-group-details">Register</a>
                @endif
            </ul>
        </div>
    </div>
</div>
