@extends('shineCompliance.layouts.app')

@section('content')
    @if(isset($property->parents))
        @include('shineCompliance.partials.nav',['breadCrumb' => 'sub_list_area', 'color' => 'red', 'data' => $property])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'list_area', 'color' => 'red', 'data' => $property])
    @endif
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $property->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_search', [
            'backRoute' => route('shineCompliance.zone.details',['zone_id' => $property->zone_id]),
            'addRoute' => $can_add_new ? "#add-area-register" : false ,
            'search_action' => route('shineCompliance.property.list_area', ['id' => $property->id]),
            'addFilter' => true])

        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar')

            <div class="list-data col-md-9 pl-0 pr-0" style="padding: 0" >
                @if(count($data_area) > 0)
                <div  class="card-data mar-up">
                    @foreach($data_area as $key => $area)
                        <div class="card card-img card-img-deco col-md-4" style="padding:0;">
                            <a href="{{ route('shineCompliance.property.area_detail',['property_id' => $property_id ?? '','area' => $area->id ?? '']) }}" >
                                @if($area->decommissioned === AREA_UNDECOMMISSION)
                                    <div class="spanWarningSuccess mb-2" style="position: absolute;right: 0;font-size: 15px;border-radius:unset;text-align: center;width: 45% !important;">
                                        <strong><em>Live</em></strong>
                                    </div>
                                    @if($area->is_locked === AREA_LOCKED)
                                        <span style='font-size:20px;color:red;position: absolute;left: 10px;border-radius:unset;text-align: left;width: 45% !important;border:none;'><i class='fas fa-lock'></i></span>
                                    @endif

                                @else
                                    <div class="spanWarningSurveying mb-2" style="position: absolute;right: 0;font-size: 15px;color: #b94a48;border-radius:unset;text-align: center;width: 45% !important;">
                                        <strong><em>Decommissioned</em></strong>
                                    </div>
                                @endif
                                <img class="card-img-top unset-border" src="{{ \ComplianceHelpers::getSystemFile($area->id,AREA_IMAGE) }}" alt="Card image" height="300px">
                                <div class="card-body card-body-border card-padding" style="height:220px">
                                    <div class="property-detail-attribute mt-2">
                                        <strong class="str-color">{{implode('- ', array_filter([$area->area_reference ?? '' , $area->description ?? '']))}}</strong>
                                    </div>
                                    <div class="row" >
                                        <label class="col-md-6">Reference:</label>
                                        <div class="col-md-6">
                                            {{ $area->area_reference ?? ''}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6">Description:</label>
                                        <div class="col-md-6">
                                            {{ $area->description ?? ''}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6">Shine:</label>
                                        <div class="col-md-6">
                                            {{ $area->reference ?? ''}}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-6">Accessibility:</label>
                                        <div class="col-md-6">
                                            {{ $area->state == 1 ? 'Accessible' : 'Inaccessible' }}
                                        </div>
                                    </div>
                                    @if($area->state != AREA_ACCESSIBLE_STATE)
                                    <div class="row">
                                        <label class="col-md-6">Inaccessibility Reason:</label>
                                        <div class="col-md-6">
                                            {{ $area->reasonArea->description ?? ''  }}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                             </a>
                        </div>
                @if(($key + 1)%3 == 0 && !$loop->last )
                    </div>
                    <div  class="card-data mar-up">
                @endif
                @endforeach
                </div>
                    <div class="pagination-right mar-up">
                        <nav aria-label="...">
                            {{ $data_area->appends(request()->except('_'))->links() }}
                        </nav>
                    </div>
            @endif
            </div>
        </div>
    </div>
</div>

@include('shineCompliance.zones.partials._filter', ['accessibility' => [AREA_ACCESSIBLE_STATE,AREA_INACCESSIBLE_STATE], 'type' => FILTER_AREA])
@include('shineCompliance.modals.add_area',['property_id' =>$property_id, 'modal_id' => 'add-area-register', 'action' => 'create' ,
            'url' => route('shineCompliance.ajax.create_area'), 'data_dropdown' => $data_reason])
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
