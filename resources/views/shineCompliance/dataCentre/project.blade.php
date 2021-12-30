@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' =>'data_center', 'color' => 'red'])
    <div class="container prism-content pad-up">
        <div class="row">
            <h3 style="margin: 0">Project</h3>
        </div>
        <div class="row mr-bt-top">
            <div class="full-width button-top-left pl-0 pr-0">
                <div class="form-button-search">
                    <ul class="nav" id="nav-project">

                        @if($asbestos)
                        <li class="nav-item">
                            <a href="#asbestos" style="text-decoration: none" data-toggle="tab" class="active">
                                <button type="submit" class="fs-8pt btn shine-compliance-button asbestos">
                                    <strong>{{ __('Asbestos') }}</strong>
                                </button>
                            </a>
                        </li>
                        @endif
                        @if($fire)
                        <li class="nav-item">
                            <a href="#fire" style="text-decoration: none" data-toggle="tab">
                                <button type="submit" class="fs-8pt btn shine-compliance-button">
                                    <strong>{{ __('Fire') }}</strong>
                                </button>
                            </a>
                        </li>
                        @endif

                        @if($hs)
                            <li class="nav-item">
                                <a href="#hs" style="text-decoration: none" data-toggle="tab">
                                    <button type="submit" class="fs-8pt btn shine-compliance-button ">
                                        <strong>{{ __('H&S') }}</strong>
                                    </button>
                                </a>
                            </li>
                        @endif

                        @if($water)
                        <li class="nav-item">
                            <a href="#water" style="text-decoration: none" data-toggle="tab">
                                <button type="submit" class="fs-8pt btn shine-compliance-button">
                                    <strong>{{ __('Water') }}</strong>
                                </button>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            @include('shineCompliance.dataCentre.partials._data_centre_sidebar')
            <div class="col-md-9 pl-0 tab-content">
                <div id="asbestos" class="container tab-pane active" style="padding-left: 0; padding-right:0;">

                    @if(\CommonHelpers::isSystemClient() and !\CompliancePrivilege::checkPermission(JR_DATA_CENTRE,JR_DATA_CENTRE_PROJECT_ASBESTOS,JOB_ROLE_ASBESTOS))
                    @else
                        @include('shineCompliance.tables.project_datacentre', [
                            'title' => 'Survey Only Projects ',
                            'tableId' => 'survey-project',
                            'collapsed' => false,
                            'plus_link' => false,
                            'data' => $survey_only_projects,
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.project_datacentre', [
                            'title' => 'Remediation/Removal Projects',
                            'tableId' => 'project-remediation',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $remediation_projects,
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.project_datacentre', [
                            'title' => 'Demolition Projects',
                            'tableId' => 'project-demolition',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $demolition_projects,
                            'order_table' => 'survey-table'
                            ])

                        @include('shineCompliance.tables.project_datacentre', [
                            'title' => 'Analytical Projects ',
                            'tableId' => 'project-analyrical',
                            'collapsed' => true,
                            'plus_link' => false,
                            'data' => $analytical_projects,
                            'order_table' => 'survey-table'
                            ])
                    @endif
                </div>
                <div id="fire" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                        @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Fire Risk Assessment Projects ',
                        'tableId' => 'assessment-fire-project',
                        'collapsed' => false,
                        'plus_link' => false,
                        'data' => $fire_assessment_projects,
                        'order_table' => 'survey-table'
                        ])
                        @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Fire Remedial Projects ',
                        'tableId' => 'fire_remedial_projects-project',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $fire_remedial_projects,
                        'order_table' => 'survey-table'
                        ])
                        @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Fire Independent Survey Projects ',
                        'tableId' => 'fire_independent_projects-project',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $fire_independent_projects,
                        'order_table' => 'survey-table'
                        ])
                        @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Fire Equipment Assessment Projects ',
                        'tableId' => 'fire_equipment_projects-project',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $fire_equipment_projects,
                        'order_table' => 'survey-table'
                        ])
                </div>
                <div id="water" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                    @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Water Legionella Risk Assessment',
                        'tableId' => 'water_legionella_risk_-project',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $water_legionella_risk,
                        'order_table' => 'survey-table'
                        ])
                        @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Water Testing Assessment Projects ',
                        'tableId' => 'assessment-water-project',
                        'collapsed' => false,
                        'plus_link' => false,
                        'data' => $water_assessment_projects,
                        'order_table' => 'survey-table'
                        ])
                        @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Water Remedial Assessment Projects ',
                        'tableId' => 'water_remedial_projects-project',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $water_remedial_projects,
                        'order_table' => 'survey-table'
                        ])
                        @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Water Temperature Assessment Projects ',
                        'tableId' => 'water_temp_projects-project',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $water_temp_projects,
                        'order_table' => 'survey-table'
                        ])
                </div>
                <div id="hs" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                    @include('shineCompliance.tables.project_datacentre', [
                        'title' => 'Health & Safety Assessment Projects',
                        'tableId' => 'hs_and_s_risk_project',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $hs_projects,
                        'order_table' => 'survey-table'
                        ])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
<script>
    $('li.nav-item').click(function () {
        var className = $(this).find('a').attr('href').replace('#', '');
        $(this).find('button').addClass(className);

        $(this).siblings('li.nav-item').each(function (i, e) {
            var removedClass = $(e).find('a').attr('href').replace('#', '');
            $(e).find('button').removeClass(removedClass);
        })

    })

    $(document).ready(function () {
        var url = document.location.toString();
        if (url.match('#')) {
            var active_tab = url.split('#')[1];
            $('#nav-project a[href="#' + active_tab + '"]').tab('show');
        }
    });

</script>
@endpush
