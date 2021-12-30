@extends('shineCompliance.layouts.app')

@section('content')
@if($section)
    @include('shineCompliance.partials.nav',['breadCrumb' => 'register_zone_asbestos_summary', 'color' => 'red', 'data' =>  $zone])
@else
    @include('shineCompliance.partials.nav',['breadCrumb' => 'register_zone_asbestos', 'color' => 'red', 'data' =>  $zone])
@endif
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $zone->zone_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.zones.partials._zone_button_register',['backRoute' => url()->previous(), 'property_id' => $zone_id ])

        <div class="row">
            @include('shineCompliance.zones.partials._zone_sidebar', ['active_summary' => true])
            @if($section)
                <div class="col-md-9 pr-0 pl-0">
                    @if($section == TYPE_INACCESS_ROOM_SUMMARY)
                        @include('shineCompliance.tables.inaccess_locations',[
                                'title' => $zone->breadcrumb_title ?? '',
                                'tableId' => 'register-summary-table-location',
                                'collapsed' => false,
                                'plus_link' => false,
                                'data' => $data_summary
                            ])
                    @else
                        @include('shineCompliance.tables.item_summary', [
                            'title' => $zone->breadcrumb_title ?? '',
                            'tableId' => 'register-summary-table-item',
                            'collapsed' => false,
                            'plus_link' => false,
                            'data' => $data_summary,
                            'summary' => true,
                            'header' => ['Summary','Area/floor Reference','Room/location Reference','Product/debris type', 'MAS',''],
                            'order_table' => 'mas-risk-child'
                            ])

                    @endif
                </div>
            @else
                <div class="col-md-9 pr-0 pl-0">
                        @include('shineCompliance.tables.property_register_summary', [
                            'title' => 'Property Asbestos Register Summary',
                            'tableId' => 'property_register_summary',
                            'count' => $register_data["All ACM Items"]['number'] ?? 0,
                            'data' => $register_data,
                            'register' => true,
                            'plus_link' => false,
                            'normalTable' => true,
                            'collapsed' => false
                            ])
                    @include('shineCompliance.tables.property_register_summary', [
                            'title' => 'Property Group Decommissioned Asbestos Register Summary',
                            'tableId' => 'decommission_property_register_summary',
                            'count' => $decommission_register_data["All ACM Items"]['number'] ?? 0,
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
