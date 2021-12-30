@extends('shineCompliance.layouts.app')
@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'property_assessment_detail', 'color' => 'orange', 'data'=> $assessment])
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $assessment->property->name ?? ''}} {{ $assessment->reference ?? '' }}</h3>
        </div>
        <div class="main-content">
            <!-- Nav tabs -->
            <input type="hidden" id="active_tab" value="{{$active_tab}}">
            <ul class="nav nav-pills orange_gradient_nav" id="myTab" style="margin-left: -7px !important;">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#details" title="Details"><strong>Details</strong></a>
                </li>
                @if(($assessment->classification == ASSESSMENT_FIRE_TYPE && $assessment->type != ASSESS_TYPE_FIRE_EQUIPMENT)
                || ($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type != ASSESS_TYPE_WATER_EQUIPMENT && $assessment->type != ASSESS_TYPE_WATER_TEMP)
                || $assessment->classification == ASSESSMENT_HS_TYPE
                )
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#summary" title="Assessments"><strong>Summary</strong></a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#objective-scope" title="Objective/scope">
                        <strong>
                            @if($assessment->classification == ASSESSMENT_FIRE_TYPE || $assessment->classification == ASSESSMENT_HS_TYPE)
                                Executive Summary
                            @else
                                Objective/scope
                            @endif
                        </strong>
                    </a>
                </li>
                @if(($assessment->classification == ASSESSMENT_FIRE_TYPE && $assessment->type != ASSESS_TYPE_FIRE_EQUIPMENT)
                || ($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type != ASSESS_TYPE_WATER_EQUIPMENT && $assessment->type != ASSESS_TYPE_WATER_TEMP)
                || $assessment->classification == ASSESSMENT_HS_TYPE
                )
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#property-information" title="Property Information"><strong>Property Information</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#assessment-questionnaire" title="Assessment"><strong>Assessment</strong></a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#hazards" title="Hazards"><strong>Hazards</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#equipment" title="Equipment"><strong>Equipment</strong></a>
                </li>
                @if($assessment->assessmentInfo->setting_non_conformities == NONCONFORMITIES || ($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type != ASSESSMENT_WATER_TYPE) || $assessment->classification == ASSESSMENT_HS_TYPE)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Nonconformities" title="Nonconformities"><strong>Nonconformities</strong></a>
                </li>
                @endif
                @if(($assessment->classification == ASSESSMENT_FIRE_TYPE && $assessment->type != ASSESS_TYPE_FIRE_EQUIPMENT) || $assessment->classification == ASSESSMENT_HS_TYPE
                || ($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type != ASSESS_TYPE_WATER_EQUIPMENT && $assessment->type != ASSESS_TYPE_WATER_TEMP))
                @if($assessment->classification != ASSESSMENT_HS_TYPE)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#sampling" title="Sampling"><strong>Sampling</strong></a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#exists-assembly" title="Exits/Assembly"><strong>Exits/Assembly</strong></a>
                </li>
                @if($assessment->assessmentInfo->setting_show_vehicle_parking == VEHICLE_PARKING
                    || ($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type != ASSESSMENT_WATER_TYPE)
                    || $assessment->classification == ASSESSMENT_HS_TYPE)
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#vehicle-parking" title="Vehicle Parking"><strong>Vehicle Parking</strong></a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#plans" title="Plans"><strong>Plans</strong></a>
                </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#asbestos" title="Asbestos"><strong>Asbestos</strong></a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div id="details" class="container tab-pane active pl-0">
                    @include('shineCompliance.assessment.tab_details', ['data' => $assessment])
                </div>
                @if(($assessment->classification == ASSESSMENT_FIRE_TYPE && $assessment->type != ASSESS_TYPE_FIRE_EQUIPMENT) || $assessment->classification == ASSESSMENT_HS_TYPE
                || ($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type != ASSESS_TYPE_WATER_EQUIPMENT && $assessment->type != ASSESS_TYPE_WATER_TEMP))
                <div id="summary" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_summary', ['data' => $assessment])
                </div>
                @endif
                <div id="objective-scope" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_objective_scope', ['data' => $assessment])
                </div>
                @if(($assessment->classification == ASSESSMENT_FIRE_TYPE && $assessment->type != ASSESS_TYPE_FIRE_EQUIPMENT) || $assessment->classification == ASSESSMENT_HS_TYPE
                || ($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type != ASSESS_TYPE_WATER_EQUIPMENT && $assessment->type != ASSESS_TYPE_WATER_TEMP))
                <div id="property-information" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_property_information', ['data' => $assessment, 'propertyInfo' => json_decode($assessment->assessmentInfo->property_information ?? '', true)])
                </div>
                <div id="assessment-questionnaire" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_questionnaire',['data' => $assessment])
                </div>
                @endif
                <div id="hazards" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_hazard', ['data' => $assessment])
                </div>
                <div id="equipment" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_equipment', ['data' => $assessment])
                </div>
                <div id="Nonconformities" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_nonconform', ['data' => $assessment])
                </div>
                @if(($assessment->classification == ASSESSMENT_FIRE_TYPE && $assessment->type != ASSESS_TYPE_FIRE_EQUIPMENT) || $assessment->classification == ASSESSMENT_HS_TYPE
                || ($assessment->classification == ASSESSMENT_WATER_TYPE && $assessment->type != ASSESS_TYPE_WATER_EQUIPMENT && $assessment->type != ASSESS_TYPE_WATER_TEMP))
                <div id="sampling" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_sampling', ['data' => $samples])
                </div>
                <div id="exists-assembly" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_exits_assembly', [
                        'data' => $assessment,
                        'assemblyPoints' => $assessment->assemblyPoints,
                        'decommissionAssemblyPoints' => $assessment->decommissionAssemblyPoints,
                        'fireExits' => $assessment->fireExits,
                        'decommissionFireExits' => $assessment->decommissionFireExits,
                    ])
                </div>
                <div id="vehicle-parking" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_vehicle_parking', [
                        'data' => $assessment,
                        'vehicleParking' => $assessment->vehicleParking,
                        'decommissionVehicleParking' => $assessment->decommissionVehicleParking,
                    ])
                </div>
                <div id="plans" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_plan',['assessorsNotes' => $assessorsNotes, 'plans' =>$plans ])
                </div>
                @endif
                <div id="asbestos" class="container tab-pane pl-0">
                    @include('shineCompliance.assessment.tab_asbestos', ['data' => $assessment])
                </div>
            </div>
        </div>
    </div>
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            var currentTab = $('#myTab a.active').attr("title");
            var active_tab = $('#active_tab').val();
            if(active_tab){
                localStorage.setItem('activeTab', active_tab);
            }
            //Tab change
            $('#myTab a').click(function () {
                var tab = $(this).attr("title");

                if (currentTab != tab) {
                    // auditTab(tab);
                }
                currentTab = tab
            });

            {{--function auditTab(tab) {--}}
            {{--    $.ajax({--}}
            {{--        type: "POST",--}}
            {{--        url: "{{route('ajax.ajax_audit')}}",--}}
            {{--        data: {id: {{ $assessment->id }},type : 'survey', tab: tab, _token:"{{ csrf_token() }}"}--}}
            {{--    });--}}
            {{--}--}}
        });
    </script>
@endpush
@push('css')
    <style>
        .page-item.active .page-link {
            background-color: #f57f34 !important;
            border-color: #f57f34 !important;
        }
    </style>
@endpush
