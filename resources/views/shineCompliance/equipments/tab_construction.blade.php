@php
        // new update for insulation_type and pipe_insulation
        $insulation_type = $equipment->equipmentConstruction->insulation_type ?? '';
        $insulation_type = explode(",",$insulation_type);

        // new update for insulation_type
        $pipe_insulation = $equipment->equipmentConstruction->pipe_insulation ?? '';
        $pipe_insulation = explode(",",$pipe_insulation);
@endphp
<div class="offset-top40">
    @include('shineCompliance.forms.form_text',['title' => 'Access:', 'data' => $equipment->equipmentConstruction->access ?? '','id' => 'access', 'class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Water Meter Fitted?', 'data' => $equipment->equipmentConstruction->water_meter_fitted ?? '', 'id' => 'water_meter_fitted', 'class_other' => 'equipment_section'])
    @if(isset($equipment->equipmentConstruction->water_meter_fitted) and ($equipment->equipmentConstruction->water_meter_fitted == 1))
        @include('shineCompliance.forms.form_text',['title' => 'Water Meter Reading:', 'id' => 'water_meter_reading', 'data' => $equipment->equipmentConstruction->water_meter_reading ?? '', 'class_other' => 'equipment_section'])
    @endif
    @include('shineCompliance.forms.form_text',['title' =>  'Material of Pipework:', 'data' => ComplianceHelpers::getEquipmentMultiselectWitOther($equipment->equipmentConstruction->material_of_pipework ?? '', $equipment->equipmentConstruction, 'material_of_pipework_other'),
                                                    'class_other' => 'equipment_section','id' => 'material_of_pipework'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Size of Pipework (mm):', 'name' => 'size_of_pipework', 'data' => $equipment->equipmentConstruction->size_of_pipework ?? '','measurement' => 'mm','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_text',['title' => 'Condition of Pipework:', 'class_other' => 'equipment_section', 'id' => 'condition_of_pipework',
                                                    'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->condition_of_pipework ?? 0)])
    @include('shineCompliance.forms.form_text',['title' => 'Aerosol Risk:', 'data' => $equipment->equipmentConstruction->aerosolRisk->description ?? '',
                                                                'class_other' => 'equipment_section','id' => 'aerosol_risk'])
    @include('shineCompliance.forms.form_text',['title' => 'Source:', 'data' => $equipment->equipmentConstruction->sourceRelation->description ?? '',
                                                                 'class_other' => 'equipment_section','id' => 'source'])
    @include('shineCompliance.forms.form_text',['title' => 'Source Accessibility:', 'data' => $equipment->equipmentConstruction->sourceAccessibility->description ?? '',
                                                                 'class_other' => 'equipment_section','id' => 'source_accessibility'])
    @include('shineCompliance.forms.form_text',['title' => 'Source Condition:', 'data' => $equipment->equipmentConstruction->sourceCondition->description ?? '',
                                                                 'class_other' => 'equipment_section','id' => 'source_condition'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Main Access Hatch:', 'data' => $equipment->equipmentConstruction->main_access_hatch ?? '',
                                                                'class_other' => 'equipment_section','id' => 'main_access_hatch'])
    @include('shineCompliance.forms.form_text',['title' =>  'Construction Material:', 'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->construction_material ?? 0),
                                                                'class_other' => 'equipment_section','id' => 'construction_material'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Anti-stratification Pump Fitted:', 'data' => $equipment->equipmentConstruction->anti_stratification ?? '',
                                                                'class_other' => 'equipment_section','id' => 'anti_stratification'])
    @include('shineCompliance.forms.form_text',['title' => 'Direct/Indirect Fired:', 'data' => $equipment->equipmentConstruction->directFired->description ?? '',
                                                                'class_other' => 'equipment_section','id' => 'direct_fired'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Flexible Hose:', 'data' => $equipment->equipmentConstruction->flexible_hose ?? '',
                                                                'class_other' => 'equipment_section','id' => 'flexible_hose'])
    @include('shineCompliance.forms.form_text',['title' => 'Horizontal/Vertical:', 'data' =>  $equipment->equipmentConstruction->horizontalVertical->description ?? '',
                                                                'class_other' => 'equipment_section','id' => 'horizontal_vertical'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Includes Water Softener:', 'data' => $equipment->equipmentConstruction->water_softener ?? '',
                                                                'class_other' => 'equipment_section','id' => 'water_softener'])
    @include('shineCompliance.forms.form_text',['title' =>  'Insulation Type:', 'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->insulation_type ?? 0),
                                                                'class_other' => 'equipment_section','id' => 'insulation_type'])
    @if (!in_array(221, $insulation_type) and !in_array(256, $insulation_type) and !in_array(257, $insulation_type))
        @include('shineCompliance.forms.form_text_small',['title' => 'Insulation Thickness:', 'data' => $equipment->equipmentConstruction->insulation_thickness ?? ''
                                                                ,'measurement' => 'mm','class_other' => 'equipment_section','id' => 'insulation_thickness'])
        @include('shineCompliance.forms.form_text',['title' => 'Insulation Condition:', 'data' => $equipment->equipmentConstruction->insulationCondition->description ?? '',
                                                                    'class_other' => 'equipment_section','id' => 'insulation_condition'])
    @endif
    @include('shineCompliance.forms.form_text',['title' =>  'Pipe Insulation:', 'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->pipe_insulation ?? 0),
                                                                'class_other' => 'equipment_section','id' => 'pipe_insulation'])
    @if (!in_array(222, $pipe_insulation) and !in_array(260, $pipe_insulation) and !in_array(261, $pipe_insulation))
    @include('shineCompliance.forms.form_text',['title' => 'Pipe Insulation Condition:', 'data' => $equipment->equipmentConstruction->pipeInsulationCondition->description ?? '',
                                                                'class_other' => 'equipment_section','id' => 'pipe_insulation_condition'])
    @endif
    @include('shineCompliance.forms.form_yes_no',['title' => 'Sentinel:', 'data' => $equipment->equipmentConstruction->sentinel ?? '',
                                                                'class_other' => 'equipment_section','id' => 'sentinel'])

    @if(isset($equipment->equipmentConstruction->sentinel) and ($equipment->equipmentConstruction->sentinel == 1))
        @include('shineCompliance.forms.form_text',['title' => 'Nearest/Furthest:', 'data' => $equipment->equipmentConstruction->nearestFurthest->description ?? '',
                                                                    ])
    @endif

    @include('shineCompliance.forms.form_yes_no',['title' => 'System Recirculated:', 'data' => $equipment->equipmentConstruction->system_recirculated ?? '',
                                                                'class_other' => 'equipment_section','id' => 'system_recirculated'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Screened Lid Vent:', 'data' => $equipment->equipmentConstruction->screened_lid_vent ?? '',
                                                                'class_other' => 'equipment_section','id' => 'screened_lid_vent'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Air Vent:', 'data' => $equipment->equipmentConstruction->air_vent ?? '',
                                                                'class_other' => 'equipment_section','id' => 'air_vent'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Overflow Warning:', 'data' => $equipment->equipmentConstruction->warning_pipe ?? '',
                                                                'class_other' => 'equipment_section','id' => 'warning_pipe'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Overflow Pipe:', 'data' => $equipment->equipmentConstruction->overflow_pipe ?? '',
                                                                'class_other' => 'equipment_section','id' => 'overflow_pipe'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Rodent Protection:', 'data' => $equipment->equipmentConstruction->rodent_protection ?? '',
                                                                 'class_other' => 'equipment_section','id' => 'rodent_protection'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'TMV Fitted:', 'data' => $equipment->equipmentConstruction->tmv_fitted ?? '',
                                                                'class_other' => 'equipment_section','id' => 'tmv_fitted'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Can it be Isolated:', 'data' => $equipment->equipmentConstruction->can_isolated ?? '',
                                                                'class_other' => 'equipment_section','id' => 'can_isolated'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Backflow Protection:', 'data' => $equipment->equipmentConstruction->backflow_protection ?? '',
                                                                 'class_other' => 'equipment_section','id' => 'backflow_protection'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Separate Ball Valve Hatch:', 'data' => $equipment->equipmentConstruction->ball_valve_hatch ?? '',
                                                                'class_other' => 'equipment_section','id' => 'ball_valve_hatch'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Stop Tap Fitted?', 'data' => $equipment->equipmentConstruction->stop_tap_fitted ?? '',
                                                    'name' => 'stop_tap_fitted' , 'id' => 'stop_tap_fitted', 'class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Drain Valve:', 'data' => $equipment->equipmentConstruction->drain_valve ?? '',
                                                                'class_other' => 'equipment_section','id' => 'drain_valve'])
    {{-- TODO Update Relation --}}
    <div class="row equipment_section" id="drain_size-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Drain Size:</label>
        <div class="col-md-1 form-input-text" >
            <span id="drain_size">{!! isset($equipment->equipmentConstruction->drain_size) ? $equipment->equipmentConstruction->drain_size : '' !!}</span>
            mm
        </div>

        <label class="col-md-1 col-form-label text-md-left font-weight-bold fs-8pt" >Location:</label>
        <div class="col-md-2 form-input-text" >
            <span id="drain_location">{!! isset($equipment->equipmentConstruction->drain_location) ? ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->drain_location ?? 0) : '' !!}</span>
        </div>
    </div>
    <div class="row equipment_section" id="cold_feed_size-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Cold Feed Size:</label>
        <div class="col-md-1 form-input-text" >
            <span id="cold_feed_size">{!! isset($equipment->equipmentConstruction->cold_feed_size) ? $equipment->equipmentConstruction->cold_feed_size : '' !!}</span>
            mm
        </div>

        <label class="col-md-1 col-form-label text-md-left font-weight-bold fs-8pt" >Location:</label>
        <div class="col-md-2 form-input-text" >
            <span id="cold_feed_location">{!! isset($equipment->equipmentConstruction->cold_feed_location) ? ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->cold_feed_location ?? 0) : '' !!}</span>
        </div>
    </div>
    <div class="row equipment_section" id="outlet_size-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Outlet Size:</label>
        <div class="col-md-1 form-input-text" >
            <span id="outlet_size">{!! isset($equipment->equipmentConstruction->outlet_size) ? $equipment->equipmentConstruction->outlet_size : '' !!}</span>
            mm
        </div>

        <label class="col-md-1 col-form-label text-md-left font-weight-bold fs-8pt" >Location:</label>
        <div class="col-md-2 form-input-text" >
            <span id="outlet_location">{!! isset($equipment->equipmentConstruction->outlet_location) ? ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->outlet_location ?? 0) : '' !!}</span>
        </div>
    </div>

    @include('shineCompliance.forms.form_text',['title' =>  'Labelling:', 'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->labelling ?? 0),
                                                                'class_other' => 'equipment_section','id' => 'labelling'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Return Temperature:', 'data' => $equipment->equipmentConstruction->construction_return_temp ?? '',
                                                                'class_other' => 'equipment_section','id' => 'construction_return_temp'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Flow Temperature Gauge:', 'data' => $equipment->equipmentConstruction->flow_temp_gauge ?? '',
                                                                'class_other' => 'equipment_section','id' => 'flow_temp_gauge'])
    @include('shineCompliance.forms.form_yes_no',['title' => 'Return Temperature Gauge:', 'data' => $equipment->equipmentConstruction->return_temp_gauge ?? '',
                                                                'class_other' => 'equipment_section','id' => 'return_temp_gauge'])
</div>
