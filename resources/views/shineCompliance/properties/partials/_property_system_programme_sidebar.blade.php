<div class="column-left">
    <div  class="card-data mar-up">
        <div style="width:100%;" >
            <ul class="list-group">
                <div class="list-group-img">
                    <img src="{{ $image ?? '' }}"  width="100%" height="230px" alt="">
                </div>
                <div class="list-group-button">
                    @if(isset($display_type) && $display_type == EQUIPMENT_TYPE)
                    <button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button>
                    <button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button>
                @else
                    <button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button>
                    <button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button>
                    <button class="list-group-btn"><i class="fa fa-cubes fa-2x"></i></button>
                    <button class="list-group-btn"><i class="fa fa-qrcode fa-2x"></i></button>
                @endif
                </div>
                <a href="{{ route($route ?? 'shineCompliance.programme.detail', ['id' => $id ?? 0]) }}" class="list-group-item {{ request()->route()->getName() == ($route ??'shineCompliance.programme.detail') ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Details</a>

                @if(isset($display_type) && $display_type == EQUIPMENT_TYPE)
                    <a href="{{ route('shineCompliance.equipment.temperature_ph', ['id' => $id ?? 0]) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.equipment.temperature_ph' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Temperature & PH</a>
                    <a href="{{ route('shineCompliance.photography_equipment.detail', ['id' => $id ?? 0]) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.photography_equipment.detail' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Photography</a>
                    <a href="{{ route('shineCompliance.equipment.hazards', ['id' => $id ?? 0]) }}" class="list-group-item {{ isset($active_summary) && $active_summary ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Hazards</a>
                    <a href="{{ route('shineCompliance.equipment.nonconformities', ['id' => $id ?? 0]) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.equipment.nonconformities' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Non-conformities</a>
                    <a href="{{ route('shineCompliance.equipment.pre_planned_maintenance', ['id' => $id ?? 0]) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.equipment.pre_planned_maintenance' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Pre-planned Maintenance</a>
                @endif
                <a href="{{$route_document ?? 'javascript:void(0)'}}" class="list-group-item list-group-item-action
                        {{ (strpos(request()->route()->getName(), 'shineCompliance.system.document.list') !== false ||
                           strpos(request()->route()->getName(), 'shineCompliance.equipment.document.list') !== false ||
                           strpos(request()->route()->getName(), 'shineCompliance.programme.document.list') !== false)  ? 'list-group-active' : 'list-group-item-danger'}}
                    list-group-details">Documents</a>
            </ul>
        </div>
    </div>
</div>
