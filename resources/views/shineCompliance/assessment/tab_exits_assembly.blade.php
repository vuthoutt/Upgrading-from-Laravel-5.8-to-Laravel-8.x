<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_exits_assembly_vehicle_park', [
            'title' => 'Assessment Assembly Points',
            'tableId' => 'assessment_assembly_points',
            'collapsed' => false,
            'plus_link' => ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
            'link' => route('shineCompliance.assessment.get_add_assembly_point',['property_id' => $data->property_id,'assess_id' => $data->id]),
            'data' => $assemblyPoints,
            'order_table' => "[]"
            ])
    </div>
</div>

<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_exits_assembly_vehicle_park', [
            'title' => 'Assessment Fire Exits',
            'tableId' => 'assessment_fire_exits',
            'collapsed' => false,
            'plus_link' => ($assessment->is_locked == 0) and $canBeUpdateSurvey ? true : false,
            'link' => route('shineCompliance.assessment.get_add_fire_exit',['property_id' => $data->property_id,'assess_id' => $data->id]),
            'data' => $fireExits,
            'order_table' => "[]"
            ])
    </div>
</div>

<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_exits_assembly_vehicle_park', [
            'title' => 'Assessment Decommissioned Assembly Points',
            'tableId' => 'assessment_decommissioned_assembly_points',
            'collapsed' => true,
            'plus_link' => false,
            'data' => $decommissionAssemblyPoints,
            'order_table' => "[]"
            ])
    </div>
</div>

<div class="row">
    <div class="col-12 mb-1">
        @include('shineCompliance.tables.assessment_exits_assembly_vehicle_park', [
            'title' => 'Assessment Decommissioned Fire Exits',
            'tableId' => 'assessment_decommissioned_fire_exits',
            'collapsed' => true,
            'plus_link' => false,
            'data' => $decommissionFireExits,
            'order_table' => "[]"
            ])
    </div>
</div>
