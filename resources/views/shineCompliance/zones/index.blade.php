@extends('shineCompliance.layouts.app')

@section('content')
    @include('shineCompliance.partials.nav', ['breadCrumb' => 'list_zone', 'color' => 'red'])
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $client->name ?? '' }}</h3>
        </div>
        <div class="main-content mar-up">
           @include('shineCompliance.properties.partials._property_button_search',
            ['backRoute' => route('zone_map'), 'addRoute'=> $can_add_new ? '#add-zone' : false, 'addFilter' => false, 'search_action' => route('shineCompliance.zone', ['client_id' => $client->id ?? 0])])
            @include('shineCompliance.modals.add_zone',['color' => 'red', 'modal_id' => 'add-zone','action' => 'add', 'url' => route('shineCompliance.zone.update_or_create'),'client_id' => $client->id ])
            <div class="list-data row">
                @include('shineCompliance.properties.partials._zone_sidebar')
                <div class="col-md-9" style="padding: 0" >
                    @if(count($zones))
                    <div class="card-data mar-up">
                            @foreach($zones as $key => $zone)
                                {{-- <div class="card card-img card-img-deco col-md-4" style="padding:0;"> --}}
                                    <a href="{{ route('shineCompliance.zone.details', ['id' => $zone->id]) }}"  class="card card-img card-img-deco col-md-3" style="padding:0">
                                        <img class="card-img-top unset-border" src="{{ asset(\ComplianceHelpers::getFileImage($zone->id, ZONE_PHOTO)) }}" alt="Card image" height="250px">
                                        <div class="card-body card-body-border card-padding" >
                                            <div class="property-detail-attribute mt-3">
                                                <strong class="str-color">{{ $zone->zone_name ?? '' }}</strong>
                                            </div>
                                            <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Shine:</div>
                                                <div class="col-6">{{ $zone->reference ?? '' }}</div>
                                            </div>
                                           <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Properties:</div>
                                                <div class="col-6"> {{ $zone->countAllProperty() ?? 0 }}</div>
                                            </div>
                                        </div>
                                    </a>
                                {{-- </div> --}}
                                @if( ($key + 1)%3 == 0)
                                    </div>
                                    <div class="card-data mar-up">
                                @endif
                            @endforeach
                    </div>

                    <div class="pagination-right mar-up">
                        <nav aria-label="...">
                             {{ $zones->appends(['q' => request()->input('q')])->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
