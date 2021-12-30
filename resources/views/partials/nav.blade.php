<div class="row nav-com" style="position: fixed;z-index: 1000;font-size: 14px!important;width: 101%">
    <div class="{{ isset($color) ?  CommonHelpers::getComplianceNavColor($color) : 'red_color' }}" style="width: 100%">
        <div class="container-cus" style="padding-right: 0px">
            <nav class="navbar navbar-expand-xl navbar-light pl-0 pr-0" id="navigation" >
                <!-- Brand -->
                <a class="navbar-brand" href="{{ route('shineCompliance.home_shineCompliance') }}"><strong>shine</strong>Compliance</a>
                <!-- Links -->
                <button class="btn btn-link bd-search-docs-toggle d-xl-none p-0 ml-3 collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto" style="font-size: small">
                        @if(\Auth::user()->is_site_operative == 0)
                            <li class="nav-item">
                                <a class="nav-link fs-8pt" href="{{ route('shineCompliance.home_shineCompliance') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                @if(env('SETTING_PROPERTY_MAP'))
                                    <a class="nav-link" href="{{route('zone_map', ['client_id' => 1])}}">Properties</a>
                                @else
                                    <a class="nav-link" href="{{route('shineCompliance.zone', ['client_id' => 1])}}">Properties</a>
                                @endif
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('zone', ['client_id' => 1])}}">Home</a>
                            </li>
                        @endif

                    <!-- Dropdown -->
                        @if(\CompliancePrivilege::getDataCentreDisplayPrivs())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fs-8pt" href="" id="navbardrop" data-toggle="dropdown">
                                    Data Centre
                                </a>
                                <div class="dropdown-menu dropdown-menu-tip-nw">
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('dashboard'))
                                        <a href="{{ route('shineCompliance.data_centre.dashboard') }}" class="dropdown-item fs-8pt" >Dashboard</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('project'))
                                        <a href="{{ route('shineCompliance.data_centre.projects') }}{{ \CompliancePrivilege::getActiveTabDataCentreProject() }}" class="dropdown-item fs-8pt" >Projects</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('assessment'))
                                        <a href="{{ route('shineCompliance.data_centre.assessment') }}" class="dropdown-item fs-8pt" >Assessments</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('certificate'))
                                        <a href="{{ route('shineCompliance.data_centre.certificate') }}" class="dropdown-item fs-8pt" >Certificates</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('survey'))
                                        <a href="{{ route('shineCompliance.data_centre.surveys') }}" class="dropdown-item fs-8pt" >Surveys</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('critical'))
                                        <a href="{{ route('shineCompliance.data_centre.critical') }}" class="dropdown-item fs-8pt" >Critical</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('urgent'))
                                        <a href="{{ route('shineCompliance.data_centre.urgent') }}" class="dropdown-item fs-8pt" >Urgent</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('important'))
                                        <a href="{{ route('shineCompliance.data_centre.important') }}" class="dropdown-item fs-8pt" >Important</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('attention'))
                                        <a href="{{ route('shineCompliance.data_centre.attention') }}" class="dropdown-item fs-8pt" >Attention</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('deadline'))
                                        <a href="{{ route('shineCompliance.data_centre.deadline') }}" class="dropdown-item fs-8pt" >Deadline</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('approval'))
                                        <a href="{{ route('shineCompliance.data_centre.approval') }}" class="dropdown-item fs-8pt" >Approval</a>
                                    @endif
                                    @if(\CompliancePrivilege::getDataCentrePrivilege('rejected'))
                                        <a href="{{ route('shineCompliance.data_centre.rejected') }}" class="dropdown-item fs-8pt" >Rejected</a>
                                    @endif
                                </div>
                            </li>
                        @endif


                        @if(empty(\CompliancePrivilege::getContractorIdPermission()) and \CommonHelpers::isSystemClient() and empty(\CompliancePrivilege::getContractorIdPermission('contractor')))
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle disabled_a" href="#" id="navbardrop" data-toggle="dropdown">
                                    Organisation
                                </a>
                                <div class="dropdown-menu dropdown-menu-tip-nw">
                                    @if(empty(\CompliancePrivilege::getContractorIdPermission()) and \CommonHelpers::isSystemClient())
                                    @else
                                        <a class="dropdown-item" href="{{ route('my_organisation', ['client_id' => \Auth::user()->client_id]) }}">My Organisation</a>
                                    @endif
                                    @if(\CommonHelpers::isSystemClient())
                                        @if(!empty(\CompliancePrivilege::getContractorIdPermission('contractor')))
                                            <a class="dropdown-item fs-8pt" href="{{ route('contractor.clients') }}">Contractors</a>
                                        @endif
                                    @else
                                        <a class="dropdown-item fs-8pt" href="{{ route('contractor', ['client_id' => 1]) }}">System Owner</a>
                                    @endif
                                </div>
                            </li>
                        @endif

                        @if(!\CompliancePrivilege::checkPermission(JR_RESOURCES, JR_AUDIT_TRAIL) and
                        !\CompliancePrivilege::checkPermission(JR_RESOURCES, JR_APP_AUDIT_TRAIL) and
                        \CommonHelpers::isSystemClient() and !\CompliancePrivilege::getAvailableSummary()
                        and !\CompliancePrivilege::checkPermission(JR_REPORTING, JR_PREPLAN_SUMMARY_FIRE,JOB_ROLE_FIRE)
                        and !\CompliancePrivilege::checkPermission(JR_REPORTING, JR_PREPLAN_SUMMARY_WATER,JOB_ROLE_WATER))

                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle fs-8pt" href="#" id="navbardrop" data-toggle="dropdown">
                                    Reporting
                                </a>
                                <div class="dropdown-menu dropdown-menu-tip-nw">
                                    @if(\CompliancePrivilege::getAvailableSummary())
                                        <a class="dropdown-item fs-8pt" href="{{ route(\CompliancePrivilege::getAvailableSummary()) }}">Summary</a>
                                    @endif
                                    @if(\CompliancePrivilege::checkPermission(JR_RESOURCES, JR_AUDIT_TRAIL) and \CommonHelpers::isSystemClient())
                                        <a class="dropdown-item fs-8pt" href="{{ route('shineCompliance.audit_trail') }}">Audit Trail</a>
                                    @endif
                                    @if(\CompliancePrivilege::checkPermission(JR_RESOURCES, JR_APP_AUDIT_TRAIL) and \CommonHelpers::isSystemClient())
                                        <a class="dropdown-item fs-8pt" href="{{ route('app_audit_trail') }}">App Audit Trail</a>
                                    @endif
                                </div>
                            </li>
                        @endif

                        @if(\CommonHelpers::isSystemClient() and (\Auth::user()->role != SUPER_USER_ROLE)
                            and !\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_E_LEARNING) and !\CompliancePrivilege::checkPermission(JR_RESOURCES,CATEGORY_BOX_VIEW_PRIV)
                            and !\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_ADMINISTRATOR_FUNCTIONS) and !\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_PRE_SURVEY_PLAN)
                            and !\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_DEPARTMENT_LIST)
                        )
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                    Resources
                                </a>
                                <div class="dropdown-menu dropdown-menu-tip-nw">

                                    {{-- <a class="dropdown-item fs-8pt" href="{{ route('shineCompliance.admin_tool.upload') }}">Administrator Functions</a> --}}
                                    @if(!\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_E_LEARNING) and \CommonHelpers::isSystemClient())
                                    @else
                                        <a class="dropdown-item" href="{{ route('e_learning') }}">E-Learning</a>
                                    @endif
                                    @if(\CommonHelpers::isSystemClient() and \Auth::user()->role == SUPER_USER_ROLE)
                                        <a class="dropdown-item fs-8pt" href="{{ route('shineCompliance.list_job_role.compliance') }}">Job Roles</a>
                                    @endif
                                    @if(!\CompliancePrivilege::checkPermission(JR_RESOURCES,CATEGORY_BOX_VIEW_PRIV) and \CommonHelpers::isSystemClient())
                                    @else
                                        <a class="dropdown-item fs-8pt" href="{{ route('resource_document') }}">Resource Documents</a>
                                    @endif
                                    @if((\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_WORK_REQUEST) and \CommonHelpers::isSystemClient()) || (\Auth::user()->client_id == 40))
                                        <a class="dropdown-item" href="{{ route('wr.get_list') }}">Work Requests</a>
                                    @endif
                                    @if(\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkPermission(JR_RESOURCES,JR_ADMINISTRATOR_FUNCTIONS))
                                        <a class="dropdown-item fs-8pt" href="{{ route('toolbox.remove') }}">Administrator Tool Box</a>
                                    @endif
                                    @if(\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkPermission(JR_RESOURCES,JR_ADMINISTRATOR_FUNCTIONS))
                                        <a class="dropdown-item fs-8pt" href="{{ route('toolboxlog') }}">Administrator Tool Box Log</a>
                                    @endif
                                    @if(\CompliancePrivilege::checkPermission(JR_RESOURCES,JR_DEPARTMENT_LIST) and \CommonHelpers::isSystemClient())
                                        <a class="dropdown-item" href="{{ route('department_list') }}">Department List</a>
                                    @endif
                                    @if(\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkPermission(JR_RESOURCES,JR_INCIDENT_REPORT))
                                        <a class="dropdown-item" href="{{ route('incident_reports') }}">Incident Reporting</a>
                                    @endif
                                </div>
                            </li>
                        @endif

                    </ul>
                    <ul  class="navbar-nav ml-auto">
                        <form class="form-inline my-2 my-lg-0">
                            <div class="input-group">
                                @if(\CommonHelpers::isSystemClient() and \CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_INCIDENT_REPORT_EDIT))
                                    <a title="Incident Reporting" href="{{ route('shineCompliance.incident_reporting.get_add') }}">
                                        <img width="13px" src="{{asset('img/plus-square-solid.png')}}" style="margin-top: 8px;margin-right: 10px">
                                    </a>
                                @endif
                                @if((\CompliancePrivilege::checkUpdatePermission(JR_RESOURCES,JR_WORK_REQUEST_EDIT) and \CommonHelpers::isSystemClient()) || (\Auth::user()->client_id == 40))
                                    <a id="link-ticket" title="Work Request Form" href="{{ route('wr.get_add') }}">
                                        <img width="13px" src="{{asset('img/work_request.png')}}" style="margin-top: 8px;margin-right: 6px">
                                    </a>
                                @endif
                                <input class="form-control py-2 basicAutoComplete search-item"
                                       data-url="{{ route('search-autocomplete') }}" data-noresults-text="No result"
                                       placeholder="search" type="text" id="" style="border-radius: 0;" autocomplete="off">
                                <span class="input-group-append">
                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle1" type="button" id="dropdownMenu2" data-toggle="dropdown"
                                                    style="font-size: 13px !important;height: 32px !important; border-radius: 0;">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <a id="link-ticket" title="Problem Management Trouble Ticket Form" href="https://app.smartsheet.com/b/form/f048873be6e847a8bb9d66901bd624b9" target="_blank">
                                                <img width="30px" src="{{asset('img/icon_user.png')}}">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right px-2" style="width: 850px; text-align: center">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <ul class="list-unstyled text-left ul-search">
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="area">Area/floor (AF)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="crtAirTestGroup"  >Asbestos Air Test Certificate Groups (CF)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="crtAirTest"  >Asbestos Air Test Certificates (AC)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="DSsurvey"  >Asbestos Demolition Survey (DS)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="MPSsurvey"  >Asbestos Management Survey – Partial (MSP)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="MSsurvey"  >Asbestos Management Surveys (MS)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="FSsurvey"  >Asbestos Refurbishment Surveys (FS)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="item">Asbestos Register Item (IN)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="RRsurvey"  >Asbestos Re-inspection Report (RR)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="airtestcertificate"  >Asbestos Survey Air Test Certificate (AC)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="SPlan"  >Asbestos Survey Plan (SP)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="samplecertificate"  >Asbestos Survey Sample Certificate (SC)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="pblock"  >Block Codes</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="FDdocument"  >Commercial Documents (FD)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="CDocument"  >Completion Documents (CD)</label></li>
                                                            {{--  <li><label><input type="checkbox" class="form-check-input check1" value="ODocument"  >Contractor Doc (OD)</label></li>  --}}
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="DesDocument"  >Design Documents (DD)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="equipment"  >Equipment (EQ)</label></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-4">
                                                        <ul class="list-unstyled text-left ul-search">

                                                            <li><label><input type="checkbox" class="form-check-input check1" value="FHazard" >Fire Hazards (FH)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="Fra" >Fire Risk Assessment (FRA)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="Hsa" >H&S Assessments (HSA) </label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="Hsi" >H&S Investigations (HSI) </label></li>
                                                            @if(\CommonHelpers::isSystemClient())
                                                                <li><label><input type="checkbox" class="form-check-input check1" value="incident"  >Incident Reporting (IR)</label></li>
                                                            @endif
                                                            @if(\CommonHelpers::isSystemClient()|| (\Auth::user()->client_id == 40))
                                                                <li><label><input type="checkbox" class="form-check-input check1" value="majorwork"  >Programmed Work Request (PWR)</label></li>
                                                            @endif
                                                            @if(\CommonHelpers::isSystemClient())
                                                                <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="organisation">Organisation (CO)</label></li>
                                                            @endif
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="PDocument"  >Planning Documents (PD)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="PcdDocument"  >Pre-construction Documents (PCD)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="SDocument"  >Pre-Start Documents  (SD)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="programme"  >Programme (PT)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="project">Project (PR)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="property">Property (PL)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="group">Property Group (PG)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="historical"  >Property Historical Documents (HD)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="PPlan"  >Property Plan (PP)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="propertyReference"  >Property UPRN</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="BSsurvey"  >Sample Survey (BS)</label></li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-3">
                                                        <ul class="list-unstyled text-left ul-search">
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="summaries"  >Register Summaries (SS)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="room">Room/location (RL)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="RDocument"  >Site Record Doc (RD)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="system"  >System (PS)</label></li>
                                                            <li><label><input type="checkbox" class="form-check-input check1" value="TDdocument"  >Tender Doc (TD)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="user"  >User (ID)</label></li>
                                                            <li><label><input type="checkbox" class="fs-8pt form-check-input check1" value="WHazard"  >Water Hazards (WH)</label></li>
                                                            @if(\CommonHelpers::isSystemClient()|| (\Auth::user()->client_id == 40))
                                                                <li><label><input type="checkbox" class="form-check-input check1" value="work">Work Request (WR)</label></li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </div>
                                                <button type="button" id="toogle-filter-checkboxes" class="btn light_grey_gradient mt-2 float-right" value="1">
                                                    <strong>Un-check All</strong>
                                                </button>
                                            </div>
                                        </div>
                                    </span>
                            </div>
                        </form>

                        <!-- Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fs-8pt" href="#" id="navbardrop" data-toggle="dropdown">
                                {{ \Auth::user()->full_name ?? '' }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-tip-nw">
                                <a class="dropdown-item fs-8pt" href="{{ route('shineCompliance.profile-shineCompliance',['id' => \Auth::user()->id]) }}">Profile</a>
                                <a class="dropdown-item fs-8pt" href="/logout">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="{{ isset($color) ? CommonHelpers::getBreadcrumbColor($color) : 'red_alpha50' }}" style="width: 100%;height: 35px">
        <div class="container-cus ">
            @if(isset($breadCrumb))
                @if (isset($data))
                    {{ Breadcrumbs::render($breadCrumb, $data )}}
                @else
                    {{ Breadcrumbs::render($breadCrumb ) }}
                @endif
            @endif
        </div>
    </div>
    @if(session('msg'))
        <div class="container">
            <div class="notification">
                <div class="alert alert-success notify-popup" role="alert">
                    <i class="fa fa-check"></i> &nbsp; &nbsp; {{ session('msg') }}
                </div>
            </div>
        </div>
    @endif

    @if(session('err'))
        <div class="container">
            <div class="notification">
                <div class="alert alert-danger notify-popup" role="alert">
                    <i class="fa fa-warning" style="font-size:16px;color:red"></i> &nbsp; &nbsp; {{ session('err') }}
                </div>
            </div>
        </div>
    @endif

    <a id="back-to-top" href="#" class="btn btn-light btn-lg back-to-top" role="button"><i class="fa fa-chevron-up" aria-hidden="true"></i></a>
</div>

