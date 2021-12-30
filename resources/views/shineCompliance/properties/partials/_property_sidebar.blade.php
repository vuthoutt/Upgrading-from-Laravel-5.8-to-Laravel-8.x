<div class="col-md-3 pl-0">
    <div  class="card-data mar-up">
        <div style="width:100%; " >
            <ul class="list-group">
                <div class="list-group-img">
                    <img src="{{ ComplianceHelpers::getFileImage($property_id ?? 0, PROPERTY_IMAGE) }}" width="100%" height="230px" alt="">
                </div>
                <div class="list-group-button">
                    <a href="{{ route('retrive_image',['type'=>  PROPERTY_IMAGE ,'id'=> $property_id ?? 0, 'is_view' => true ]) }}" target="_blank"><button class="list-group-btn" style="margin-left:0px"  title="Open"><i class="fa fa-image fa-2x"></i></button></a>
                    <a href="{{ route('retrive_image',['type'=>  PROPERTY_IMAGE ,'id'=> $property_id ?? 0]) }}"><button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
{{--                    <button class="list-group-btn"><i class="fa fa-cubes fa-2x"></i></button>--}}
{{--                    <button class="list-group-btn"><i class="fa fa-qrcode fa-2x"></i></button>--}}
                </div>
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_DETAILS))
                @else
                    <a href="{{ route('shineCompliance.property.property_detail', $property_id ?? 0) }}" class="list-group-item {{ request()->route()->getName() == 'shineCompliance.property.property_detail' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-item-action list-group-details">Details</a>
                @endif
                @if(\CompliancePrivilege::checkRegisterDataPermission())
                    <a href="{{ route('shineCompliance.property.register', $property_id ?? 0) }}" class="list-group-item list-group-item-action {{ isset($active_summary) ? 'list-group-active' : 'list-group-item-danger' }} list-group-details">Register</a>
                @endif
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_SUB_PROPERTY))
                @else
                    <a href="{{ route('shineCompliance.property.sub_property',$property_id ?? 0) }}" class="list-group-item list-group-item-action {{ request()->route()->getName() == 'shineCompliance.property.sub_property' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Sub Property</a>
                @endif

                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_SYSTEMS))
                @else
                    <a href="{{ route('shineCompliance.systems.list', ['property_id' => $property_id ?? 0]) }}" class="list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.systems.list') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Systems</a>
                @endif

                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_EQUIPMENT))
                @else
                    <a href="{{ route('shineCompliance.property.equipment', ['property_id' => $property_id ?? 1]) }}" class="list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.property.equipment') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Equipment</a>
                @endif

                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_AREA_FLOORS))
                @else
                    <a href="{{ route('shineCompliance.property.list_area', ['property_id' => ($property_id ?? 0),'status' => 0]) }}" class="list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.property.list_area') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Area/floors</a>
                @endif

                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_FIRE_EXITS_ASSEMBLY_POINTS))
                @else
                    <a href="{{ route('shineCompliance.property.fireExit', $property_id ?? 0) }}" class="not-active list-group-item list-group-item-action {{ request()->route()->getName() == 'shineCompliance.property.fireExit' ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Fire Exits & Assembly Points</a>
                @endif

                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_VEHICLE_PARKING))
                @else
                    <a href="{{ route('shineCompliance.property.parking', $property_id ?? 0) }}" class="not-active list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.property.parking') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Vehicle Parking</a>
                @endif

                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_ASBESTOS, JOB_ROLE_ASBESTOS) and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_FIRE, JOB_ROLE_FIRE) and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_PROJECT_WATER, JOB_ROLE_WATER))
                @else
                    <a href="{{ route('shineCompliance.project.project_list',['property_id' => $property_id ?? 0]) }}{{ \CompliancePrivilege::getActiveTabRegisterProject() }}" class="not-active list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.project.project_list') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Projects</a>
                @endif

                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENT_ASBESTOS, JOB_ROLE_ASBESTOS) and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENT_FIRE, JOB_ROLE_FIRE) and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_PI_ASSESSMENTS_WATER, JOB_ROLE_WATER))
                @else
                    <a href="{{ route('shineCompliance.assessment.index',['property_id' => $property_id ?? 0]) }}{{ \CompliancePrivilege::getActiveTabRegisterAssessment() }}" class="not-active list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.assessment.index') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Assessments/Surveys</a>
                @endif
{{--                 @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_CERTIFICATE_VIEW, JOB_ROLE_ASBESTOS))
                @else
                    <a href="{{ route('shineCompliance.property.list_cert_group',['property_id' => $property_id ?? 0]) }}" class="not-active list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.property.list_cert_group') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Air Test Certificates</a>
                @endif --}}
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROPERTIES_INFORMATION,JR_DOCUMENTS))
                @else
                    <a href="{{ route('shineCompliance.property.documents',['property_id' => $property_id ?? 0]) }}" class="list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.property.documents') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Documents</a>
                @endif
{{--                <a href="{{ route('shineCompliance.property.drawings', $property_id ?? 0) }}" class="border-unset list-group-item list-group-item-action {{ strpos(request()->route()->getName(), 'shineCompliance.property.drawings') !== false  ? 'list-group-active' : 'list-group-item-danger'  }} list-group-details">Drawings</a>--}}
            </ul>
        </div>
    </div>
</div>
