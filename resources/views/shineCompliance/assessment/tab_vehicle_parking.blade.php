<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_exits_assembly_vehicle_park', [
            'title' => 'Assessment Vehicle Parking',
            'tableId' => 'assessment_vehicle_parking',
            'collapsed' => false,
            'plus_link' => ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
            'link' => route('shineCompliance.assessment.get_add_vehicle_parking',['property_id' => $data->property_id,'assess_id' => $data->id]),
            'data' => $vehicleParking,
            'order_table' => "[]"
            ])
    </div>
</div>
<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_exits_assembly_vehicle_park', [
            'title' => 'Assessment Decommissioned Vehicle Parking',
            'tableId' => 'assessment_decommissioned_vehicle_parking',
            'collapsed' => true,
            'plus_link' => false,
            'data' => $decommissionVehicleParking,
            'order_table' => "[]"
            ])
    </div>
</div>
