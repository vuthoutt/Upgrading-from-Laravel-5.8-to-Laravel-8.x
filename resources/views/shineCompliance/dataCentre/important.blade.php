@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' =>'data_center', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">Important</h3>
        </div>

        <div class="row">
            @include('shineCompliance.dataCentre.partials._data_centre_sidebar')

            <div class="col-md-9 pl-0" style="padding: 0">
                <div id="fire" class="container" style="padding-left: 0; padding-right:0;">
                    @include('shineCompliance.tables.overdue_surveys_datacentre_critical', [
                        'title' => 'Assessment Important Notifications',
                        'tableId' => 'assessment-overdue-critical',
                        'collapsed' => false,
                        'plus_link' => false,
                        'type' => 'assessment',
                        'data' => $important_assessments
                        ])
                    @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_IMPORTANT,JOB_ROLE_ASBESTOS))
                    @else
                        @include('shineCompliance.tables.overdue_surveys_datacentre_critical', [
                            'title' => 'Audit Important Notifications',
                            'tableId' => 'audit-overdue-critical',
                            'collapsed' => true,
                            'plus_link' => false,
                            'type' => 'audit',
                            'data' => $important_audits
                            ])

                        @include('shineCompliance.tables.overdue_surveys_datacentre_critical', [
                            'title' => 'Certificate Important Notifications',
                            'tableId' => 'cert-overdue-critical',
                            'collapsed' => true,
                            'plus_link' => false,
                            'type' => 'certificate',
                            'data' => []
                            ])
                    @endif

                    @include('shineCompliance.tables.overdue_documents_datacentre', [
                        'title' => 'Project Document Important Notifications',
                        'tableId' => 'planning-document-overdue',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $project_docs,
                        'order_table' => '[]'
                        ])

                    @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_IMPORTANT,JOB_ROLE_ASBESTOS))
                    @else

                        @include('shineCompliance.tables.overdue_surveys_datacentre', [
                            'title' => 'Survey Important Notifications',
                            'tableId' => 'survey-important',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $overdue_surveys
                            ])

                        @include('shineCompliance.tables.overdue_surveys_re_inspections_datacentre_critical2', [
                            'title' => 'Survey Re-Inspection Important Notifications',
                            'tableId' => 're-inspection-overdue-important',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $reinspection_sites
                            ])
                    @endif

                    @include('shineCompliance.tables.overdue_surveys_re_inspections_datacentre_critical2', [
                        'title' => 'Assessment Re-Inspection Important Notifications',
                        'tableId' => 'assessment-re-inspection-overdue',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $reinspection_assessments
                        ])

                    @include('shineCompliance.tables.pre_plan_urgent_to_deadline', [
                            'title' => 'Pre-Planned Maintenance Important Notifications',
                            'tableId' => 'pre-plan-maintenance-overdue',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => []
                            ])
                </div>
            </div>
        </div>
    </div>
@endsection
