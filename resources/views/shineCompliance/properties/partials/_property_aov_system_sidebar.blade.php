<div class="col-md-3 pl-0">
    <div  class="card-data mar-up">
        <div style="width:100%; " >
            <ul class="list-group">
                <div class="list-group-img">
                    <img src="{{ $image ?? '' }}"  width="100%" height="230px" alt="">
                </div>
                <div class="list-group-button">
                    <button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button>
                    <button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button>
                    <button class="list-group-btn"><i class="fa fa-cubes fa-2x"></i></button>
                    <button class="list-group-btn"><i class="fa fa-qrcode fa-2x"></i></button>
                </div>
                <a href="{{ route('shineCompliance.systems.detail', ['id' => $system_id ?? 1]) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.systems.detail' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Details</a>
                <a href="{{ route('shineCompliance.programme.list', ['system_id' => $system_id ?? 1]) }}" class="list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.programme.list') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Programmes</a>
                <a href="{{ route('shineCompliance.equipment.list', ['system_id' => $system_id ?? 1]) }}" class="list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.equipment.list') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Equipment</a>
                <a href="#" class="list-group-item list-group-item-action list-group-item-danger list-group-details not-active">Risk</a>
                <a href="#" class="list-group-item list-group-item-action list-group-item-danger list-group-details not-active">Actions</a>
                <a href="{{$route_document ?? 'javascript:void(0)'}}" class="list-group-item list-group-item-action
                        {{ (strpos(request()->route()->getName(), 'shineCompliance.system.document.list') !== false ||
                           strpos(request()->route()->getName(), 'shineCompliance.equipment.document.list') !== false ||
                           strpos(request()->route()->getName(), 'shineCompliance.programme.document.list') !== false)  ? 'list-group-active' : 'list-group-item-danger'}}
                        list-group-details">Documents</a>
            </ul>
        </div>
    </div>
</div>
