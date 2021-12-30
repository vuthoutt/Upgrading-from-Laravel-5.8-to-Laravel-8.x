@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'nonconformities_equipment_detail', 'color' => 'red', 'data' =>  $equipment])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">Non-conformities</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._system_button', [
            'backRoute' =>  route('shineCompliance.property.equipment',['property_id' => $equipment->property_id ?? 0]),
            'route_decommission' =>  route('shineCompliance.equipment.decommission',['id' => $equipment->id ?? 0]),
            'route_recommission' =>  route('shineCompliance.equipment.decommission',['id' => $equipment->id ?? 0]),
            'decommission' => $equipment->decommissioned ?? 0,
            'editRoute'  => $can_update ? route('shineCompliance.equipment.get_edit_equipment',['id' => $equipment->id ?? 0]) : false
            ])

        <div class="row">
            @include('shineCompliance.properties.partials._property_system_programme_sidebar',
            ['image' =>  asset(\ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO)),
            'id' => $equipment->id ?? 0,
            'route' => 'shineCompliance.register_equipment.detail',
            'route_document' => route('shineCompliance.equipment.document.list', ['id'=>$equipment->id ?? 0, 'type'=> DOCUMENT_EQUIPMENT_TYPE]),
            'display_type' => EQUIPMENT_TYPE
            ])
            <div class="col-md-9">
                @include('shineCompliance.tables.equipment_nonconformities', [
                    'title' => 'Equipment Nonconformity Summary',
                    'tableId' => 'nonconformity_equipment',
                    'over_all_text' => '',
                    'collapsed' => false,
                    'plus_link' => false,
                    'data' => $nonconformities,
                    'order_table' => "[]"
                    ])
            </div>

        </div>
    </div>
</div>
@endsection
