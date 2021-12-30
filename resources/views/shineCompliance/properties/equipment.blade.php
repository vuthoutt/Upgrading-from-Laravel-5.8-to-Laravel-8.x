@extends('shineCompliance.layouts.app')

@section('content')
    @if(isset($property->parents))
        @include('shineCompliance.partials.nav',['breadCrumb' => 'sub_equipment_pro', 'color' => 'red', 'data' =>  $property])
    @else
        @include('shineCompliance.partials.nav',['breadCrumb' => 'equipment_pro', 'color' => 'red', 'data' =>  $property])
    @endif
    <div class="container-cus prism-content pad-up">
        <div class="row">
            <h3 class="title-row">{{ $property->name ?? '' }}</h3>
        </div>
        <div class="main-content mar-up">
            @include('shineCompliance.properties.partials._property_button_search',[
                'backRoute' => route('shineCompliance.property.property_detail',$property->id ?? 0),
                'addRoute'=>  $can_add_new ? route('shineCompliance.equipment.get_add_equipment',['property_id' => $property->id]) : false
                ])

            <div class="row">
                @include('shineCompliance.properties.partials._property_sidebar',['property_id' => $property->id ?? 0])
                <div class="list-data col-md-9 pl-0 pr-0" style="padding: 0" >
                    @if(count($equipments))
                    <div class="card-data mar-up">
                            @foreach($equipments as $key => $equipment)
                                <div class="card card-img card-img-deco col-md-4" style="padding:0;">
                                    <a href="{{ route('shineCompliance.register_equipment.detail', ['id' => $equipment->id]) }}" >
                                        <img class="card-img-top unset-border" src="{{ asset(\ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO)) }}" alt="Card image" height="300px">
                                        <div class="card-body card-body-border card-padding" style="padding: 15px;">
                                            <div class="row property-detail-attribute mt-2">
                                                <div class="col-6 property-detail-attribute-label">Equipment Name:</div>
                                                <div class="col-6">{{ $equipment->name ?? '' }}</div>
                                            </div>
                                            <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Shine:</div>
                                                <div class="col-6">{{ $equipment->reference ?? '' }}</div>
                                            </div>
                                            <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Type:</div>
                                                <div class="col-6"> {{ $equipment->equipmentType->description ?? '' }}</div>
                                            </div>
                                            <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Status:</div>
                                                <div class="col-6"> {{ ($equipment->decommissioned == 1) ? 'Decommissioned' : 'Live' }}</div>
                                            </div>
                                           <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Accessibility:</div>
                                                <div class="col-6"> {{ $equipment->state_text ?? '' }}</div>
                                            </div>
                                           <div class="row property-detail-attribute">
                                                <div class="col-6 property-detail-attribute-label">Inaccessible Reason:</div>
                                                <div class="col-6"> {{ $equipment->reason ?? '' }}</div>
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
                            {{ $equipments->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
