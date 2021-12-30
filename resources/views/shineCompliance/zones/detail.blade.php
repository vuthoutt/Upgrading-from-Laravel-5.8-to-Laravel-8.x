@extends('shineCompliance.layouts.app')

@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'zone_detail_shineCompliance', 'color' => 'red', 'data' => $zone ?? ''])

    <div class="container-cus prism-content pad-up" >
        <div class="row">
            <h3 class="title-row">{{$zone->zone_name ?? ''}}</h3>
        </div>
        <div class="main-content mar-up">
            @include('shineCompliance.properties.partials._property_button_search',[
                'backRoute' => route('zone_map_child', ['zone_id' => $zone->parent_id, 'client_id' => 1]),
                'addRoute' => $can_add_new ? route('shineCompliance.property.get_add',['zone_id'=> $zone->id ?? 0]) : false,
                'search_action' => route('shineCompliance.zone.details', ['id' => $zone->id, 'client_id' => 1]),
                'addFilter' => true])
            <div class="row">
                <div class="col-md-3 pl-0">
                    <div  class="card-data mar-up">
                        <div style="width:100%; " >
                            <ul class="list-group">
                                <div class="list-group-img">
                                    <img src="{{ asset(\ComplianceHelpers::getFileImage($zone->id, ZONE_PHOTO)) }}"  width="100%" height="230px" alt="">
                                </div>
                                <div class="list-group-button">
                                    <a href="{{ route('retrive_image',['type'=>  ZONE_PHOTO ,'id'=> $zone->id ?? 0, 'is_view' => true ]) }}" target="_blank"><button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button></a>
                                    <a href="{{ route('retrive_image',['type'=>  ZONE_PHOTO ,'id'=> $zone->id ?? 0]) }}" target="_blank"><button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
{{--                                    <button class="list-group-btn"><i class="fa fa-cubes fa-2x"></i></button>--}}
{{--                                    <button class="list-group-btn"><i class="fa fa-qrcode fa-2x"></i></button>--}}
                                </div>
                                <a href="{{route('shineCompliance.zone.zone_details', ['id' => $zone->id ?? 0])}}" class="list-group-item list-group-item-action {{ Route::currentRouteName() == 'shineCompliance.zone.zone_details' ? 'list-group-active' : 'list-group-item-danger'}} list-group-details" >Details</a>
                                @if(\CompliancePrivilege::checkRegisterDataPermission())
                                    <a href="{{ route('shineCompliance.zone.register', $zone->id ?? 0) }}" class="list-group-item list-group-item-action {{ isset($active_summary) ? 'list-group-active' : 'list-group-item-danger' }} list-group-details">Register</a>
                                @endif
                                <a href="{{route('shineCompliance.zone.details', ['id' => $zone->id ?? 0])}}" class="list-group-item {{ Route::currentRouteName() == 'shineCompliance.zone.details' ? 'list-group-active' : 'list-group-item-danger'}} list-group-item-action list-group-details border-unset" disabled>Properties</a>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="list-data col-md-9" style="padding: 0" >
                    @if(count($properties) > 0)
                        <div  class="card-data mar-up" style="position: relative">
                        @foreach($properties as $key => $property)
                                <a href="{{ route('shineCompliance.property.property_detail', $property->id) }}" class="card card-img card-img-deco col-md-4" style="padding:0">
                                    <img class="card-img-top unset-border" src="{{ \ComplianceHelpers::getFileImage($property->id, PROPERTY_IMAGE) }}" alt="Card image" height="300px">
                                    @if($property->decommissioned === PROPERTY_UNDECOMMISSION)
                                        <div class="spanWarningSuccess mb-2" style="position: absolute;right: 0;font-size: 15px;border-radius:unset;text-align: center;width: 45% !important;">
                                            <strong><em>Live</em></strong>
                                        </div>
                                    @else
                                        <div class="spanWarningSurveying mb-2" style="position: absolute;right: 0;font-size: 15px;color: #b94a48;border-radius:unset;text-align: center;width: 45% !important;">
                                            <strong><em>Decommissioned</em></strong>
                                        </div>
                                    @endif
                                    <div class="card-body card-body-border card-padding " >
                                        <div class="property-detail-attribute mt-2">
                                            <strong class="str-color">{{$property->name ?? ''}}</strong>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6"  >UPRN:</label>
                                            <div class="col-md-6" >
                                                {{$property->property_reference ?? ''}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6">Block:</label>
                                            <div class="col-md-6" >
                                                {{$property->pblock ?? ''}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6" >Parent:</label>
                                            <div class="col-md-6" >
                                                {{$property->parents->name ?? ''}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6">Estate Code:</label>
                                            <div class="col-md-6">
                                                {{$property->estate_code ?? ''}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6" >Shine:</label>
                                            <div class="col-md-6">
                                                {{$property->reference ?? ''}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @if( ($key + 1)%3 == 0 && !$loop->last )
                            </div>
                            <div  class="card-data mar-up">
                            @endif
                        @endforeach
                        </div>
                        <div class="pagination-right mar-up">
                            <nav aria-label="...">
                                {{ $properties->appends(request()->except('_'))->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('shineCompliance.zones.partials._filter', ['asset_class' => $asset_class ?? [], 'property_status' => $property_status, 'system_types' => $system_types, 'equipment_types' => $equipment_types, 'type' => FILTER_PROPERTY, 'type' => FILTER_PROPERTY])
@endsection
@push('javascript')
    <script>
        $(document).ready(function(){
            $('#filter').click( function(e) {
                $('.add').toggleClass('add-aimation');
            });
            $('#close-form').click( function() {
                $('.add').removeClass('add-aimation');
            });
        });
    </script>
@endpush
