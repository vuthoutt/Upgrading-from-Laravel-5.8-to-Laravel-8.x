@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'list_location', 'color' => 'red', 'data' => $area ?? ''])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $area->title_presentation ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_search', [
            'backRoute' => route('shineCompliance.property.list_area', ['property_id' => $area->property_id ?? 0]),
            'addRoute' =>  $can_add_new ? route('shineCompliance.location.get_add', ['area_id' => $area_id]) : false,
            'search_action' => route('shineCompliance.location.list', ['area_id' => $area_id]),
            'addFilter' => true,'is_locked' => (($area->is_locked === AREA_LOCKED)? TRUE : FALSE),
            ])

        <div class="row">
            @include('shineCompliance.properties.area.partials._area_sidebar', ['property_id' => $area->property_id ?? 0])

            <div class="list-data col-md-9 pl-0 pr-0" style="{{ $area->is_locked === AREA_LOCKED ? "margin-top: 15px;margin-left:10px": '' }}"  >
                @if($area->is_locked == AREA_LOCKED)
                <div class="btn-toolbar mb-3" role="toolbar">
                    <button id="danger-button" class="btn btn-danger dropdown-toggle box-shadow--2dp f13"
                        type="button" data-toggle="dropdown" style="margin-right: 10px">Red Warnings
                        <span class="caret" style="padding-right: 20px;"></span>
                    </button>
                    <div class="dropdown-menu" style="background-color: #ffe3e6;max-height: 600px;overflow: auto;padding: 0!important;" >
                            <span class="dropdown-item f13 f13" style="color: #bd2130!important;" ><strong>Area/Floor is view only while surveying or assessing is in progress</strong></span>
                            <div class="dropdown-divider" style="border-color: white;padding: 0!important;"></div>
                    </div>
                </div>
                @endif
                @if(count($locations))
                    <div class="card-data mar-up">
                        @foreach($locations as $key => $location)
                            <div class="card card-img card-img-deco col-md-4" style="padding:0;">
                                <a href="{{ route('shineCompliance.location.detail', ['id' => $location->id]) }}" >
                                    @if($location->decommissioned === LOCATION_UNDECOMMISSION)
                                        <div class="spanWarningSuccess mb-2" style="position: absolute;right: 0;font-size: 15px;border-radius:unset;text-align: center;width: 45% !important;">
                                            <strong><em>Live</em></strong>
                                        </div>
                                        @if($location->is_locked === LOCATION_LOCKED)
                                            <span style='font-size:20px;color:red;position: absolute;left: 10px;border-radius:unset;text-align: left;width: 45% !important;border:none;'><i class='fas fa-lock'></i></span>
                                        @endif
                                    @else
                                        <div class="spanWarningSurveying mb-2" style="position: absolute;right: 0;font-size: 15px;color: #b94a48;border-radius:unset;text-align: center;width: 45% !important;">
                                            <strong><em>Decommissioned</em></strong>
                                        </div>
                                    @endif
                                    <img class="card-img-top unset-border" src="{{ asset(\ComplianceHelpers::getFileImage($location->id, LOCATION_IMAGE)) }}" alt="Card image" height="300px">
                                    <div class="card-body card-body-border card-padding" style="height:250px" >
                                        <div class="property-detail-attribute mt-2">
                                            <strong class="str-color">{{ $location->title_presentation ?? '' }}</strong>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6">Reference:</label>
                                            <div class="col-md-6">
                                                {{ $location->location_reference ?? '' }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6">Decription:</label>
                                            <div class="col-md-6">
                                                {{ $location->description ?? '' }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6 str-color">Floor:</label>
                                            <div class="col-md-6 str-color">
                                                {{ $location->area->title_presentation ?? '' }}
                                            </div>
                                        </div>
                                        <div class="row mt-1 mb-1">
                                            <strong class="col-md-6">Shine:</strong>
                                            <div class="col-md-6">
                                                <strong>{{ $location->reference ?? '' }}</strong>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6 str-color">Accessibility:</label>
                                            <div class="col-md-6 str-color">
                                                {{ $location->state_text ?? '' }}
                                            </div>
                                        </div>
                                        @if($location->state != AREA_ACCESSIBLE_STATE)
                                        <div class="row">
                                            <label class="col-md-6 str-color">Inaccessible Reason:</label>
                                            <div class="col-md-6 str-color">
                                                {{ \CommonHelpers::getLocationVoidDetails($location->locationInfo->reason_inaccess_key ?? 0, $location->locationInfo->reason_inaccess_other ?? '' ) }}
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                            @if( ($key + 1)%3 == 0)
                                </div>
                                <div class="card-data mar-up">
                            @endif
                        @endforeach
                    </div>
                    <div class="pagination-right mar-up">
                        <nav aria-label="...">
                            {{ $locations->appends(request()->except('_'))->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('shineCompliance.zones.partials._filter', ['accessibility' => [LOCATION_STATE_INACCESSIBLE,LOCATION_STATE_ACCESSIBLE], 'type' => FILTER_LOCATION])
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
