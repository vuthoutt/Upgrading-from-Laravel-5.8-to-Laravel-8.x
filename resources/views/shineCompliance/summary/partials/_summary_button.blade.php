<div class="row mr-bt-top">
    <div class="full-width">
        <div class="form-button-search" >
{{--             <a href="{{ route('shineCompliance.general_summary') }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button overall">
                    <strong>{{ __('General') }}</strong>
                </button>
            </a> --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::getAvailableSummary())
            @else
            <a href="{{ route(\CompliancePrivilege::getAvailableSummary()) }}" style="text-decoration: none">
                <button type="submit" class="fs-8pt btn shine-compliance-button {{ isset($type) ?  :  ASBESTOS }}" >
                    <strong>{{ __('Asbestos') }}</strong>
                </button>
            </a>
            @endif
            @if(!\CompliancePrivilege::checkPermission(JR_REPORTING, JR_PREPLAN_SUMMARY_FIRE,JOB_ROLE_FIRE) and \CommonHelpers::isSystemClient())
            @else
                <a href="{{ route('shineCompliance.fire_summary') }}" style="text-decoration: none">
                    <button type="submit" class="fs-8pt btn shine-compliance-button {{ isset($type) ? ($type == FIRE ?  FIRE : ''): '' }}">
                        <strong>{{ __('Fire') }}</strong>
                    </button>
                </a>
            @endif
            @if(env('WATER_MODULE'))
                @if(!\CompliancePrivilege::checkPermission(JR_REPORTING, JR_PREPLAN_SUMMARY_WATER,JOB_ROLE_WATER) and \CommonHelpers::isSystemClient())
                @else
                <a href="{{ route('shineCompliance.water_summary') }}" style="text-decoration: none">
                    <button type="submit" class="fs-8pt btn shine-compliance-button {{ isset($type) ?  ($type == WATER ?  WATER : '') : '' }}" style="margin-right: 0px!important;">
                        <strong>{{ __('Water') }}</strong>
                    </button>
                </a>
                @endif
            @endif
        </div>
    </div>
</div>
