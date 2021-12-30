@extends('layouts.app')
@section('content')
@include('partials.nav', ['breadCrumb' => 'zone_group','data' => $breadcrumb_data])

<div class="container prism-content">
    <div style="border-bottom:1px #dddddd solid;text-align: center;">
        <img width="150px" src="{{ CommonHelpers::getFile(1, CLIENT_LOGO) }}" alt="{{ env('APP_DOMAIN') ?? 'GSK' }}" style="margin-bottom: 30px;"/>
    </div>
    <h3 class="offset-top20">{{ $zone->zone_name }}</h3>
    <div class="main-content">
                <!-- Nav tabs -->
        <ul class="nav nav-pills red_gradient_nav" id="myTab">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#property-list"><strong>Property Listings</strong></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#decommissioned"><strong>Decommissioned</strong></a>
            </li>
        </ul>
        <div class="tab-content offset-top20">
            <div id="property-list" class="container tab-pane active">
                @include('zones.property_listing_operative')
            </div>
            <div id="decommissioned" class="container tab-pane fade">
                @include('zones.decommissioned_operative')
            </div>
        </div>
    </div>
</div>
@endsection

@push('javascript')
    <script>
        $(document).ready(function(){
            $(".name").each(function(){
                var showChar = 30;
                var text = $(this).text();

                if(text.length > 30){
                    text = text.substring(0, 29) + " ...";
                }
                $(this).text(text);
            });
        });
    </script>
@endpush
