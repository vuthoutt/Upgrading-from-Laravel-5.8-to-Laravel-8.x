@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'projects', 'color' => 'blue', 'data' => $project])

<div class="container prism-content">
    <h3>{{ $project->reference }} - {{ $project->title }}</h3>
    <div class="main-content">
        <div class="col-md-12 client-image-show mb-3">
            <img class="image-signature" src="{{ CommonHelpers::getFile(optional($project->property)->id, PROPERTY_IMAGE) }}">
        </div>
        <div class="col-md-12 client-image-show mb-3">
            <a title="Download Asbestos Register Image" href="{{ route('retrive_image',['type'=>  PROPERTY_IMAGE ,'id'=> optional($project->property)->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
        </div>
        <div class="row">
            <div class="col-md-6">
                @include('forms.form_text',['width_label' => 4,'title' => 'Property Name:', 'data' => optional($project->property)->name ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Property Reference:', 'data' => optional($project->property)->reference ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Project Reference:', 'data' => $project->reference ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Property Group:', 'data' => $project->property->zone->zone_name ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Property Client:', 'data' => optional($project->client)->name ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Workstream/Programme:', 'data' => $project->workStream->description ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Address 1:', 'data' => $project->property->propertyInfo->address1 ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Address 2:', 'data' => $project->property->propertyInfo->address2 ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Town:', 'data' => $project->property->propertyInfo->address3 ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'City:', 'data' => $project->property->propertyInfo->address4 ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'County:', 'data' => $project->property->propertyInfo->address5 ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Postcode:', 'data' => $project->property->propertyInfo->postcode ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Telephone:', 'data' => $project->property->propertyInfo->telephone ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Mobile:', 'data' => $project->property->propertyInfo->mobile ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Email:', 'data' => $project->property->propertyInfo->mobile ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Project Title:', 'data' => $project->title ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Project Type:', 'data' => $project->project_type_text ])
                {{-- @include('forms.form_text',['width_label' => 4,'title' => 'Project Initiation:', 'data' => $project->enquiry_date ]) --}}
                @include('forms.form_text',['width_label' => 4,'title' => 'Project Created Date:', 'data' => isset($project->created_at) ? $project->created_at->format('d/m/Y') : null ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Project Enquiry Date:', 'data' => $project->enquiry_date ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Project Start Date:', 'data' => $project->date ?? '' ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Project Due Date:', 'data' => $project->due_date ?? '' ])
                @if ($project->status == PROJECT_COMPLETE_STATUS)
                    @include('forms.form_text',['width_label' => 4,'title' => 'Project Completed Date:', 'data' => $project->completed_date_project ?? '' ])
                @endif
                @include('forms.form_text',['width_label' => 4,'title' => 'Status:', 'data' => $project->status_text ])
{{--                 @if($project->status == PROJECT_COMPLETE_STATUS)
                    @include('forms.form_text',['width_label' => 4,'title' => 'Completed Date:', 'data' => $project->completed_date ])
                @endif --}}
                {{-- @include('forms.form_text',['width_label' => 4,'title' => 'Project Setup By:', 'data' => CommonHelpers::getUserFullname($project->created_by), 'link' => route('profile', ['user_id' => $project->created_by] ) ]) --}}
                @include('forms.form_text',['width_label' => 4,'title' => 'Project Lead:', 'data' => CommonHelpers::getUserFullname($project->lead_key), 'link' => route('shineCompliance.profile-shineCompliance', ['user_id' => $project->lead_key] ) ])
                @include('forms.form_text',['width_label' => 4,'title' => 'Second Project Lead:', 'data' => CommonHelpers::getUserFullname($project->second_lead_key), 'link' => route('profile', ['user_id' => $project->second_lead_key] ) ])
                {{-- @include('forms.form_text',['width_label' => 4,'title' => 'Sponsor:', 'data' => optional($project->sponsor)->description ]) --}}
                {{-- @include('forms.form_text',['width_label' => 4,'title' => 'Sponsor Lead:', 'data' => CommonHelpers::getUserFullname($project->sponsor_lead_key), 'link' => route('profile', ['user_id' => $project->sponsor_lead_key] ) ]) --}}
                @include('forms.form_text',['width_label' => 4,'title' => 'PO Number:', 'data' => $project->job_no ])

                <div class="row">
                        @if($project->risk_classification_id == ASBESTOS_CLASSIFICATION)
                        <label class="col-md-4 col-form-label text-md-left font-weight-bold" >Survey(s) for Reference:</label>
                        <div class="col-md-6 form-input-text" >
                            @if(!is_null($project_surveys))
                                @foreach($project_surveys as $survey)
                                    <a href="{{ route('property.surveys', ['id' =>  $survey->id, 'section' => SECTION_DEFAULT]) }}">{{ $survey->reference }}</a>
                                    @if(!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @endif
                        @else
                        <label class="col-md-4 col-form-label text-md-left font-weight-bold" >Assessment(s) for Reference:</label>
                        <div class="col-md-6 form-input-text" >
                            @if(!is_null($project_assessments))
                                @foreach($project_assessments as $assessment)
                                    <a href="{{ route('shineCompliance.assessment.show', ['assess_id' =>  $assessment->id]) }}">{{ $assessment->reference ?? '' }}</a>
                                    @if(!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @endif
                        @endif
                    </div>
                </div>

                @if($project->risk_classification_id != ASBESTOS_CLASSIFICATION)
                    <div class="row">
                        <label class="col-md-4 col-form-label text-md-left font-weight-bold" >Hazard(s) for Reference:</label>
                        <div class="col-md-6 form-input-text" >
                            @if(!is_null($hazard_project))
                                @foreach($hazard_project as $hazard)
                                    <a href="{{ route('shineCompliance.assessment.get_hazard_detail', ['id' =>  $hazard->id, 'section' => SECTION_DEFAULT]) }}">{{ $hazard->reference }}</a>
                                    @if(!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif

                <div class="row">
                    <label class="col-md-4 col-form-label text-md-left font-weight-bold" >Auto Linked Survey:</label>
                    <div class="col-md-6 form-input-text" >
                        @if(isset($project->survey) && count($project->survey) > 0)
                            @foreach($project->survey as $survey)
                                <a href="{{ route('property.surveys', ['id' =>  $survey->id, 'section' => SECTION_DEFAULT]) }}">{{ $survey->reference }}</a>
                                @if(!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-4 col-form-label text-md-left font-weight-bold" >Linked Project</label>
                    <div class="col-md-6 form-input-text" >
                        @if(!is_null($linked_projects))
                            @foreach($linked_projects as $prj)
                                <a href="{{ route('project.index', ['id' =>  $prj->id]) }}">{{ $prj->reference }}</a>
                                @if(!$loop->last)
                                    ,
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                @if($project->contractor_not_required != 1)
                <div class="row">
                    <label class="col-md-4 col-form-label text-md-left font-weight-bold" >Contractors</label>
                    <div class="col-md-6 form-input-text" >
                        <ul style="margin-left: -25px">
                        @if(\Auth::user()->clients->client_type != 1)
                            @if(!is_null($project_contractors))
                                @foreach($project_contractors as $contractor)
                                    <li><a href="{{ route('contractor', ['client_id' => $contractor->id]) }}"> {{ $contractor->name }} </a></li>
                                 @endforeach
                            @endif
                        @else
                            <a href="">{{ \Auth::user()->clients->name }}</a>
                        @endif
                        </ul>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-md-6">
                @include('forms.form_text',['width_label' => 5,'title' => 'Primary:', 'data' =>
                CommonHelpers::getProgrammeType( $project->property->propertySurvey->asset_use_primary  ?? '',  $project->property->propertySurvey->asset_use_primary_other ?? '', 'primary' )
                ])
                @include('forms.form_text',['width_label' => 5,'title' => 'Secondary:',
                 'data' => CommonHelpers::getProgrammeType( $project->property->propertySurvey->asset_use_secondary ?? '',  $project->property->propertySurvey->asset_use_secondary_other ?? '', 'primary' )
                 ])
                @include('forms.form_text',['width_label' => 5,'title' => 'Age:',
                 'data' => $project->property->propertySurvey->construction_age ?? ''
                 ])
                @include('forms.form_text',['width_label' => 5,'title' => 'Construction Type:',
                 'data' => $project->property->propertySurvey->construction_type ?? ''
                 ])
                @include('forms.form_text',['width_label' => 5,'title' => 'No. Floors:',
                 'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_floors ?? '', $project->property->propertySurvey->size_floors_other ?? null)
                 ])
                @include('forms.form_text',['width_label' => 5,'title' => 'No. Staircases:',
                 'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_staircases ?? '', $project->property->propertySurvey->size_staircases_other ?? null)
                 ])
                @include('forms.form_text',['width_label' => 5,'title' => 'No. Lifts:',
                 'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_lifts ?? '', $project->property->propertySurvey->size_lifts_other ?? null)
                 ])
                @include('forms.form_text',['width_label' => 5,'title' => 'Net Area per Floor:',
                 'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_net_area ?? '')
                 ])
                @include('forms.form_text',['width_label' => 5,'title' => 'Gross Area:',
                 'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_gross_area ?? '')
                 ])

                 @if($project->project_type == PROJECT_REMEDIATION_REMOVAL)

                        @include('forms.form_text',['width_label' => 5,'title' => 'Linked Survey Type:',
                             'data' => optional($project->surveyType)->description
                             ])
                        @include('forms.form_text',['width_label' => 5,'title' => 'R&R Condition:',
                             'data' => optional($project->rrCondition)->description
                             ])
                 @endif
                <div class="row">
                    <label class="col-md-5 col-form-label text-md-left" >
                        <span class="font-weight-bold">Linked Comments:</span>
                        <a href="" data-toggle="modal" data-target="#project_comment_history">(History)</a>
                    </label>
                    <div class="col-md-6 form-input-text" >
                        {!! nl2br($project->comments) ?? null !!}
                    </div>
                </div>
                @include('modals.comment_history',[ 'color' => 'red',
                                            'modal_id' => 'project_comment_history',
                                            'header' => 'Historical Project Comments',
                                            'table_id' => 'project_comment_history_table',
                                            'url' => route('comment.project'),
                                            'data' => $project->commentHistory,
                                            'id' => $project->id
                                            ])
            </div>
        </div>
        <div class="mt-3 ml-2 pb-5">
            @if(\CommonHelpers::isSystemClient() and $canBeUpdateThisProject)
                @if($project->decommissioned == PROJECT_UNDECOMMISSION)
                    @if($project->status < PROJECT_READY_FOR_ARCHIVE_STATUS)
                        <a href="{{ route('project.get_edit', ['project_id' => $project->id]) }}" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient ">
                                <strong>{{ __('Edit') }}</strong>
                            </button>
                        </a>
                    @elseif($project->status > PROJECT_READY_FOR_ARCHIVE_STATUS)
                        <a href="{{ route('project.archive', ['project_id' => $project->id, 'type' => 'restore']) }}" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient ">
                                <strong>{{ __('Restore') }}</strong>
                            </button>
                        </a>
                    @endif
                    @if( $project->status == PROJECT_READY_FOR_ARCHIVE_STATUS  || $project->contractor_not_required == 1 )
                        <a href="{{ route('project.archive', ['project_id' => $project->id, 'type' => 'archive']) }}" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient ">
                                <strong>{{ __('Archive') }}</strong>
                            </button>
                        </a>
                    @endif
                    <a href="" data-toggle="modal" data-target="#decommission_project" style="text-decoration: none">
                        <button type="button" class="btn light_grey_gradient">
                            <strong>{{ __('Decommission') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.decommission_project',[
                                'modal_id' => 'decommission_project',
                                'header' => 'Decommission Project',
                                'decommission_type' => 'project',
                                'name' => 'decommission_reason',
                                'color' => 'light_blue_gradient',
                                'reference' => $project->reference ?? '',
                                'url' => route('project.decommission', ['project_id' => $project->id]),
                            ])
                @else
                    <a href="" data-toggle="modal" data-target="#recommission_project" style="text-decoration: none">
                        <button type="button" class="btn light_grey_gradient">
                            <strong>{{ __('Recommission') }}</strong>
                        </button>
                    </a>
                    @include('shineCompliance.modals.recommission_project',[
                                'modal_id' => 'recommission_project',
                                'header' => 'Recommission Project',
                                'name' => 'decommission_reason',
                                'color' => 'light_blue_gradient',
                                'reference' => $project->reference ?? '',
                                'url' => route('project.decommission', ['id' => $project->id]),
                            ])
                @endif
            @endif
        </div>

        @if(!is_null($project_surveys))
            @foreach($project_surveys as $survey)
                    <div class="row">
                        @include('tables.survey_document', [
                            'title' => $survey->survey_type_text .' - ' . $survey->reference,
                            'data' => $survey->data,
                            'tableId' => 'survey-doc-table'. $survey->id,
                            'collapsed' => true,
                            'plus_link' =>false,
                            'edit_permission' => false
                        ])
                    </div>
            @endforeach
        @endif

        {{-- Other box --}}
        @if($project->status < PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS)
        @else
            {{-- Pre-construction --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROJECT_INFO, JR_PROJECT_COMPLETION_DOCUMENT))
            @else
                <div class="row">
                    @include('tables.project_document', [
                        'title' => 'Pre-construction Documents',
                        'modal_name' => 'pre-construction',
                        'data' => $pre_construction_docs,
                        'doc_types' => $pre_construction_document_types,
                        'tableId' => 'pre-construction-doc-table',
                        'collapsed' => !$updatePreConstruction,
                        'plus_link' => !$locked && $updatePreConstruction,
                        'edit_permission' => !$locked && $updatePreConstruction,
                        'type' => PRE_CONSTRUCTION_DOC_TYPE,
                        'doc_cat' => PRE_CONSTRUCTION_DOC_CATEGORY,
                        'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View", "Deadline", "Risk Warning", "Confirmation" ],
                        'modal_id' => 'pre-construction-doc-modal',
                        'order_table' => 'project-doc',
                        'stage_completed_label' => 'Pre-construction Completed',
                        'check_user' => true,
                        'canCompletedStage' => \CommonHelpers::checkProjectCompletedProgressStage($project->id, PROJECT_STAGE_PRE_CONSTRUCTION),
                    ])
                </div>
                @include('modals.project_document_add', [
                    'color' => 'blue',
                    'doc_cat' => PRE_CONSTRUCTION_DOC_CATEGORY,
                    'modal_id' => 'pre-construction-doc-modal',
                    'url' => route('ajax.project_doc'),
                    'title' => 'Add Pre-construction Document',
                    'doc_types' => $pre_construction_document_types,
                    'contractor_key' => PRE_CONSTRUCTION_DOC_TYPE
                ])
            @endif
            {{-- Design --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROJECT_INFO, JR_PROJECT_COMPLETION_DOCUMENT))
            @else
                <div class="row">
                    @include('tables.project_document', [
                        'title' => 'Design Documents',
                        'modal_name' => 'design',
                        'data' => $design_docs,
                        'doc_types' => $design_document_types,
                        'tableId' => 'design-doc-table',
                        'collapsed' => !$updateDesign,
                        'plus_link' => !$locked && $updateDesign,
                        'edit_permission' => !$locked && $updateDesign,
                        'type' => DESIGN_DOC_TYPE,
                        'doc_cat' => DESIGN_DOC_CATEGORY,
                        'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View", "Deadline", "Risk Warning", "Confirmation" ],
                        'modal_id' => 'design-doc-modal',
                        'order_table' => 'project-doc',
                        'stage_completed_label' => 'Design Completed',
                        'check_user' => true,
                        'canCompletedStage' => \CommonHelpers::checkProjectCompletedProgressStage($project->id, PROJECT_STAGE_DESIGN),
                    ])
                </div>
                @include('modals.project_document_add', [
                    'color' => 'blue',
                    'doc_cat' => DESIGN_DOC_CATEGORY,
                    'modal_id' => 'design-doc-modal',
                    'url' => route('ajax.project_doc'),
                    'title' => 'Add Design Document',
                    'doc_types' => $design_document_types,
                    'contractor_key' => DESIGN_DOC_TYPE
                ])
            @endif
            {{-- Commercial --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROJECT_INFO, JR_PROJECT_COMPLETION_DOCUMENT))
            @else
                <div class="row">
                    @include('tables.project_document', [
                        'title' => 'Commercial Documents',
                        'modal_name' => 'commercial',
                        'data' => $commercial_docs,
                        'doc_types' => $commercial_document_types,
                        'tableId' => 'commercial-doc-table',
                        'collapsed' => !$updateCommercial,
                        'plus_link' => !$locked && $updateCommercial,
                        'edit_permission' => !$locked && $updateCommercial,
                        'type' => COMMERCIAL_DOC_TYPE,
                        'doc_cat' => COMMERCIAL_DOC_CATEGORY,
                        'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View", "Deadline", "Risk Warning", "Confirmation" ],
                        'modal_id' => 'commercial-doc-modal',
                        'order_table' => 'project-doc',
                        'stage_completed_label' => 'Commercial Completed',
                        'check_user' => true,
                        'canCompletedStage' => \CommonHelpers::checkProjectCompletedProgressStage($project->id, PROJECT_STAGE_COMMERCIAL),
                    ])
                </div>
                @include('modals.project_document_add', [
                    'color' => 'blue',
                    'doc_cat' => COMMERCIAL_DOC_CATEGORY,
                    'modal_id' => 'commercial-doc-modal',
                    'url' => route('ajax.project_doc'),
                    'title' => 'Add Commercial Document',
                    'doc_types' => $commercial_document_types,
                    'contractor_key' => COMMERCIAL_DOC_TYPE
                ])
            @endif
            {{-- Planning --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROJECT_INFO, JR_PROJECT_PLANNING_DOCUMENT))
            @else
                <div class="row">
                    @include('tables.project_document', [
                        'title' => 'Planning Documents',
                        'modal_name' => 'Planning',
                        'data' => $planning_docs,
                        'doc_types' => $planning_document_types,
                        'tableId' => 'planning-doc-table',
                        'collapsed' => !$updatePlanning,
                        'plus_link' => !$locked && $updatePlanning,
                        'edit_permission' => !$locked && $updatePlanning,
                        'type' => PLANNING_DOC_TYPE,
                        'doc_cat' => PLANNING_DOC_CATEGORY,
                        'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View", "Deadline", "Risk Warning", "Confirmation" ],
                        'modal_id' => 'planning-doc-modal',
                        'order_table' => 'project-doc',
                        'stage_completed_label' => 'Planning Completed',
                        'check_user' => true,
                        'canCompletedStage' => \CommonHelpers::checkProjectCompletedProgressStage($project->id, PROJECT_STAGE_PLANNING),
                    ])
                </div>
                @include('modals.project_document_add', [
                    'color' => 'blue',
                    'doc_cat' => PLANNING_DOC_CATEGORY,
                    'modal_id' => 'planning-doc-modal',
                    'url' => route('ajax.project_doc'),
                    'title' => 'Add Planning Document',
                    'doc_types' => $planning_document_types,
                    'contractor_key' => PLANNING_DOC_TYPE
                ])
            @endif
            {{-- Prestart --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROJECT_INFO, JR_PROJECT_PRESTART_DOCUMENT))
            @else
                <div class="row">
                    @include('tables.project_document', [
                        'title' => 'Pre-Start Documents',
                        'modal_name' => 'Pre-Start',
                        'data' => $pre_start_docs,
                        'doc_types' => $pre_start_document_types,
                        'tableId' => 'pre_start-doc-table',
                        'collapsed' => !$updatePreStart,
                        'plus_link' => !$locked && $updatePreStart,
                        'edit_permission' => !$locked && $updatePreStart,
                        'type' => PRE_START_DOC_TYPE,
                        'doc_cat' => PRE_START_DOC_CATEGORY,
                        'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View", "Deadline", "Risk Warning", "Confirmation" ],
                        'modal_id' => 'pre_start-doc-modal',
                        'order_table' => 'project-doc',
                        'stage_completed_label' => 'Pre-Start Completed',
                        'check_user' => true,
                        'canCompletedStage' => \CommonHelpers::checkProjectCompletedProgressStage($project->id, PROJECT_STAGE_PRE_START),
                    ])
                </div>
                @include('modals.project_document_add', [
                    'color' => 'blue',
                    'doc_cat' => PRE_START_DOC_CATEGORY,
                    'modal_id' => 'pre_start-doc-modal',
                    'url' => route('ajax.project_doc'),
                    'title' => 'Add Pre-Start Document',
                    'doc_types' => $pre_start_document_types,
                    'contractor_key' => PRE_START_DOC_TYPE
                ])
            @endif
            {{-- Site Record --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROJECT_INFO, JR_PROJECT_SITE_RECORD_DOCUMENT))
            @else
                <div class="row">
                    @include('tables.project_document', [
                        'title' => 'Site Records Documents',
                        'modal_name' => 'Site Records',
                        'data' => $site_record_docs,
                        'doc_types' => $site_record_document_types,
                        'tableId' => 'site_record-doc-table',
                        'collapsed' => !$updateSiteRecord,
                        'plus_link' => !$locked && $updateSiteRecord,
                        'edit_permission' => !$locked  && $updateSiteRecord,
                        'type' => SITE_RECORD_DOC_TYPE,
                        'doc_cat' => SITE_RECORDS_DOC_CATEGORY,
                        'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View", "Deadline", "Risk Warning", "Confirmation" ],
                        'modal_id' => 'site_record-doc-modal',
                        'order_table' => 'project-doc',
                        'stage_completed_label' => 'Site Records Completed',
                        'check_user' => true,
                        'canCompletedStage' => \CommonHelpers::checkProjectCompletedProgressStage($project->id, PROJECT_STAGE_SITE_RECORD),
                    ])
                </div>
                @include('modals.project_document_add', [
                    'color' => 'blue',
                    'doc_cat' => SITE_RECORDS_DOC_CATEGORY,
                    'modal_id' => 'site_record-doc-modal',
                    'url' => route('ajax.project_doc'),
                    'title' => 'Add Site Records Document',
                    'doc_types' => $site_record_document_types,
                    'contractor_key' => SITE_RECORD_DOC_TYPE
                ])
            @endif
            {{-- Completion --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_PROJECT_INFO, JR_PROJECT_COMPLETION_DOCUMENT))

            @else
                <div class="row">
                    @include('tables.project_document', [
                        'title' => 'Completion Documents',
                        'modal_name' => 'Completion',
                        'data' => $completion_docs,
                        'doc_types' => $completion_document_types,
                        'tableId' => 'completion-doc-table',
                        'collapsed' => !$updateCompletion,
                        'plus_link' => !$locked && $updateCompletion,
                        'edit_permission' => !$locked && $updateCompletion,
                        'type' => COMPLETION_DOC_TYPE,
                        'doc_cat' => COMPLETION_DOC_CATEGORY,
                        'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View", "Deadline", "Risk Warning", "Confirmation" ],
                        'modal_id' => 'completion-doc-modal',
                        'order_table' => 'project-doc',
                        'stage_completed_label' => 'Completion Completed',
                        'check_user' => (\Auth::user()->clients->client_type == 0 ?? false),
                        'canCompletedStage' => \CommonHelpers::checkProjectCompletedProgressStage($project->id, PROJECT_STAGE_COMPLETION),
                    ])
                </div>
                @include('modals.project_document_add', [
                    'color' => 'blue',
                    'doc_cat' => COMPLETION_DOC_CATEGORY,
                    'modal_id' => 'completion-doc-modal',
                    'url' => route('ajax.project_doc'),
                    'title' => 'Add Completion Document',
                    'doc_types' => $completion_document_types,
                    'contractor_key' => COMPLETION_DOC_TYPE
                ])
            @endif
            @include('modals.project_doc_cancel',[ 'modal_id' => 'project-cancel'.($unique ?? ''),'color' => 'blue', 'header' => 'Document Cancel','unique' => ($unique ?? '') ])
            @include('modals.project_doc_confirm',[ 'modal_id' => 'project-confirm'.($unique ?? ''),'color' => 'blue', 'header' => 'Document Approval','unique' => ($unique ?? '')])
            @include('modals.project_doc_reject',[ 'modal_id' => 'project-reject'.($unique ?? ''),'color' => 'blue', 'header' => 'Document Rejection', 'url' => route('document.reject'),'unique' => ($unique ?? '') ])
        @endif

        {{--  --}}
        @if(count($project_document) > 0)
        <div class="row">
            @include('tables.project_document_mutiple', [
                'title' => 'Documents for Reference',
                'data' => $project_document,
                'tableId' => 'documents_for_reference',
                'collapsed' => true,
                'plus_link' => false ,
                'header' => [ "Document Name", "Reference", "Category", "File", "Date Completed"],
            ])
        </div>
        @endif
    </div>
</div>
@endsection
