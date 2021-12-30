@extends('shineCompliance.layouts.app')

@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'register_health_and_safety', 'color' => 'red', 'data' =>  $property])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $property->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_register',['backRoute' => url()->previous(), 'property_id' => $property_id ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar', ['active_summary' => true])
            @if($section)
                <div class="col-md-9 pr-0 pl-0">
                    @include('shineCompliance.tables.register_hazards', [
                        'title' => $property->breadcrumb_title ?? '',
                        'tableId' => 'assessment_hazard',
                        'over_all_text' => '',
                        'collapsed' => false,
                        'plus_link' => false,
                        'data' => $section_hazard,
                        'order_table' => "[]"
                        ])
                </div>
            @else
                <div class="col-md-9 pr-0 pl-0">
                    @include('shineCompliance.tables.property_register_summary', [
                        'title' => 'Property Health & Safety Hazard Summary',
                        'tableId' => 'property_register_summary',
                        'count' => $register_data["All Hazard Risk Count"]['number'],
                        'data' => $register_data,
                        'register' => true,
                        'plus_link' => $can_add_new,
                        'normalTable' => true,
                        'link' => route('shineCompliance.assessment.get_add_hazard',['property_id' => $property_id, 'assess_type' => ASSESSMENT_HS_TYPE]),
                        'collapsed' => false
                        ])

                    @include('shineCompliance.tables.property_register_summary', [
                            'title' => 'Property Decommissioned Health & Safety Hazard Summary',
                            'tableId' => 'decommission_property_register_summary',
                            'count' => $decommission_register_data["All Hazard Risk Count"]['number'],
                            'data' => $decommission_register_data,
                            'register' => true,
                            'plus_link' => false,
                            'normalTable' => true,
                            'collapsed' => true
                            ])
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
