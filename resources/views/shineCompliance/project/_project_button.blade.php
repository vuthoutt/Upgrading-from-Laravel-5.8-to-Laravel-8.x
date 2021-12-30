<div class="row mr-bt-top">
    <div style="width: 98.80%">
        <div class="form-button-search" >
            <a href="{{ route('shineCompliance.zone.asbestos', [$zone_id]) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == ASBESTOS ?  ASBESTOS : '' }}" >
                    <strong>{{ __('Asbestos') }}</strong>
                </button>
            </a>
            <a href="{{ route('shineCompliance.zone.fire', [$zone_id]) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == FIRE ?  FIRE : '' }}">
                    <strong>{{ __('Fire') }}</strong>
                </button>
            </a>
            <a href="{{ route('shineCompliance.zone.gas', [$zone_id]) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == GAS ?  GAS : '' }}">
                    <strong>{{ __('Gas') }}</strong>
                </button>
            </a>
            <a href="{{ route('shineCompliance.zone.water', [$zone_id]) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == WATER ?  WATER : '' }}" style="margin-right: 0px!important;">
                    <strong>{{ __('Water') }}</strong>
                </button>
            </a>
        </div>
    </div>
</div>
