@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'equipment_detail', 'color' => 'red', 'data' => $equipment ?? '']))
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $equipment->name ?? '' }}</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._system_button', [
            'backRoute' =>  route('shineCompliance.equipment.list',['system_id' => $equipment->system_id ?? 0]),
            'route_decommission' =>  route('shineCompliance.equipment.decommission',['id' => $equipment->id ?? 0]),
            'route_recommission' =>  route('shineCompliance.equipment.decommission',['id' => $equipment->id ?? 0]),
            'decommission' => $equipment->decommissioned ?? 0,
            'editRoute'  => $can_update ? route('shineCompliance.equipment.get_edit',['id' => $equipment->id ?? 0]) : false
            ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_system_programme_sidebar',
            ['image' =>  asset(\ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO)),
            'id' => $equipment->id ?? 0,
            'route' => 'shineCompliance.register_equipment.detail',
            'route_document' => route('shineCompliance.equipment.document.list', ['id'=>$equipment->id ?? 0, 'type'=> DOCUMENT_EQUIPMENT_TYPE]),
            'display_type' => EQUIPMENT_TYPE
            ])

        <div class="col-md-9 pl-0 pr-0" style="padding: 0" >
            <div class="card-data mar-up">
                <div class="col-md-6">
                    <div class="card discard-border-radius">
                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                        <div class="card-body" style="padding: 15px;">
                            <div class="row property-detail-attribute">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
