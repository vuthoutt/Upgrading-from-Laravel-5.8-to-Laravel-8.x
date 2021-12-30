@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'hazard_equipment_detail', 'color' => 'red', 'data' =>  $equipment])
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">Hazards</h3>
    </div>
    <div class="main-content mar-up">
        @include('shineCompliance.properties.partials._equipment_button_register',['backRoute' => url()->previous(), 'id' => $equipment->id ])
        <div class="row">
            @include('shineCompliance.properties.partials._property_system_programme_sidebar',
           ['image' =>  asset(\ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO)),
           'id' => $equipment->id ?? 0,
           'route' => 'shineCompliance.register_equipment.detail',
           'route_document' => route('shineCompliance.equipment.document.list', ['id'=>$equipment->id ?? 0, 'type'=> DOCUMENT_EQUIPMENT_TYPE]),
           'display_type' => EQUIPMENT_TYPE
           ])

            @if($overall)
                <div class="col-md-9 pr-0 pl-0">
                        @include('shineCompliance.tables.property_overall_summary', [
                            'title' => 'Property Overall Register Summary',
                            'tableId' => 'property_overall_summary',
                            'data' => $register_data,
                            'register' => true,
                            'plus_link' => false,
                            'normalTable' => true,
                            'collapsed' => false
                            ])

                        @include('shineCompliance.tables.property_register_summary', [
                                'title' => 'Property Overall Decommissioned Summary',
                                'tableId' => 'decommission_property_register_summary',
                                'data' => $decommission_register_data,
                                'register' => true,
                                'plus_link' => false,
                                'normalTable' => true,
                                'collapsed' => true
                                ])
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
