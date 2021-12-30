<div class="row mr-bt-top">
    <div class="full-width">
        <div class="form-button-left" >
            <a href="{{ $backRoute  }}" style="text-decoration: none">
                <button type="submit" class="btn shine-compliance-button fs-8pt">
                    <strong>{{ __('Back') }}</strong>
                </button>
            </a>
        </div>
        <div class="form-button-search" >
            @if($overall)
            <a href="{{ route('shineCompliance.all_zone.register',['client_id' => $client_id]) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ is_null($type) ? 'overall' : '' }}">
                    <strong>{{ __('Overall') }}</strong>
                </button>
            </a>
            @endif
            @if($asbestos)
            <a href="{{ route('shineCompliance.all_zone.asbestos',['client_id' => $client_id]) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == ASBESTOS ?  ASBESTOS : '' }}" >
                    <strong>{{ __('Asbestos') }}</strong>
                </button>
            </a>
            @endif
            @if($fire)
            <a href="{{ route('shineCompliance.all_zone.fire',['client_id' => $client_id]) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == FIRE ?  FIRE : '' }}">
                    <strong>{{ __('Fire') }}</strong>
                </button>
            </a>
            @endif
{{--             <a href="{{ route('shineCompliance.all_zone.gas') }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == GAS ?  GAS : '' }}">
                    <strong>{{ __('Gas') }}</strong>
                </button>
            </a> --}}
            @if($water)
            <a href="{{ route('shineCompliance.all_zone.water',['client_id' => $client_id]) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ $type == WATER ?  WATER : '' }}" style="margin-right: 0px!important;">
                    <strong>{{ __('Water') }}</strong>
                </button>
            </a>
            @endif
        </div>
    </div>
</div>
