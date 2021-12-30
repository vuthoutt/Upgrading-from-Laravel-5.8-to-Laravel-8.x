@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' =>'data_center', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">Assessment</h3>
        </div>

        <div class="row">
            @include('shineCompliance.dataCentre.partials._data_centre_sidebar')
            <div class="col-md-9 pl-0" style="padding: 0">
                <div id="fire" class="container" style="padding-left: 0; padding-right:0;">
                    @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASSESSMENT_FIRE, JOB_ROLE_FIRE))
                    @else
                        @include('shineCompliance.tables.assessment_datacentre', [
                            'title' => 'Fire Risk Assessment Notifications',
                            'tableId' => 'fire-assessment',
                            'collapsed' => false,
                            'plus_link' => false,
                            'data' => $fires,
                            'order_table' => 'survey-table'
                            ])
                    @endif
                    @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASSESSMENT_HS, JOB_ROLE_H_S))
                    @else
                        @include('shineCompliance.tables.assessment_datacentre', [
                            'title' => 'Health And Safety Assessment Notifications',
                            'tableId' => 'hs-assessment',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $hs,
                            'order_table' => 'survey-table'
                            ])
                    @endif
                    @if(env('WATER_MODULE'))
                        @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_ASSESSMENT_WATER, JOB_ROLE_WATER))
                        @else
                            @include('shineCompliance.tables.assessment_datacentre', [
                                'title' => 'Water Risk Assessment Notifications',
                                'tableId' => 'water-assessment',
                                'collapsed' => true,
                                'plus_link' => false,
                                'data' => $waters,
                                'order_table' => 'survey-table'
                                ])
                        @endif
                    @endif

                    @include('shineCompliance.tables.assessment_datacentre', [
                        'title' => 'Pre-Planned Maintenance Assessment Notifications',
                        'tableId' => 'maintence-assessment',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => [],
                        'order_table' => 'survey-table'
                        ])

                </div>
            </div>
        </div>
    </div>
@endsection
