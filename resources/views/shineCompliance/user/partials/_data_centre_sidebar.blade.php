<div class="col-md-3 pl-0">
    <ul class="list-group {{ (Route::currentRouteName() == 'shineCompliance.profile-shineCompliance') ? '' : 'mt-3'}}">
        <img src={{  asset(\ComplianceHelpers::getFileImage($data->id, AVATAR))}} alt="">
        <a href="{{ route('shineCompliance.home_shineCompliance') }}" class="list-group-item list-group-item-action list-group-details {{ (Route::currentRouteName() == 'shineCompliance.home_shineCompliance') ? 'list-group-active' : 'list-group-item-danger'}} " style="margin-top: 1px">Dashboard</a>
        <a href="{{ route('shineCompliance.profile-shineCompliance',['id' => \Auth::user()->id]) }}" class="list-group-item list-group-item-action list-group-details {{ (Route::currentRouteName() == 'shineCompliance.profile-shineCompliance') ? 'list-group-active' : 'list-group-item-danger'}} ">Details</a>
{{--        <button href="#" class="list-group-item list-group-item-action list-group-item-danger list-group-details" disabled>Notifications</button>--}}
        <a href="{{ route('shineCompliance.user.project') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.user.project' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Projects</a>
        <a href="{{ route('shineCompliance.assessment_user') }}" class="list-group-item  list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.assessment_user' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Assessments</a>
        @if(\CompliancePrivilege::getDataCentrePrivilege('certificate'))
            <a href="{{ route('shineCompliance.user.certificate') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.user.certificate' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Certificates</a>
        @endif
        @if(\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkPermission(JR_RESOURCES,JR_INCIDENT_REPORT))
            <a href="{{ route('shineCompliance.user.incident_report') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.user.incident_report' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details">Incident Reporting</a>
        @endif
        @if(\CompliancePrivilege::getDataCentrePrivilege('survey'))
            <a href="{{ route('shineCompliance.user.survey') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.user.survey' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Surveys</a>
        @endif
        @if((\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_WORK_REQUEST) and \CommonHelpers::isSystemClient()) || (\Auth::user()->client_id == 40))
            <a href="{{ route('shineCompliance.user.work_requests') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.user.work_requests' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Work Requests</a>
        @endif
{{--        <button href="#" class="list-group-item list-group-item-action list-group-item-danger list-group-details" disabled>Settings</button>--}}
        @if(\CompliancePrivilege::getDataCentrePrivilege('approval'))
            <a href="{{ route('shineCompliance.user.approval') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.user.approval' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Approval</a>
        @endif
        @if(\CompliancePrivilege::getDataCentrePrivilege('rejected'))
            <a href="{{ route('shineCompliance.user.rejected') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.user.rejected' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Rejected</a>
        @endif
    </ul>
</div>
