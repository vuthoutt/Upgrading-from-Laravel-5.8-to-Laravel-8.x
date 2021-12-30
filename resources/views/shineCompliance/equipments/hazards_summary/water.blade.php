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
                'display_type' => EQUIPMENT_TYPE,
                'active_summary' => true
                ])
                <div class="col-md-9 pr-0 pl-0">
                    @include('shineCompliance.tables.register_hazards', [
                        'title' => 'All Water Hazard Summary',
                        'tableId' => 'assessment_hazard',
                        'over_all_text' => '',
                        'collapsed' => false,
                        'plus_link' => false,
                        'data' => $register_data,
                        'order_table' => "[]"
                        ])

                    @include('shineCompliance.tables.register_hazards', [
                        'title' => 'All Decommissioned Water Hazard Summary',
                        'tableId' => 'assessment_decommissioned_hazard',
                        'over_all_text' => '',
                        'collapsed' => true,
                        'plus_link' => false,
                        'data' => $decommission_register_data,
                        'order_table' => "[]"
                        ])
                </div>

            </div>
        </div>
    </div>
@endsection
