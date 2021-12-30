@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'register_gas', 'color' => 'red', 'data' =>  $property])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $property->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_register',['backRoute' => url()->previous(), 'property_id' => $property_id ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar', ['active_summary' => true])
            <div class="col-md-9 pr-0 pl-0">

            </div>
        </div>
    </div>
</div>
@endsection
