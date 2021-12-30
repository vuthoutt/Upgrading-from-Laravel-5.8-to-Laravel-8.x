@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' =>'data_center', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">Survey</h3>
        </div>

        <div class="row">
            @include('shineCompliance.dataCentre.partials._data_centre_sidebar')

            <div class="col-md-9 pl-0" style="padding: 0">
                <div id="fire" class="container" style="padding-left: 0; padding-right:0;">

                    @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASBESTOS_SURVEYS,JOB_ROLE_ASBESTOS))
                    @else
                        @include('shineCompliance.tables.survey_datacentre', [
                            'title' => 'Management Survey Notifications',
                            'tableId' => 'survey-management',
                            'collapsed' => false,
                            'plus_link' => false,
                            'data' => $management_surveys,
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.survey_datacentre', [
                            'title' => 'Management Survey - Partial Notifications',
                            'tableId' => 'survey-partial-management',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => [],
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.survey_datacentre', [
                            'title' => 'Refurbishment Survey Notifications',
                            'tableId' => 'survey-refurbishment',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $refurbish_surveys,
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.survey_datacentre', [
                            'title' => 'Re-inspection Survey Notifications',
                            'tableId' => 'inspection-survey',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $reinspection_surveys,
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.survey_datacentre', [
                            'title' => 'Sample Survey Notifications',
                            'tableId' => 'sample-demolition',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => [],
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.survey_datacentre', [
                            'title' => 'Demolition Survey Notifications',
                            'tableId' => 'survey-demolition',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $demolition_surveys,
                            'order_table' => 'survey-table'
                            ])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
