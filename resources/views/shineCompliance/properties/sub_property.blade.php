@extends('shineCompliance.layouts.app')

@section('content')
    @if(isset($property->parents))
        @include('shineCompliance.partials.nav', ['breadCrumb' => 'sub_list_property', 'color' => 'red', 'data' => $property])
    @else
        @include('shineCompliance.partials.nav', ['breadCrumb' => 'sub_list', 'color' => 'red', 'data' => $property])
    @endif
    <div class="container-cus prism-content pad-up" >
        <div class="row">
            <h3 class="title-row">{{$property->name ?? ''}}</h3>
        </div>
        <div class="main-content mar-up">
            @if(!$property->parents)
                @include('shineCompliance.properties.partials._property_button_search',['backRoute' => route('shineCompliance.zone.details',
                ['zone_id' => $property->zone_id]),'addRoute' => $can_add_new ? route('shineCompliance.property.get_add_sub_property',['zone_id' => $property->zone_id ?? 0,'property_id' => $property->id ?? 0]) : false ])
           @else
                @include('shineCompliance.properties.partials._property_button_search',['backRoute' => route('shineCompliance.property.sub_property',
                ['id' => $property->id]),'addRoute' => $can_add_new ? route('shineCompliance.property.get_add_sub_property',['zone_id' => $property->zone_id ?? 0,'property_id' => $property->id ?? 0]) : false ])
            @endif
            <div class="row">
                @include('shineCompliance.properties.partials._property_sidebar',['property_id' => $property->id])
                <div class="col-md-9 list-data" style="padding: 0" >
                    @if(count($sub_properties) > 0)
                        <div  class="card-data mar-up">
                            @foreach($sub_properties as $key => $sub_property)
                                <a href="{{ route('shineCompliance.property.property_detail', $sub_property->id) }}" class="card card-img card-img-deco col-md-4" style="padding:0">
                                    <img class="card-img-top unset-border" src="{{ \ComplianceHelpers::getFileImage($sub_property->id, SUB_PROPERTY_IMAGE) }}" alt="Card image" height="300px">
                                    <div class="card-body card-body-border card-padding" >
                                        <div class="property-detail-attribute mt-2">
                                            <strong class="str-color">{{$sub_property->name ?? ''}}</strong>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6"  >UPRN:</label>
                                            <div class="col-md-6" >
                                                {{$sub_property->property_reference ?? ''}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6" >Parent:</label>
                                            <div class="col-md-6" >
                                                {{$sub_property->parents->name ?? ''}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6">Estate Code:</label>
                                            <div class="col-md-6">
                                                {{$sub_property->estate_code ?? ''}}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <label class="col-md-6" >Shine:</label>
                                            <div class="col-md-6">
                                                {{$sub_property->reference ?? ''}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                @if(($key + 1)%3 == 0 && !$loop->last )
                        </div>
                        <div  class="card-data mar-up">
                            @endif
                            @endforeach
                        </div>
                        <div class="pagination-right mar-up">
                            <nav aria-label="...">
                                {{ $sub_properties->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="add" style="position: fixed !important;">
        <div class="filter">
            <div class="form-select-button">
                <button class="btn-remove" id="close-form" ><i class="fa fa-remove fa-2x"></i></button>
                <h2>Filters</h2>
                <p> <strong>Asset Class</strong></p>
                <ul class="select-button">
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Block</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Corporate Property</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Comercial Building</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Domestic Property</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Estate</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Out Building</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Outdoor Space</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Non-Domestic</span>
                    </li>
                </ul>
            </div>
            <div class="form-select-button">
                <p><strong>Property Status</strong></p>
                <ul class="select-button">
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Derelict</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Operational</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Vacant</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">No Longer under Managenment</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Demolished</span>
                    </li>
                </ul>
            </div>
            <div class="form-select-button">
                <p><strong>Included Attributes</strong></p>
                <ul class="select-button">
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">AOV</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Dry Riser</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Fire Alarm</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Fire Extinguishers</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Sprinklers</span>
                    </li>
                </ul>
            </div>
            <div class="form-select-button">
                <p><strong>Identified Risks</strong></p>
                <ul class="select-button">
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Inaccessible Room/locations</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Inaccessible Voids</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Asbestos Containing Materials</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Hazards</span>
                    </li>
                    <li>
                        <label class="switch">
                            <input type="checkbox" checked>
                            <span class="slider round"></span>
                        </label>
                        <span class="text-select">Nonconformity</span>
                    </li>
                </ul>
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
