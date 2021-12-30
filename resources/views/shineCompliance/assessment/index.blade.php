@extends('shineCompliance.layouts.app')

@section('content')
    @if(isset($property->parents))
        @include('shineCompliance.partials.nav',['breadCrumb' => 'sub_assessment_list', 'color' => 'red', 'data' => $property])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'assessment_list', 'color' => 'red', 'data' => $property])
    @endif
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{$property->name ?? ''}}</h3>
        </div>
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
                    <ul class="nav" id="nav-assessment">
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
                                <button type="submit" class="fs-8pt btn shine-compliance-button "  style="margin-right: 0px!important;">
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
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_asbestos_assessment', [
                                'title' => 'Management Survey',
                                'tableId' => 'property-management-survey-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddSurvey,
                                'link' => route('survey.get_add', ['property_id' => $property->id,'section' => SECTION_DEFAULT ]),
                                'data' => $management_Survey,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_asbestos_assessment', [
                                'title' => 'Management Survey - Partial',
                                'tableId' => 'property-management-partial-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddSurvey,
                                'link' => route('survey.get_add', ['property_id' => $property->id,'section' => SECTION_DEFAULT ]),
                                'data' => $management_Survey_partial,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_asbestos_assessment', [
                                'title' => 'Refurbishment Survey',
                                'tableId' => 'property-refurbishment-survey-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddSurvey,
                                'link' => route('survey.get_add', ['property_id' => $property->id,'section' => SECTION_DEFAULT ]),
                                'data' => $refurbishment_Survey,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_asbestos_assessment', [
                                'title' => 'Re-inspection Survey',
                                'tableId' => 'property-re-inspection-survey-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddSurvey,
                                'link' => route('survey.get_add', ['property_id' => $property->id,'section' => SECTION_DEFAULT ]),
                                'data' => $reInspection_Survey,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_asbestos_assessment', [
                                'title' => 'Sample Survey',
                                'tableId' => 'property-sample-survey-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddSurvey,
                                'link' => route('survey.get_add', ['property_id' => $property->id,'section' => SECTION_DEFAULT ]),
                                'data' => $sample_Survey,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_asbestos_assessment', [
                                'title' => 'Demolition Survey',
                                'tableId' => 'property-demolition-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddSurvey,
                                'link' => route('survey.get_add', ['property_id' => $property->id,'section' => SECTION_DEFAULT ]),
                                'data' => $demolition_Survey,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data mar-up" style="margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_asbestos_assessment', [
                                'title' => 'Decommissioned Asbestos Surveys',
                                'tableId' => 'property-dec-asbestos-assess-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => false,
                                'data' => $asbestosAssessmentsDecommissioned,
                                'order_table' => "[]"
                                ])
                        </div>
                    </div>
                @endif

                @if($fire)
                    <div id="fire" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_fire_assessment', [
                                'title' => 'Fire Risk Assessments',
                                'tableId' => 'property-fire-assess-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddFireAssessment,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => FIRE, 'property_id' => $property->id]),
                                'data' => $fra,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_fire_assessment', [
                                'title' => 'Fire Risk Assessment (Type 1)',
                                'tableId' => 'property-fire-assess1-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddFireAssessment,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => FIRE, 'property_id' => $property->id]),
                                'data' => $fra1,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_fire_assessment', [
                                'title' => 'Fire Risk Assessment (Type 2)',
                                'tableId' => 'property-fire-assess2-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddFireAssessment,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => FIRE, 'property_id' => $property->id]),
                                'data' => $fra2,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_fire_assessment', [
                                'title' => 'Fire Risk Assessment (Type 3)',
                                'tableId' => 'property-fire-assess3-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddFireAssessment,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => FIRE, 'property_id' => $property->id]),
                                'data' => $fra3,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_fire_assessment', [
                                'title' => 'Fire Risk Assessment (Type 4)',
                                'tableId' => 'property-fire-assess4-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddFireAssessment,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => FIRE, 'property_id' => $property->id]),
                                'data' => $fra4,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data mar-up" style="margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_fire_assessment', [
                                'title' => 'Decommissioned Fire Risk Assessment',
                                'tableId' => 'property-dec-fire-assess-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => false,
                                'data' => $fireAssessmentsDecommissioned,
                                'order_table' => "[]"
                                ])
                        </div>
                    </div>
                @endif

                @if($water)
                    <div id="water" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_water_assessment', [
                                'title' => 'Water Equipment Assessment',
                                'tableId' => 'property-water-equipment-assessment-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddWaterAssessment,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => WATER, 'property_id' => $property->id]),
                                'data' => $water_equipment_assessments,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_water_assessment', [
                                'title' => 'Water Risk Assessment',
                                'tableId' => 'property-water-risk-assessment-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddWaterAssessment,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => WATER, 'property_id' => $property->id]),
                                'data' => $water_risk_assessments,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_water_assessment', [
                                'title' => 'Water Temperature Assessment',
                                'tableId' => 'property-water-temperature-assessment-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => $canAddWaterAssessment,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => WATER, 'property_id' => $property->id]),
                                'data' => $water_temperature_assessments,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data mar-up" style="margin-bottom: 38px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_water_assessment', [
                                'title' => 'Decommissioned Water Risk Assessment ',
                                'tableId' => 'property-dec-water-assess-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => false,
                                'data' => $waterAssessmentsDecommissioned,
                                'order_table' => "[]"
                                ])
                        </div>
                    </div>
                @endif
                @if($hs)
                    <div id="hs" class="container tab-pane" style="padding-left: 0; padding-right:0;">
                        <div class="card-data " style="margin-top: 0px; margin-bottom: 10px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_hs_assessment', [
                                'title' => 'Health & Safety Assessments',
                                'tableId' => 'property-hs-assess-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => false,
                                'plus_link' => true,
                                'link' => route('shineCompliance.assessment.get_add', ['type' => HS, 'property_id' => $property->id]),
                                'data' => $hsAssessments,
                                'order_table' => "[]"
                                ])
                        </div>
                        <div class="card-data mar-up" style="margin-bottom: 38px; margin-right: -15px;">
                            @include('shineCompliance.tables.property_hs_assessment', [
                                'title' => 'Decommissioned Health & Safety Risk Assessment ',
                                'tableId' => 'property-dec-hs-assess-table',
                                'row_col' => 'col-md-12',
                                'collapsed' => true,
                                'plus_link' => false,
                                'data' => $hsAssessmentsDecommissioned,
                                'order_table' => "[]",
                                ])
                        </div>
                    </div>
                @endif
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
                $('#nav-assessment a[href="#' + active_tab + '"]').tab('show');
            }
        });
    </script>
@endpush
