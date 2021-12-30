@extends('shineCompliance.layouts.app')

@section('content')
    @if(isset($property->parents))
        @include('shineCompliance.partials.nav', ['breadCrumb' => 'sub_register_vehicle_parking', 'color' => 'red', 'data' => $property])
    @else
        @include('shineCompliance.partials.nav', ['breadCrumb' => 'register_vehicle_parking', 'color' => 'red', 'data' => $property])
    @endif
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $property->name ?? '' }}</h3>
        </div>
        <div class="main-content mar-up">
            @if(isset($property->parents))
                @include('shineCompliance.properties.partials._property_button_search',[
                    'backRoute' => route('shineCompliance.property.property_detail',$property->parent_id ?? 0),
                    'addRoute'=>  $can_add_new ? route('shineCompliance.assessment.get_add_vehicle_parking',['property_id' => $property->id]) : false,
                    'search_action' => route('shineCompliance.property.parking',['property_id' => $property->id]),
                    ])
            @else
                @include('shineCompliance.properties.partials._property_button_search',[
                    'backRoute' => route('shineCompliance.property.property_detail',$property->id ?? 0),
                    'addRoute'=>  $can_add_new ? route('shineCompliance.assessment.get_add_vehicle_parking',['property_id' => $property->id]) : false,
                    'search_action' => route('shineCompliance.property.parking',['property_id' => $property->id]),
                    ])
            @endif
            <div class="row">
                @include('shineCompliance.properties.partials._property_sidebar',['property_id' => $property->id ?? 0])
                <div class="list-data col-md-9 pl-0 pr-0" style="padding: 0" >
                    @if(count($parkings))
                    <div class="card-data mar-up">
                            @foreach($parkings as $key => $parking)
                                <div class="card card-img card-img-deco col-md-4" style="padding:0;">
                                    <a href="{{ route('shineCompliance.assessment.get_vehicle_parking', ['id' => $parking->id]) }}" >
                                        <img class="card-img-top unset-border" src="{{ CommonHelpers::getFile($parking->id, VEHICLE_PARKING_PHOTO) }}" alt="Card image" height="300px">
                                        <div class="card-body card-body-border card-padding" style="padding: 15px;">
                                            <div class="row property-detail-attribute mt-2">
                                                <div class="col-6 property-detail-attribute-label">Vehicle Parking Name:</div>
                                                <div class="col-6">{{ $parking->name ?? '' }}</div>
                                            </div>
                                            <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Shine:</div>
                                                <div class="col-6">{{ $parking->reference ?? '' }}</div>
                                            </div>
                                            <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Status:</div>
                                                <div class="col-6"> {{ ($parking->decommissioned == 1) ? 'Decommissioned' : 'Live' }}</div>
                                            </div>
                                           <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Accessibility:</div>
                                                <div class="col-6"> {{ $parking->accessibility ? 'Yes' : 'No' }}</div>
                                            </div>
                                           <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Inaccessible Reason:</div>
                                                <div class="col-6"> {{ $parking->reasonNotAccessible->description ?? '' }}</div>
                                            </div>
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
                            {{ $parkings->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
