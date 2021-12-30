@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'project_detail_shineCompliance', 'color' => 'blue', 'data' => $project])

    <div class="container-cus prism-content">
        <div class="row">
            <h3>{{ $project->reference }} - {{ $project->title }}</h3>
        </div>
        <div class="main-content">
            <div class="col-md-12 client-image-show mb-3">
                <img class="image-signature" src="{{ CommonHelpers::getFile(optional($project->property)->id, PROPERTY_IMAGE) }}">
            </div>
            <div class="col-md-12 client-image-show mb-3">
                <a title="Download Asbestos Register Image" href="{{ route('retrive_image',['type'=>  PROPERTY_IMAGE ,'id'=> optional($project->property)->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
            </div>
            <div class="row">
                <div class="col-md-6">
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Property Name:', 'data' => optional($project->property)->name ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Property Reference:', 'data' => optional($project->property)->reference ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Project Reference:', 'data' => $project->reference ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Property Group:', 'data' => $project->property->zone->zone_name ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Property Client:', 'data' => optional($project->client)->name ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Workstream/Programme:', 'data' => $project->workStream->description ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Address 1:', 'data' => $project->property->propertyInfo->address1 ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Address 2:', 'data' => $project->property->propertyInfo->address2 ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Town:', 'data' => $project->property->propertyInfo->address3 ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'City:', 'data' => $project->property->propertyInfo->address4 ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'County:', 'data' => $project->property->propertyInfo->address5 ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Postcode:', 'data' => $project->property->propertyInfo->postcode ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Telephone:', 'data' => $project->property->propertyInfo->telephone ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Mobile:', 'data' => $project->property->propertyInfo->mobile ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Email:', 'data' => $project->property->propertyInfo->mobile ?? '' ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Project Title:', 'data' => $project->title ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Project Type:', 'data' => $project->project_type_text ])
                    {{-- @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Project Initiation:', 'data' => $project->enquiry_date ]) --}}
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Project Created Date:', 'data' => $project->date ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Project Due Date:', 'data' => $project->due_date ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Status:', 'data' => $project->status_text ])
                    {{--                 @if($project->status == PROJECT_COMPLETE_STATUS)
                                        @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Completed Date:', 'data' => $project->completed_date ])
                                    @endif --}}
                    {{-- @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Project Setup By:', 'data' => CommonHelpers::getUserFullname($project->created_by), 'link' => route('profile', ['user_id' => $project->created_by] ) ]) --}}
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Asbestssos Lead:', 'data' => CommonHelpers::getUserFullname($project->lead_key), 'link' => route('shineCompliance.profile-shineCompliance', ['user_id' => $project->lead_key] ) ])
                    @include('shineCompliance.forms.form_text',['width_label' => 4, 'title' => 'Secondary Asbestos Lead:', 'data' => CommonHelpers::getUserFullname($project->second_lead_key) ])
                    {{-- @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Second Asbestos Lead:', 'data' => CommonHelpers::getUserFullname($project->second_lead_key), 'link' => route('profile', ['user_id' => $project->second_lead_key] ) ]) --}}
                    {{-- @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Sponsor:', 'data' => optional($project->sponsor)->description ]) --}}
                    {{-- @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'Sponsor Lead:', 'data' => CommonHelpers::getUserFullname($project->sponsor_lead_key), 'link' => route('profile', ['user_id' => $project->sponsor_lead_key] ) ]) --}}
                    @include('shineCompliance.forms.form_text',['width_label' => 4,'title' => 'PO Number:', 'data' => $project->job_no ])
                    <div class="row">
                        <label class="col-md-4 col-form-label text-md-left font-weight-bold" >Survey(s) for reference</label>
                        <div class="col-md-6 form-input-text" >
                            @if(!is_null($project_surveys))
                                @foreach($project_surveys as $survey)
                                    <a href="{{ route('property.surveys', ['id' =>  $survey->id, 'section' => SECTION_DEFAULT]) }}">{{ $survey->reference }}</a>
                                    @if(!$loop->last)
                                        ,
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
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
                                    @if(\Auth::user()->clients->client_type != 1 || $wates_user)
                                        @if(!is_null($project_contractors))
                                            @foreach($project_contractors as $contractor)
                                                <li><a href="{{ route('shineCompliance.contractor', ['client_id' => $contractor->id]) }}"> {{ $contractor->name }} </a></li>
                                            @endforeach
                                        @endif
                                    @elseif($wates_view)
                                        @if(!is_null($project_winner_contractors))
                                            @foreach($project_winner_contractors as $contractor)
                                                <li><a href="{{ route('shineCompliance.contractor', ['client_id' => $contractor->id]) }}"> {{ $contractor->name }} </a></li>
                                            @endforeach
                                        @endif
                                    @else
                                        <a href="{{ route('shineCompliance.my_organisation', ['client_id' => \Auth::user()->clients->id]) }}">{{ \Auth::user()->clients->name }}</a>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'Primary:', 'data' =>
                    CommonHelpers::getProgrammeType( $project->property->propertySurvey->asset_use_primary  ?? '',  $project->property->propertySurvey->asset_use_primary_other ?? '', 'primary' )
                    ])
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'Secondary:',
                     'data' => CommonHelpers::getProgrammeType( $project->property->propertySurvey->asset_use_secondary ?? '',  $project->property->propertySurvey->asset_use_secondary_other ?? '', 'primary' )
                     ])
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'Age:',
                     'data' => $project->property->propertySurvey->construction_age ?? ''
                     ])
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'Construction Type:',
                     'data' => $project->property->propertySurvey->construction_type ?? ''
                     ])
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'No. Floors:',
                     'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_floors ?? '', $project->property->propertySurvey->size_floors_other ?? null)
                     ])
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'No. Staircases:',
                     'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_staircases ?? '', $project->property->propertySurvey->size_staircases_other ?? null)
                     ])
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'No. Lifts:',
                     'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_lifts ?? '', $project->property->propertySurvey->size_lifts_other ?? null)
                     ])
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'Net Area per Floor:',
                     'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_net_area ?? '')
                     ])
                    @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'Gross Area:',
                     'data' => \CommonHelpers::getSurveyPropertyInfoText($project->property->propertySurvey->size_gross_area ?? '')
                     ])

                    @if($project->project_type == PROJECT_REMEDIATION_REMOVAL)

                        @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'Linked Survey Type:',
                             'data' => optional($project->surveyType)->description
                             ])
                        @include('shineCompliance.forms.form_text',['width_label' => 5,'title' => 'R&R Condition:',
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
                    @include('shineCompliance.modals.comment_history',[ 'color' => 'red',
                                                'modal_id' => 'project_comment_history',
                                                'header' => 'Historical Project Comments',
                                                'table_id' => 'project_comment_history_table',
                                                'url' => route('comment.project'),
                                                'data' => $project->commentHistory,
                                                'id' => $project->id
                                                ])
                </div>
            </div>
            <div class="mt-3 pb-2 pl-0 ml-0">
                @if( (\CommonHelpers::isSystemClient() and $canBeUpdateThisProject) || ($wates_user))
                    @if($project->status < 4)
                        <a href="{{ route('project.get_edit', ['project_id' => $project->id]) }}" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient_button fs-8pt">
                                <strong>{{ __('Edit') }}</strong>
                            </button>
                        </a>
                    @else
                        <a href="{{ route('project.archive', ['project_id' => $project->id, 'type' => 'restore']) }}" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                                <strong>{{ __('Restore') }}</strong>
                            </button>
                        </a>
                    @endif
                    @if( $project->status == PROJECT_TECHNICAL_IN_PROGRESS_ALL_STATUS and ($is_approval_project  || $project->contractor_not_required == 1) )
                        <a href="{{ route('project.archive', ['project_id' => $project->id, 'type' => 'archive']) }}" style="text-decoration: none">
                            <button type="submit" class="btn light_grey_gradient_button fs-8pt ">
                                <strong>{{ __('Archive') }}</strong>
                            </button>
                        </a>
                    @endif
                @endif
            </div>

            {{-- Tender Box --}}
            @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(PROJECT_DOCUMENT_TYPE_PERMISSION, TENDER_DOC_PRIVILEGE))
            @else
                <div class="row">
                    @include('shineCompliance.tables.project_document', [
                        'title' => 'Tender Documents',
                        'modal_name' => 'Tender',
                        'data' => $tender_docs,
                        'doc_types' => $tender_document_types,
                        'tableId' => 'tender-doc-table',
                        'collapsed' => false,
                        'plus_link' => $locked || (\Auth::user()->clients->client_type == 1) || !$updateTender ? false : true,
                        'edit_permission' => $locked || (\Auth::user()->clients->client_type == 1) || !$updateTender ? false : true,
                        'type' => TENDER_DOC_TYPE,
                        'doc_cat' => TENDER_DOC_CATEGORY,
                        'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View" ],
                        'modal_id' => 'tender-doc-modal',
                    ])
                </div>
                @include('shineCompliance.modals.project_document_add',['color' => 'blue','doc_cat' => TENDER_DOC_CATEGORY, 'modal_id' => 'tender-doc-modal', 'url' => route('shineCompliance.ajax.project_doc'), 'title' => 'Add Tender Documents', 'doc_types' => $tender_document_types, 'contractor_key' => 'tender'])
            @endif
            {{-- project survey document --}}
            @if(!is_null($project_surveys))
                @foreach($project_surveys as $survey)
                    <div class="row">
                        @include('shineCompliance.tables.survey_document', [
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

            {{-- contractor box--}}
            @if($project->contractor_not_required != 1)
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(PROJECT_DOCUMENT_TYPE_PERMISSION, CONTRACTOR_DOC_PRIVILEGE))
                @else
                    @if ($project->status >= 2)
                        @if(!is_null($project_contractors))
                            @foreach($project_contractors as $contractor)
                                @if(\Auth::user()->client_id == $contractor->id || \Auth::user()->client_id == $project->client_id || \CommonHelpers::isSystemClient() || $wates_user || $wates_view)
                                    <div class="row">
                                        @include('shineCompliance.tables.project_document', [
                                            'title' => 'Contractor - ' .$contractor->name,
                                            'data' => $contractor->data,
                                            'tableId' => 'contractor-doc-table'. $contractor->id,
                                            'collapsed' => true,
                                            'plus_link' => $locked || !$in_contractors || !$updateContractor || ($wates_user and \Auth::user()->client_id == 6 and ($contractor->id != 6)) || ($wates_view and \Auth::user()->client_id == 6 and ($contractor->id != 6)) ? false : true,
                                            'edit_permission' => $locked || !$in_contractors || !$updateContractor || ($wates_user and \Auth::user()->client_id == 6 and ($contractor->id != 6)) || ($wates_view and \Auth::user()->client_id == 6 and ($contractor->id != 6)) ? false : true,
                                            'type' => CONTRACTOR_DOC_TYPE,
                                            'doc_cat' => CONTRACTOR_DOC_CATEGORY,
                                            'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View", "Risk Warning", "Confirmation" ],
                                            'modal_id' => 'contractor-doc-modal'. $contractor->id,
                                            'contractor_data' => $contractor
                                        ])
                                    </div>
                                    @include('shineCompliance.modals.project_document_add',['color' => 'blue','doc_cat' => CONTRACTOR_DOC_CATEGORY, 'modal_id' => 'contractor-doc-modal'. $contractor->id, 'url' => route('shineCompliance.ajax.project_doc'), 'title' => 'Add Contractors Documents', 'doc_types' => $contractor_document_types, 'contractor_key' => $contractor->id])
                                @endif
                            @endforeach
                            @include('shineCompliance.modals.project_doc_confirm',[ 'modal_id' => 'project-confirm','color' => 'blue', 'header' => 'shineReflect - Document Approval'])
                            @include('shineCompliance.modals.project_doc_reject',[ 'modal_id' => 'project-reject','color' => 'blue', 'header' => 'shineReflect - Document Rejection', 'url' => route('shineCompliance.document.reject') ])
                        @endif
                    @endif
                @endif
            @endif
            {{-- Other box --}}
            {{-- check permission with Wates work flow  --}}
            @if($wates_user and ($project->status == PROJECT_TENDERING_IN_PROGRESS_STATUS))
            @elseif($wates_table_permission)
            @else
                {{-- Planning --}}
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(PROJECT_DOCUMENT_TYPE_PERMISSION, PLANNING_DOC_PRIVILEGE))
                @else
                    <div class="row">
                        @include('shineCompliance.tables.project_document', [
                            'title' => 'Planning Documents',
                            'modal_name' => 'Planning',
                            'data' => $planning_docs,
                            'doc_types' => $planning_document_types,
                            'tableId' => 'planning-doc-table',
                            'collapsed' => true,
                            'plus_link' => $locked || !$updatePlanning ? false : true,
                            'edit_permission' => $locked || !$updatePlanning ? false : true,
                            'type' => PLANNING_DOC_TYPE,
                            'doc_cat' => PLANNING_DOC_CATEGORY,
                            'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View" ],
                            'modal_id' => 'planning-doc-modal',
                        ])
                    </div>
                    @include('shineCompliance.modals.project_document_add',['color' => 'blue','doc_cat' => PLANNING_DOC_CATEGORY, 'modal_id' => 'planning-doc-modal', 'url' => route('shineCompliance.ajax.project_doc'), 'title' => 'Add Planning Documents', 'doc_types' => $planning_document_types, 'contractor_key' => PLANNING_DOC_TYPE])
                @endif{{-- Prestart --}}
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(PROJECT_DOCUMENT_TYPE_PERMISSION, PRE_START_DOC_PRIVILEGE))
                @else
                    <div class="row">
                        @include('shineCompliance.tables.project_document', [
                            'title' => 'Pre-Start Documents',
                            'modal_name' => 'Pre-Start',
                            'data' => $pre_start_docs,
                            'doc_types' => $planning_document_types,
                            'tableId' => 'pre_start-doc-table',
                            'collapsed' => true,
                            'plus_link' => $locked  || !$updatePreStart ? false : true,
                            'edit_permission' => $locked  || !$updatePreStart ? false : true,
                            'type' => PRE_START_DOC_TYPE,
                            'doc_cat' => PRE_START_DOC_CATEGORY,
                            'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View" ],
                            'modal_id' => 'pre_start-doc-modal',
                        ])
                    </div>
                    @include('shineCompliance.modals.project_document_add',['color' => 'blue','doc_cat' => PRE_START_DOC_CATEGORY, 'modal_id' => 'pre_start-doc-modal', 'url' => route('shineCompliance.ajax.project_doc'), 'title' => 'Add Pre-Start Documents', 'doc_types' => $pre_start_document_types, 'contractor_key' => PRE_START_DOC_TYPE])
                @endif{{-- Site Record --}}
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(PROJECT_DOCUMENT_TYPE_PERMISSION, SITE_RECORDS_DOC_PRIVILEGE))
                @else
                    <div class="row">
                        @include('shineCompliance.tables.project_document', [
                            'title' => 'Site Records Documents',
                            'modal_name' => 'Site Records',
                            'data' => $site_record_docs,
                            'doc_types' => $site_record_document_types,
                            'tableId' => 'site_record-doc-table',
                            'collapsed' => true,
                            'plus_link' => $locked || !$updateSiteRecord ? false : true,
                            'edit_permission' => $locked  || !$updateSiteRecord ? false : true,
                            'type' => SITE_RECORD_DOC_TYPE,
                            'doc_cat' => SITE_RECORDS_DOC_CATEGORY,
                            'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View" ],
                            'modal_id' => 'site_record-doc-modal',
                        ])
                    </div>
                    @include('shineCompliance.modals.project_document_add',['color' => 'blue','doc_cat' => SITE_RECORDS_DOC_CATEGORY, 'modal_id' => 'site_record-doc-modal', 'url' => route('shineCompliance.ajax.project_doc'), 'title' => 'Add Site Records Documents', 'doc_types' => $site_record_document_types, 'contractor_key' => SITE_RECORD_DOC_TYPE])
                @endif{{-- Completion --}}
                @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(PROJECT_DOCUMENT_TYPE_PERMISSION, COMPLETION_DOC_DOC_PRIVILEGE))
                @else
                    <div class="row">
                        @include('shineCompliance.tables.project_document', [
                            'title' => 'Completion Documents',
                            'modal_name' => 'Completion',
                            'data' => $completion_docs,
                            'doc_types' => $completion_document_types,
                            'tableId' => 'completion-doc-table',
                            'collapsed' => true,
                            'plus_link' => $locked  || !$updateCompletion ? false : true,
                            'edit_permission' => $locked  || !$updateCompletion ? false : true,
                            'type' => COMPLETION_DOC_TYPE,
                            'doc_cat' => COMPLETION_DOC_CATEGORY,
                            'header' => [ "Document Name", "Reference", "Document Type", "Last Revision", "View" ],
                            'modal_id' => 'completion-doc-modal',
                        ])
                    </div>
                    @include('shineCompliance.modals.project_document_add',['color' => 'blue','doc_cat' => COMPLETION_DOC_CATEGORY, 'modal_id' => 'completion-doc-modal', 'url' => route('shineCompliance.ajax.project_doc'), 'title' => 'Add Completion Documents', 'doc_types' => $completion_document_types, 'contractor_key' => COMPLETION_DOC_TYPE])
                @endif
            @endif
        </div>
    </div>
@endsection
