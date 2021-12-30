@extends('layouts.app')
@section('content')
@if(env('SETTING_PROPERTY_MAP'))
@include('partials.nav', ['breadCrumb' => 'zone_map_child_detail','data' => $breadcrumb_data])
@else
@include('partials.nav', ['breadCrumb' => 'zone_group','data' => $breadcrumb_data])
@endif
<div class="container prism-content">
<h3>{{ $zone->zone_name }}</h3>
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
        <div class="tab-content">
            <div class="row offset-top40"  style="padding-left: 12px;">
                <div class="col-md-12">
                    @if(\CommonHelpers::checkFile($zone->id, ZONE_PHOTO))
                    <div class="col-md-12 client-image-show mb-3">
                        <img class="image-signature" src="{{ CommonHelpers::getFile($zone->id, ZONE_PHOTO) }}">
                    </div>
                    <div class="col-md-12 client-image-show mb-3">
                        <a title="Download Property Group Photograph" href="{{ route('retrive_image',['type'=>  ZONE_PHOTO ,'id'=> $zone->id ]) }}" class="btn download-btn"><i class="fa fa-download"></i></a>
                    </div>
                    @endif
                    @include('forms.form_text',['title' => 'Shine Reference:', 'data' => optional($zone)->reference ])
                    @include('forms.form_text',['title' => 'Group Name:', 'data' => optional($zone)->zone_name ])
                @if(\CommonHelpers::isSystemClient())
                    @include('modals.edit_zone',['color' => 'red', 'modal_id' => 'edit-zone','action' => 'edit', 'url' => route('zone.edit',['zone_id' => $zone->id]), 'zone' => $zone,'client_id' => $zone->client_id ])
                    <a  class="btn btn_long_size light_grey_gradient mt-3" data-toggle="modal" data-target="#edit-zone"><strong>Edit</strong></a>
                @endif
                </div>
            </div>
            <div id="property-list" class="container tab-pane active">
                @include('zones.property_listing')
            </div>
            <div id="decommissioned" class="container tab-pane fade">
                @include('zones.decommissioned')
            </div>
        </div>
    </div>
</div>
@endsection
