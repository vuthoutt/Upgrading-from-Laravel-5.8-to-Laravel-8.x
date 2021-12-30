@if(\CompliancePrivilege::checkPermission(JR_REPORTING, JR_PREPLAN_SUMMARY_FIRE,JOB_ROLE_FIRE) and \CommonHelpers::isSystemClient())
    <a href="{{ route('shineCompliance.fire_summary') }}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'shineCompliance.fire_summary' ? 'active' : '' }}">
        Fire Documents
    </a>
    <a href="{{ route('shineCompliance.fire_summary.fire_hazard') }}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'shineCompliance.fire_summary.fire_hazard' ? 'active' : '' }}">
        Fire Hazard AR Summary
    </a>
    <a href="{{ route('shineCompliance.fire_summary.fire_assessment_hazard') }}" class="list-group-item list-group-item-action bg-light nav-link {{ \Route::currentRouteName() == 'shineCompliance.fire_summary.fire_assessment_hazard' ? 'active' : '' }}">
        Fire Assessment Summary
    </a>
@endif
