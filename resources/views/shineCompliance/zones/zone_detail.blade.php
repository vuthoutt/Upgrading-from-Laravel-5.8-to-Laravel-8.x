@extends('shineCompliance.layouts.app')

@section('content')
    @include('shineCompliance.partials.nav',['breadCrumb' => 'zone_properties_shineCompliance', 'color' => 'red', 'data' => $zone ?? ''])

    <div class="container-cus prism-content pad-up" >
        <div class="row">
            <h3 class="title-row">{{$zone->zone_name ?? ''}}</h3>
        </div>
        <div class="main-content mar-up">
            @include('shineCompliance.properties.partials._zone_button',[
                        'backRoute' => url()->previous(),
                        'decommission' => $zone->decommissioned ?? 0,
                        'editRoute' => $can_update ? '#edit-zone' : false,
                        'data_target' => '#decommission_group',
                        'route_decommission'=> route('shineCompliance.zone.decommission',['zone_id' => $zone->id])
                        ])
            @include('shineCompliance.modals.add_zone',['modal_id' => 'edit-zone','action' => 'edit', 'url' => route('shineCompliance.zone.update_or_create'),'client_id' => $zone->client_id, 'zone' => $zone ])
            @include('shineCompliance.modals.decommission_group',[
                                            'modal_id' => 'decommission_group',
                                            'header' => 'Decommission Property Group Warning',
                                            'url' => route('shineCompliance.zone.decommission',['zone_id' => $zone->id]),
                                            'data' => $decommissioned_reason_group
                                            ])
            <div class="row">
                <div class="col-md-3 pl-0">
                    <div  class="card-data mar-up">
                        <div style="width:100%;" >
                            <ul class="list-group">
                                <div class="list-group-img">
                                    <img src="{{ asset(\ComplianceHelpers::getFileImage($zone->id, ZONE_PHOTO)) }}"  width="100%" height="230px" alt="">
                                </div>
                                <div class="list-group-button">
                                    <a href="{{ route('retrive_image',['type'=>  ZONE_PHOTO ,'id'=> $zone->id ?? 0, 'is_view' => true ]) }}" target="_blank"><button class="list-group-btn" style="margin-left:0px" title="Open"><i class="fa fa-image fa-2x"></i></button></a>
                                    <a href="{{ route('retrive_image',['type'=>  ZONE_PHOTO ,'id'=> $zone->id ?? 0]) }}"><button class="list-group-btn" title="Download"><i class="fa fa-download fa-2x"></i></button></a>
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
                <div class="col-md-9">
                    <div class="list-data property-detail mt-2" >
                        @if($zone->decommissioned != ZONE_UNDECOMMISSION)
                            <div class="property-warning">
                                <div class="non-border-radius spanWarningDemolished mr-2 mb-2">
                                    <strong>
                                        <em>{{ $zone->decommissionedReason->description ?? "Property Group No Longer under Management" }}</em>
                                    </strong>
                                </div>
                            </div>
                        @endif
                        <div class="row">
    {{--                        <div class="card-data mar-up"></div>--}}
                            <div class="col-md-6 property-detail-info-first">
                                <div class="card h-100 discard-border-radius">
                                    <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                                    <div class="card-body " style="padding: 15px;">
                                        @include('shineCompliance.forms.form_property_details',['title' => 'Group Name:', 'data'=> $zone->zone_name ?? ''])
                                        @include('shineCompliance.forms.form_property_details',['title' => 'Shine:', 'data' => $zone->reference ?? null])
                                        @include('shineCompliance.forms.form_property_details',['title' => 'Properties:', 'data' => $zone->property->count() ?? null])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
