@extends('shineCompliance.layouts.app')

@section('content')
@if($section)
    @include('shineCompliance.partials.nav',['breadCrumb' => 'register_zone_fire_summary', 'color' => 'red', 'data' =>  $zone])
@else
    @include('shineCompliance.partials.nav',['breadCrumb' => 'register_zone_fire', 'color' => 'red', 'data' =>  $zone])
@endif
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $zone->zone_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.zones.partials._zone_button_register',['backRoute' => url()->previous(), 'zone_id' => $zone_id ])

        <div class="row">
            @include('shineCompliance.zones.partials._zone_sidebar', ['active_summary' => true])
            @if($section)
                <div class="col-md-9 pr-0 pl-0">
                    @include('shineCompliance.tables.register_hazards', [
                        'title' => $zone->breadcrumb_title ?? '',
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
                            'title' => 'Property Fire Hazard Summary',
                            'tableId' => 'property_register_summary',
                            'count' => $register_data["All Hazard Risk Count"]['number'],
                            'data' => $register_data,
                            'register' => true,
                            'plus_link' => false,
                            'normalTable' => true,
                            'collapsed' => false
                            ])
                    @include('shineCompliance.tables.property_register_summary', [
                            'title' => 'Property Group Decommissioned Fire Hazard Summary',
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
