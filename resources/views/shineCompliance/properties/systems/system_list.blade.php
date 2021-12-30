@extends('shineCompliance.layouts.app')

@section('content')
    @if(isset($property->parents))
        @include('shineCompliance.partials.nav',['breadCrumb' => 'system_list_property', 'color' => 'red', 'data' => $property ?? ''])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'system_list', 'color' => 'red', 'data' => $property ?? ''])
    @endif
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $property->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @if(isset($property->parents))
            @include('shineCompliance.properties.partials._property_button_search',
            [ 'backRoute' =>  route('shineCompliance.property.property_detail',['zone_id' => $property->parent_id ?? 0 ]),
                'addRoute' =>  $can_add_new ? route('shineCompliance.systems.get_add',['property_id' => $property->id ?? 0 ]) : false,
                'search_action' => route('shineCompliance.systems.list',['property_id' => $property->id ?? 0 ])
            ])
        @else
            @include('shineCompliance.properties.partials._property_button_search',
            [ 'backRoute' =>  route('shineCompliance.property.property_detail',['zone_id' => $property->id ?? 0 ]),
                'addRoute' =>  $can_add_new ? route('shineCompliance.systems.get_add',['property_id' => $property->id ?? 0 ]) : false,
                'search_action' =>route('shineCompliance.systems.list',['property_id' => $property->id ?? 0 ])
            ])
        @endif
        <div class="row">
            @include('shineCompliance.properties.partials._property_sidebar',['property_id' => $property->id ?? 0 ,])

            <div class="list-data col-md-9 pl-0 pr-0" style="padding: 0" >
                @if(count($systems))
                    <div class="card-data mar-up">
                        @foreach($systems as $key => $system)
                            <div class="card card-img card-img-deco col-md-4" style="padding:0;">
                                <a href="{{ route('shineCompliance.systems.detail', ['id' => $system->id]) }}" >
                                    <img class="card-img-top unset-border" src="{{ asset(\ComplianceHelpers::getSystemFile($system->id, COMPLIANCE_SYSTEM_PHOTO)) }}" alt="Card image" height="300px">
                                    <div class="card-body card-body-border card-padding" >
                                        <div class="property-detail-attribute mt-2">
                                            <strong class="str-color">{{ $system->name ?? '' }}</strong>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6 fs-8pt">Shine:</label>
                                            <div class="col-md-6 fs-8pt">
                                                {{ $system->reference ?? '' }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6 fs-8pt">System Type:</label>
                                            <div class="col-md-6 fs-8pt">
                                                {{ $system->systemType->description ?? '' }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6 fs-8pt">Classification:</label>
                                            <div class="col-md-6 fs-8pt">
                                                {{ $system->systemClassification->description ?? '' }}
                                            </div>
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
                            {{ $systems->links() }}
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
