<div class="col-md-3 pl-0">
    <div  class="card-data mar-up">
        <div style="width:100%;" >
            <ul class="list-group">
                <div class="list-group-img" style="text-align: center;">
                    <img src="{{ CommonHelpers::getFile(1, CLIENT_LOGO) }}" style="object-fit: contain;"  height="230px" alt="">
                </div>
                @if(\CompliancePrivilege::getDataCentrePrivilege('dashboard') and \CommonHelpers::isSystemClient())
                    <a href="{{ route('shineCompliance.data_centre.dashboard') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.dashboard' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Dashboard</a>
                @endif
                @if(\CompliancePrivilege::getDataCentrePrivilege('project'))
                    <a href="{{ route('shineCompliance.data_centre.projects') }}{{ \CompliancePrivilege::getActiveTabDataCentreProject() }}" class="list-group-item list-group-item-action  {{ Route::currentRouteName() == 'shineCompliance.data_centre.projects' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Projects</a>
                @endif
                @if(\CompliancePrivilege::getDataCentrePrivilege('assessment'))
                    <a href="{{ route('shineCompliance.data_centre.assessment') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.assessment' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Assessments</a>
                @endif

                @if(\CompliancePrivilege::getDataCentrePrivilege('certificate'))
                    <a href="{{ route('shineCompliance.data_centre.certificate') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.certificate' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Certificates</a>
                @endif

                @if(\CompliancePrivilege::getDataCentrePrivilege('survey'))
                    <a href="{{ route('shineCompliance.data_centre.surveys') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.surveys' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Surveys</a>
                @endif

                @if(\CompliancePrivilege::getDataCentrePrivilege('critical'))
                    <a href="{{ route('shineCompliance.data_centre.critical') }}" class="list-group-item list-group-item-action  {{ Route::currentRouteName() == 'shineCompliance.data_centre.critical' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Critical</a>
                @endif
                @if(\CompliancePrivilege::getDataCentrePrivilege('urgent'))
                    <a href="{{ route('shineCompliance.data_centre.urgent') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.urgent' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Urgent</a>
                @endif
                @if(\CompliancePrivilege::getDataCentrePrivilege('important'))
                    <a href="{{ route('shineCompliance.data_centre.important') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.important' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Important</a>
                @endif
                @if(\CompliancePrivilege::getDataCentrePrivilege('attention'))
                    <a href="{{ route('shineCompliance.data_centre.attention') }}" class="list-group-item list-group-item-action  {{ Route::currentRouteName() == 'shineCompliance.data_centre.attention' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Attention</a>
                @endif
                @if(\CompliancePrivilege::getDataCentrePrivilege('deadline'))
                    <a href="{{ route('shineCompliance.data_centre.deadline') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.deadline' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Deadline</a>
                @endif
                @if(\CompliancePrivilege::getDataCentrePrivilege('approval'))
                    <a href="{{ route('shineCompliance.data_centre.approval') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.approval' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Approval</a>
                @endif
                @if(\CompliancePrivilege::getDataCentrePrivilege('rejected'))
                    <a href="{{ route('shineCompliance.data_centre.rejected') }}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.data_centre.rejected' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Rejected</a>
                @endif
            </ul>
        </div>
    </div>
</div>
