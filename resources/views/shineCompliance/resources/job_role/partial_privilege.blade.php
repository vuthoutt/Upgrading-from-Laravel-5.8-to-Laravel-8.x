
@if($type == JOB_ROLE_VIEW)
    @if(in_array($data->id, [JR_GENERAL_EMAIL_NOTIFICATIONS_VIEW]))
        @include('shineCompliance.resources.job_role.partial_child_data_view',['data' => $data, 'items' => $data->privilegeChild,'level' => ($level + 1), 'prefix' => 'email-notification', 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralEmailNotificationViewByType($tab)])
    @elseif(in_array($data->id, [JR_GENERAL_REPORTING_VIEW, JR_FIRE_RESOURCES_WORKFLOW, JR_WATER_RESOURCES_WORKFLOW, JR_HS_RESOURCES]))
        @include('shineCompliance.resources.job_role.partial_child_data_view',['data' => $data, 'items' => $data->privilegeChild,'level' => ($level + 1), 'prefix' => 'reporting', 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralReportingViewByType($tab)])
    @elseif(in_array($data->id, [JR_ASBESTOS_REPORTING]))
        @include('shineCompliance.resources.job_role.partial_summary_view',['data' => $data,'level' => $level + 1, 'prefix' => 'reporting', 'type' => JOB_ROLE_VIEW, 'checked_summary' => $job_role->getAsbestosReporting()])
    @endif
@elseif($type == JOB_ROLE_UPDATE)

@endif
@if (isset($data) && !is_null($data) && count($data->allChildren))
    <!-- bstree here -->
    <ul>
        @foreach ($data->allChildren as $child)
            <li data-id="child{{$prefix}}-{{$child->id}}" data-prefix="normal" data-value="{{$child->id}}"
                data-checked="{{in_array($child->id, $data_normal) ? 1 : 0}}"
                data-level="{{$level + 1}}" data-parent="{{$data->id}}">
                <span>{{$child->name}}</span>
                <!-- some content need to be loaded dynamically -->
                @if($type == JOB_ROLE_VIEW)
                    @if(in_array($child->id, [JR_GENERAL_CLIENT_VIEW]))
{{--                        @include('shineCompliance.resources.job_role.partial_client_view',['data' => $child,'level' => ($level + 1), 'prefix' => 'client-property-view', 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralValueViewByType($tab, 'group')])--}}
{{--                    @elseif(in_array($child->id, [JR_GENERAL_SYSTEM_OWNER_VIEW]))--}}
{{--                        @include('shineCompliance.resources.job_role.partial_organisation_view',['data' => $child, 'client_org' => $clients,'level' => ($level + 1), 'prefix' => 'organisation-view', 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralOrganisationViewByType($tab, 'organisation')])--}}
{{--                    @elseif(in_array($child->id, [JR_GENERAL_SYSTEM_CLIENT_VIEW]))--}}
{{--                        @include('shineCompliance.resources.job_role.partial_organisation_view',['data' => $child, 'client_org' => $clients_other,'level' => ($level + 1), 'prefix' => 'client-view', 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralOrganisationViewByType($tab, 'client')])--}}
{{--                    @elseif(in_array($child->id, [JR_GENERAL_SYSTEM_CONTRACTOR_VIEW]))--}}
{{--                        @include('shineCompliance.resources.job_role.partial_organisation_view',['data' => $child, 'client_org' => $clients_contractor,'level' => ($level + 1), 'prefix' => 'contractor-view', 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralOrganisationViewByType($tab, 'contractor')])--}}
                    @elseif(in_array($child->id, [JR_GENERAL_RESOURCES_WORK_REQUEST_TYPE]))
                        @include('shineCompliance.resources.job_role.partial_work_request_view',['data' => $child, 'work_type' => $work_request_types,'level' => $level + 1, 'prefix' => "work-request", 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralValueViewByType($tab, 'work_request')])
                    @elseif(in_array($child->id, [JR_GENERAL_RESOURCES_WORKFLOW]))
                        @include('shineCompliance.resources.job_role.partial_work_request_view',['data' => $child, 'work_type' =>  $work_flow, 'level' => $level + 1, 'prefix' => "work-flow", 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralValueViewByType($tab, 'work_flow')])
                    @elseif($child->id == JR_CATEGORY_BOX_VIEW)
                        @include('shineCompliance.resources.job_role.partial_template_document_view',['data' => $child,'level' => $level + 1, 'prefix' => 'template-child-view', 'type' => JOB_ROLE_VIEW, 'checked_array' => $job_role->getGeneralValueViewByType($tab, 'category')])
                    @endif
                @elseif($type == JOB_ROLE_UPDATE)
                    @if(in_array($child->id, [JR_GENERAL_CLIENT_UPDATE]))
{{--                        @include('shineCompliance.resources.job_role.partial_client_view',['data' => $child,'level' => $level + 1, 'prefix' => 'client-property-update', 'type' => JOB_ROLE_UPDATE, 'checked_array' => $job_role->getGeneralValueEditByType($tab, 'group')])--}}
{{--                    @elseif(in_array($child->id, [JR_GENERAL_SYSTEM_OWNER_UPDATE]))--}}
{{--                        @include('shineCompliance.resources.job_role.partial_organisation_view',['data' => $child, 'client_org' => $clients,'level' => ($level + 1), 'prefix' => 'organisation-update', 'type' => JOB_ROLE_UPDATE, 'checked_array' => $job_role->getGeneralOrganisationEditByType($tab, 'organisation')])--}}
{{--                    @elseif(in_array($child->id, [JR_GENERALL_SYSTEM_CLIENT_UPDATE]))--}}
{{--                        @include('shineCompliance.resources.job_role.partial_organisation_view',['data' => $child, 'client_org' => $clients_other,'level' => ($level + 1), 'prefix' => 'client-update', 'type' => JOB_ROLE_UPDATE, 'checked_array' => $job_role->getGeneralOrganisationEditByType($tab, 'client')])--}}
{{--                    @elseif(in_array($child->id, [JR_GENERAL_SYSTEM_CONTRACTOR_UPDATE]))--}}
{{--                        @include('shineCompliance.resources.job_role.partial_organisation_view',['data' => $child, 'client_org' => $clients_contractor,'level' => ($level + 1), 'prefix' => 'contractor-update', 'type' => JOB_ROLE_UPDATE, 'checked_array' => $job_role->getGeneralOrganisationEditByType($tab, 'contractor')])--}}
                    @elseif($child->id == JR_CATEGORY_BOX_UPDATE)
                        @include('shineCompliance.resources.job_role.partial_template_document_view',['data' => $child,'level' => $level + 1, 'prefix' => 'template-child-update', 'type' => JOB_ROLE_UPDATE, 'checked_array' => $job_role->getGeneralValueEditByType($tab, 'category')])
                    @endif
                @endif
                @include('shineCompliance.resources.job_role.partial_privilege',['data' => $child,'level' => ($level + 1) ])
            </li>
        @endforeach
    </ul>
@endif
