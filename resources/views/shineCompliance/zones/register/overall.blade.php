@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'register_zone_overall', 'color' => 'red', 'data' => $zone ?? ''])

<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $zone->zone_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.zones.partials._zone_button_register',['backRoute' => url()->previous(), 'zone_id' => $zone_id ])
        <div class="row">
            @include('shineCompliance.zones.partials._zone_sidebar', ['active_summary' => true])

            @if($overall)
            <div class="col-md-9 pr-0 pl-0">
                    @include('shineCompliance.tables.property_overall_summary', [
                        'title' => 'Property Overall Register Summary',
                        'tableId' => 'property_overall_summary',
                        'data' => $register_data,
                        'register' => true,
                        'plus_link' => false,
                        'normalTable' => true,
                        'collapsed' => false
                        ])

                    @include('shineCompliance.tables.property_register_summary', [
                            'title' => 'Property Group Overall Decommissioned Summary',
                            'tableId' => 'decommission_property_register_summary',
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
