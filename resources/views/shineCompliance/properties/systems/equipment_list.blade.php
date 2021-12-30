@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'equipment_list', 'color' => 'red', 'data' => $system])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $system->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._property_button_search',
        [   'backRoute' => route('shineCompliance.systems.list',$system->property_id ?? 0),
            'addRoute' => $can_add_new ? route('shineCompliance.equipment.get_add_equipment',['property_id' => $system->property_id ?? 0, 'system_id' => $system->id ?? 0]) : false,
            'search_action' => route('shineCompliance.equipment.list',['system' => $system->id])
        ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_aov_system_sidebar',['image' =>  asset(\ComplianceHelpers::getSystemFile($system_id, COMPLIANCE_SYSTEM_PHOTO)),
                    'route_document' => route('shineCompliance.system.document.list', ['id'=>$system_id, 'type'=> DOCUMENT_SYSTEM_TYPE])])

            <div class="list-data col-md-9 pl-0 pr-0" style="padding: 0" >
                @if(count($equipments))
                <div class="card-data mar-up">
                        @foreach($equipments as $key => $equipment)
                            <div class="card card-img card-img-deco col-md-4" style="padding:0;">
                                <a href="{{ route('shineCompliance.register_equipment.detail', ['id' => $equipment->id]) }}" >
                                    <img class="card-img-top unset-border" src="{{ asset(\ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO)) }}" alt="Card image" height="300px">
                                    <div class="card-body card-body-border card-padding" style="padding: 15px;">
                                        <div class="row property-detail-attribute mt-2">
                                            <div class="col-6 property-detail-attribute-label fs-8pt">Equipment Name:</div>
                                            <div class="col-6 fs-8pt">{{ $equipment->name ?? '' }}</div>
                                        </div>
                                        <div class="row property-detail-attribute">
                                            <div class="col-6 property-detail-attribute-label fs-8pt">Shine:</div>
                                            <div class="col-6 fs-8pt">{{ $equipment->reference ?? '' }}</div>
                                        </div>
                                        <div class="row property-detail-attribute">
                                            <div class="col-6 property-detail-attribute-label fs-8pt">Type:</div>
                                            <div class="col-6 fs-8pt"> {{ $equipment->equipmentType->description ?? '' }}</div>
                                        </div>
                                        <div class="row property-detail-attribute">
                                            <div class="col-6 property-detail-attribute-label fs-8pt">Status:</div>
                                            <div class="col-6 fs-8pt"> {{ ($equipment->decommissioned == 1) ? 'Decommissioned' : 'Live' }}</div>
                                        </div>
                                       <div class="row property-detail-attribute">
                                            <div class="col-6 property-detail-attribute-label fs-8pt">Accessibility:</div>
                                            <div class="col-6 fs-8pt"> {{ $equipment->state_text ?? '' }}</div>
                                        </div>
                                       <div class="row property-detail-attribute">
                                            <div class="col-6 property-detail-attribute-label fs-8pt">Inaccessible Reason:</div>
                                            <div class="col-6 fs-8pt"> {{ $equipment->reason ?? '' }}</div>
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
