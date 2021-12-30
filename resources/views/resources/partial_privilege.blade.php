
@if($type == JOB_ROLE_VIEW)
    @if($data->id == REPORTING_VIEW)
        @include('resources.partial_summary_view',['data' => $data,'level' => $level + 1, 'prefix' => '-summary-view', 'type' => JOB_ROLE_VIEW])
    @endif
@elseif($type == JOB_ROLE_UPDATE)
    @if($data->id == DATA_CENTRE_UPDATE)
        @include('resources.partial_document_approval_view',['data' => $data,'level' => $level + 1, 'prefix' => '-document-update', 'type' => JOB_ROLE_UPDATE])
    @endif
@endif
@if (isset($data) && !is_null($data) && count($data->allChildrens))
    <!-- bstree here -->
    <ul>
        @foreach ($data->allChildrens as $child)
            <li data-id="child{{$prefix}}-{{$child->id}}" data-normal="1" data-value="{{$child->id}}"
                data-checked="{{in_array($child->id, $data_normal) ? 1 : 0}}"
                data-level="{{$level + 1}}" data-parent="{{$data->id}}">
                <span>{{$child->name}}</span>
                <!-- some content need to be loaded dynamically -->
                @if($type == JOB_ROLE_VIEW)
                    @if($child->id == CLIENTS_VIEW)
                        @include('resources.partial_client_view',['data' => $child,'level' => $level + 1, 'prefix' => '-client-view', 'type' => JOB_ROLE_VIEW, 'array_check_zone' => $array_check_zone])
                    @elseif($child->id == PROJECTS_VIEW)
                        @include('resources.partial_project_view',['data' => $child,'level' => $level + 1, 'prefix' => '-project-view', 'type' => JOB_ROLE_VIEW])
                    @elseif($child->id == PROJECT_INFORMATIONS_VIEW)
                        @include('resources.partial_project_information_view',['data' => $child,'level' => $level + 1, 'prefix' => '-project-information-view', 'type' => JOB_ROLE_VIEW])
                    @elseif(in_array($child->id, [SYSTEM_OWNER_VIEW, CONTRACTORS_VIEW]))
                        @include('resources.partial_organisation_view',['data' => $child,'level' => $level + 1,
                        'prefix' => '-organisation-view', 'client_org' => $child->id == SYSTEM_OWNER_VIEW ? $clients : $clients_contractor,'type' => JOB_ROLE_VIEW])
                    @elseif($child->id == CATEGORY_BOX_VIEW)
                        @include('resources.partial_template_document_view',['data' => $child,'level' => $level + 1, 'prefix' => '-category-view', 'type' => JOB_ROLE_VIEW])
                    @endif
                @elseif($type == JOB_ROLE_UPDATE)
                    @if($child->id == CLIENTS_UPDATE)
                        @include('resources.partial_client_view',['data' => $child,'level' => $level + 1, 'prefix' => '-client-update', 'type' => JOB_ROLE_UPDATE, 'array_check_zone' => $array_check_zone])
                    @elseif($child->id == PROJECTS_UPDATE)
                        @include('resources.partial_project_view',['data' => $child,'level' => $level + 1, 'prefix' => '-project-update', 'type' => JOB_ROLE_UPDATE])
                    @elseif($child->id == PROJECT_INFORMATIONS_UPDATE)
                        @include('resources.partial_project_information_view',['data' => $child,'level' => $level + 1, 'prefix' => '-project-information-update', 'type' => JOB_ROLE_UPDATE])
                    @elseif(in_array($child->id, [SYSTEM_OWNER_UPDATE, CONTRACTORS_UPDATE]))
                        @include('resources.partial_organisation_view',['data' => $child,'level' => $level + 1,
                        'prefix' => '-organisation-update', 'client_org' => $child->id == SYSTEM_OWNER_UPDATE ? $clients : $clients_contractor,'type' => JOB_ROLE_UPDATE])
                    @elseif($child->id == CATEGORY_BOX_UPDATE)
                        @include('resources.partial_template_document_view',['data' => $child,'level' => $level + 1, 'prefix' => '-category-update', 'type' => JOB_ROLE_UPDATE])
                    @endif
                @endif
                @include('resources.partial_privilege',['data' => $child,'level' => ($level + 1) ])
            </li>
        @endforeach
    </ul>
@endif
