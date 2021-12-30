@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'register_zone_gas', 'color' => 'red', 'data' =>  $zone])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $zone->zone_name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.zones.partials._zone_button_register',['backRoute' => url()->previous(), 'zone_id' => $zone_id ])

        <div class="row">
            @include('shineCompliance.zones.partials._zone_sidebar', ['active_summary' => true])
            <div class="column-right">

            </div>
        </div>
    </div>
</div>
@endsection
