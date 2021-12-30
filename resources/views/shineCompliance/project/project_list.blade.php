@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'property_detail_shineCompliance', 'color' => 'red', 'data' => $property])
<div class="container-cus prism-content pad-up">
    <div class="row ">
        <h3 class="title-row" >{{$property->name ?? ''}}</h3>
    </div>

    <div class="main-content mar-up">
        <div class="row mr-bt-top">
            <div class="full-width button-top-left pl-0 pr-0">
                @if(!isset($property->parents))
                <div class="form-button-left" >
                    <a href="{{ route('shineCompliance.zone.details',['zone_id' => $property->zone_id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn shine-compliance-button fs-8pt">
                            <strong>{{ __('Back') }}</strong>
                        </button>
                    </a>
                </div>
                @else
                <div class="form-button-left" >
                    <a href="{{ route('shineCompliance.property.property_detail',['property_id' => $property->parent_id]) }}" style="text-decoration: none">
                        <button type="submit" class="btn shine-compliance-button fs-8pt">
                            <strong>{{ __('Back') }}</strong>
                        </button>
                    </a>
                </div>
                @endif
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

                        @if($health_and_safety)
                            <li class="nav-item">
                                <a href="#health_and_safety" style="text-decoration: none" data-toggle="tab">
                                    <button type="submit" class="fs-8pt btn shine-compliance-button ">
                                        <strong>{{ __('H&S') }}</strong>
                                    </button>
                                </a>
                            </li>
                        @endif

                        @if($water)
                        <li class="nav-item">
                            <a href="#water" style="text-decoration: none" data-toggle="tab">
                                <button type="submit" class="fs-8pt btn shine-compliance-button " style="margin-right: 0px!important;">
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
            @include('shineCompliance.properties.partials._property_sidebar',['property_id' => $property->id])
            <div class="col-md-9 pl-0 tab-content">
                @if($asbestos)
                <div id="asbestos" class="container tab-pane active" style="padding-left: 0; padding-right:0;">
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Survey Only',
                            'data' => $project_survey_only,
                            'tableId' => 'property-project-table-asbestos',
                            'collapsed' => true,
                            'plus_link' => $can_add_asbestos,
                            'link' => route('project.get_add', ['property_id' => $property->id]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Remediation/Removal',
                            'data' => $project_remediation,
                            'tableId' => 'property-project-remediation-table-asbestos',
                            'collapsed' => true,
                            'plus_link' => $can_add_asbestos,
                            'link' => route('project.get_add', ['property_id' => $property->id]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Demolition',
                            'data' => $project_demolition,
                            'tableId' => 'property-project-demolition-table-asbestos',
                            'collapsed' => true,
                            'plus_link' => $can_add_asbestos,
                            'link' => route('project.get_add', ['property_id' => $property->id]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Analytical',
                            'data' => $project_analytical,
                            'tableId' => 'property-project-analytical-table-asbestos',
                            'collapsed' => true,
                            'plus_link' => $can_add_asbestos,
                            'link' => route('project.get_add', ['property_id' => $property->id]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Decommissioned Asbestos Projects',
                            'data' => $decommissioned_projects_asbestos,
                            'tableId' => 'property-decommissioned-asbestos-project-table-asbestos',
                            'collapsed' => true,
                            'plus_link' => false,
                            'link' => route('project.get_add', ['property_id' => $property->id]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                </div>
                @endif
                @if($fire)
                <div id="fire" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Fire Equipment Assessment',
                            'data' => $fire_equiment_ass,
                            'tableId' => 'property-project-equipment-table-fire',
                            'collapsed' => true,
                            'plus_link' => $can_add_fire,
                            'link' => route('project.get_add', ['property_id' => $property->id, 'type' => FIRE_PROJECT]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Fire Independent Survey',
                            'data' => $fire_independent_ass,
                            'tableId' => 'property-project-independent-table-fire',
                            'collapsed' => true,
                            'plus_link' => $can_add_fire,
                            'link' => route('project.get_add', ['property_id' => $property->id, 'type' => FIRE_PROJECT]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Fire Remedial Project',
                            'data' => $fire_remedial_ass,
                            'tableId' => 'property-project-remedial-table-fire',
                            'collapsed' => true,
                            'plus_link' => $can_add_fire,
                            'link' => route('project.get_add', ['property_id' => $property->id, 'type' => FIRE_PROJECT]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                        @include('shineCompliance.tables.property_projects', [
                            'title' => 'Fire Risk Assessment',
                            'data' => $fire_ass,
                            'tableId' => 'property-project-risk-table-fire',
                            'collapsed' => true,
                            'plus_link' => $can_add_fire,
                            'link' => route('project.get_add', ['property_id' => $property->id, 'type' => FIRE_PROJECT]),
                            'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                            'dashboard' => false,
                            'order_table' => 'published'
                            ])
                    </div>
                    <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_projects', [
                                'title' => 'Decommissioned Fire Projects',
                                'data' => $decommissioned_projects_fire,
                                'tableId' => 'property-decommission-project-risk-table-fire',
                                'collapsed' => true,
                                'plus_link' => false,
                                'link' => route('project.get_add', ['property_id' => $property->id, 'type' => FIRE_PROJECT]),
                                'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                                'dashboard' => false,
                                'order_table' => 'published'
                                ])
                    </div>
                </div>
                @endif
                @if($health_and_safety)
                    <div id="health_and_safety" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                        <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_projects', [
                                'title' => 'Health And Safety Projects',
                                'data' => $projects_hs, // wait amend to change
                                'tableId' => 'property-project-table-health_and_safety',
                                'collapsed' => false,
                                'plus_link' => $can_add_hs, // wait amend to change
                                'link' => route('project.get_add', ['property_id' => $property->id, 'type' => HS_PROJECT]),
                                'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                                'dashboard' => false,
                                'order_table' => 'published'
                                ])
                        </div>

                        <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_projects', [
                                'title' => 'Decommissioned H&S Projects',
                                'data' => $decommissioned_projects_hs,
                                'tableId' => 'property-decommissioned-hs-project-table',
                                'collapsed' => true,
                                'plus_link' => false,
                                'link' => route('project.get_add', ['property_id' => $property->id]),
                                'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                                'dashboard' => false,
                                'order_table' => 'published'
                                ])
                        </div>
                    </div>
                @endif

                @if($water)
                    <div id="water" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                        <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_projects', [
                                'title' => 'Legionella Risk Assessment',
                                'data' => $legionella_risk_assessment_projects,
                                'tableId' => 'property-legionella-risk-project-table-water',
                                'collapsed' => true,
                                'plus_link' => $can_add_water,
                                'link' => route('project.get_add', ['property_id' => $property->id, 'type' => WATER_PROJECT]),
                                'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                                'dashboard' => false,
                                'order_table' => 'published'
                                ])
                        </div>
                        <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_projects', [
                                'title' => 'Water Remedial Assessment',
                                'data' => $water_remedial_assessment_projects,
                                'tableId' => 'property-water-remedial-project-table-water',
                                'collapsed' => true,
                                'plus_link' => $can_add_water,
                                'link' => route('project.get_add', ['property_id' => $property->id, 'type' => WATER_PROJECT]),
                                'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                                'dashboard' => false,
                                'order_table' => 'published'
                                ])
                        </div>
                        <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_projects', [
                                'title' => 'Water Temperature Assessment',
                                'data' => $water_temperature_assessment_projects,
                                'tableId' => 'property-water-temperature-project-table-water',
                                'collapsed' => true,
                                'plus_link' => $can_add_water,
                                'link' => route('project.get_add', ['property_id' => $property->id, 'type' => WATER_PROJECT]),
                                'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                                'dashboard' => false,
                                'order_table' => 'published'
                                ])
                        </div>
                        <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_projects', [
                                'title' => 'Water Testing Assessment',
                                'data' => $water_testing_assessment_projects,
                                'tableId' => 'property-water-testing-project-table-water',
                                'collapsed' => true,
                                'plus_link' => $can_add_water,
                                'link' => route('project.get_add', ['property_id' => $property->id, 'type' => WATER_PROJECT]),
                                'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                                'dashboard' => false,
                                'order_table' => 'published'
                                ])
                        </div>
                        <div class="card-data " style="margin-top: -5px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_projects', [
                                'title' => 'Decommissioned Water Projects',
                                'data' => $decommissioned_projects_water,
                                'tableId' => 'property-decommissioned-water-project-table',
                                'collapsed' => true,
                                'plus_link' => false,
                                'link' => route('project.get_add', ['property_id' => $property->id]),
                                'header' => ['Project ID','Project Title','Project Type','Status','Date Completed'],
                                'dashboard' => false,
                                'order_table' => 'published'
                                ])
                        </div>
                    </div>
                @endif
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
