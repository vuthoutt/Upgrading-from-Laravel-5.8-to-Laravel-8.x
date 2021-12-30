<div class="offset-top40">
    @include('shineCompliance.forms.form_input',['title' => 'Access:', 'name' => 'access', 'data' => $equipment->equipmentConstruction->access ?? '','id' => 'access', 'class_other' => 'equipment_section',])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Water Meter Fitted?', 'data' => $equipment->equipmentConstruction->water_meter_fitted ?? '', 'name' => 'water_meter_fitted' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_input',['title' => 'Water Meter Reading:', 'name' => 'water_meter_reading', 'data' => $equipment->equipmentConstruction->water_meter_reading ?? '','id' => 'water_meter_reading', 'class_other' => 'equipment_section'])
    <div class="row register-form equipment_section" id="material_of_pipework-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Material of Pipework:</label>
        <div class="col-md-5">
            <div class="form-group ">
                @include('shineCompliance.forms.form_multi_select_contruction',[ 'name' => 'material_of_pipework', 'id' => 'material_of_pipework','data_multis' => $material_of_pipework,
                        'selected' => explode(",",$equipment->equipmentConstruction->material_of_pipework ?? ''), 'data_other' => $equipment->equipmentConstruction->material_of_pipework_other ])
            </div>
        </div>
    </div>
    @include('shineCompliance.forms.form_input_small',['title' => 'Size of Pipework (mm):', 'name' => 'size_of_pipework', 'data' => $equipment->equipmentConstruction->size_of_pipework ?? '','measurement' => 'mm','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Condition of Pipework:', 'data' => $insulation_conditions, 'name' => 'condition_of_pipework', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section',
                                                    'compare_value' => $equipment->equipmentConstruction->condition_of_pipework ?? ''])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Aerosol Risk:', 'data' => $aerosol_risks, 'name' => 'aerosol_risk', 'compare_value' => $equipment->equipmentConstruction->aerosol_risk ?? '',
                                                    'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Source:', 'data' => $sources, 'name' => 'source', 'compare_value' => $equipment->equipmentConstruction->source ?? '',
                                                    'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Source Accessibility:', 'data' => $source_accesses, 'compare_value' => $equipment->equipmentConstruction->source_accessibility ?? '',
                                                    'name' => 'source_accessibility', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Source Condition:', 'data' => $source_conditions, 'compare_value' => $equipment->equipmentConstruction->source_condition ?? '',
                                                    'name' => 'source_condition', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Main Access Hatch:', 'data' => $equipment->equipmentConstruction->main_access_hatch ?? '',
                                                    'name' => 'main_access_hatch' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_select2',['title' =>  'Construction Material:', 'data_multis' => $construction_materials,'data' => explode(",",$equipment->equipmentConstruction->construction_material ?? ''),
                                                    'name' => 'construction_material', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Anti-stratification Pump Fitted:', 'data' => $equipment->equipmentConstruction->anti_stratification ?? '',
                                                    'name' => 'anti_stratification' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Direct/Indirect Fired:', 'data' => $direct_fired, 'compare_value' => $equipment->equipmentConstruction->direct_fired ?? '',
                                                    'name' => 'direct_fired', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Flexible Hose:', 'data' => $equipment->equipmentConstruction->flexible_hose ?? '',
                                                    'name' => 'flexible_hose' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Horizontal/Vertical:', 'data' => $horizontal_vertical, 'compare_value' => $equipment->equipmentConstruction->horizontal_vertical ?? '',
                                                    'name' => 'horizontal_vertical','key'=> 'id', 'value'=>'description' ,'class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Includes Water Softener:', 'data' => $equipment->equipmentConstruction->water_softener ?? '',
                                                    'name' => 'water_softener' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_select2',['title' =>  'Insulation Type:', 'data_multis' => $insulation_type,'data' => explode(",",$equipment->equipmentConstruction->insulation_type ?? ''),
                                                    'name' => 'insulation_type', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Insulation Condition:', 'data' => $insulation_conditions, 'compare_value' => $equipment->equipmentConstruction->insulation_condition ?? '',
                                                    'name' => 'insulation_condition', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_input_small',['title' => 'Insulation Thickness:','data' => $equipment->equipmentConstruction->insulation_thickness ?? '',
                                                    'name' => 'insulation_thickness', 'measurement' => 'mm','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_select2',['title' =>  'Pipe Insulation:', 'data_multis' => $pipe_insulations,'data' => explode(",",$equipment->equipmentConstruction->pipe_insulation ?? ''),
                                                    'name' => 'pipe_insulation', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Pipe Insulation Condition:', 'data' => $pipe_insulation_conditions, 'compare_value' => $equipment->equipmentConstruction->pipe_insulation_condition ?? '',
                                                    'name' => 'pipe_insulation_condition', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Sentinel:', 'data' => $equipment->equipmentConstruction->sentinel ?? '',
                                                    'name' => 'sentinel' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_dropdown',['title' => 'Nearest/Furthest:', 'data' => $nearest_furthest, 'name' => 'nearest_furthest', 'compare_value' => $equipment->equipmentConstruction->nearest_furthest ?? '',
                                                'key'=> 'id', 'value'=>'description', 'class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_checkbox',['title' => 'System Recirculated:', 'data' => $equipment->equipmentConstruction->system_recirculated ?? '',
                                                    'name' => 'system_recirculated' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Screened Lid Vent:', 'data' => $equipment->equipmentConstruction->screened_lid_vent ?? '',
                                                    'name' => 'screened_lid_vent' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Air Vent:', 'data' => $equipment->equipmentConstruction->air_vent ?? '',
                                                    'name' => 'air_vent' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Overflow Warning:', 'data' => $equipment->equipmentConstruction->warning_pipe ?? '',
                                                    'name' => 'warning_pipe' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Overflow Pipe:', 'data' => $equipment->equipmentConstruction->overflow_pipe ?? '',
                                                    'name' => 'overflow_pipe' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Rodent Protection:', 'data' => $equipment->equipmentConstruction->rodent_protection ?? '',
                                                    'name' => 'rodent_protection' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'TMV Fitted:', 'data' => $equipment->equipmentConstruction->tmv_fitted ?? '',
                                                    'name' => 'tmv_fitted' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Can it be Isolated:', 'data' => $equipment->equipmentConstruction->can_isolated ?? '',
                                                    'name' => 'can_isolated' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Backflow Protection:', 'data' => $equipment->equipmentConstruction->backflow_protection ?? '',
                                                    'name' => 'backflow_protection' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Separate Ball Valve Hatch:', 'data' => $equipment->equipmentConstruction->ball_valve_hatch ?? '',
                                                    'name' => 'ball_valve_hatch' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Stop Tap Fitted?', 'data' => $equipment->equipmentConstruction->stop_tap_fitted ?? '',
                                                    'name' => 'stop_tap_fitted' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Drain Valve:', 'data' => $equipment->equipmentConstruction->drain_valve ?? '',
                                                    'name' => 'drain_valve' ,'class_other' => 'equipment_section','compare' => 1])

    <div class="row register-form parent-element equipment_section" id="drain_size-form">
        <label  class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">
            Drain Size:
            <span style="color: red" class="d-none" id="drain_size-star">*</span>
        </label>
        <div class="col-md-1">
            <div class="form-group">
                <input  type="text" class="form-control @error('drain_size')
                    is-invalid @enderror" name="drain_size" id="drain_size"
                        value="{{ isset($equipment->equipmentConstruction->drain_size) ? $equipment->equipmentConstruction->drain_size : old('outlet_size') }}">
                <span class="invalid-feedback" role="alert" style="width: 300px">
                <strong>
                @error('drain_size')
                    {{ $message }}
                    @enderror
                </strong>
            </span>
            </div>
        </div>
        <label class="col-md-1 mt-1 font-weight-bold fs-8pt">mm</label>
        <span class="mt-1 validate_tmv" id="drain_size_validate" style="color: red"></span>

        <label  class="col-md-1 col-form-label text-md-left font-weight-bold fs-8pt">
            Location:
            <span style="color: red" class="d-none" id="drain_location-star">*</span>
        </label>
        <div class="col-md-2">
            <div class="form-group">
                <select class="form-control @error('drain_location') is-invalid @enderror" name="drain_location" id="drain_location">
                    <option value="" data-option="0">---Please select an option---</option>
                    @if(isset($drain_locations) and !is_null($drain_locations))
                        @foreach($drain_locations as $val)
                            <option value="{{ $val->id }}" {{ old('drain_location') == $val->id ? "selected" : "" }} {{ isset($equipment->equipmentConstruction->drain_location) ? ($val->id == $equipment->equipmentConstruction->drain_location ? 'selected' : '' ) : ''}} >{{ $val->description }}</option>
                        @endforeach
                    @endif
                </select>
                <span class="invalid-feedback" role="alert" style="width: 300px">
                    <strong>
                    @error('drain_location')
                        {{ $message }}
                        @enderror
                    </strong>
                </span>
            </div>
        </div>
    </div>

    <div class="row register-form parent-element equipment_section" id="cold_feed_size-form">
        <label  class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">
            Cold Feed Size:
            <span style="color: red" class="d-none" id="cold_feed_size-star">*</span>
        </label>
        <div class="col-md-1">
            <div class="form-group">
                <input  type="text" class="form-control @error('cold_feed_size')
                    is-invalid @enderror" name="cold_feed_size" id="cold_feed_size"
                        value="{{ isset($equipment->equipmentConstruction->cold_feed_size) ? $equipment->equipmentConstruction->cold_feed_size : old('outlet_size') }}">
                <span class="invalid-feedback" role="alert" style="width: 300px">
                <strong>
                @error('cold_feed_size')
                    {{ $message }}
                    @enderror
                </strong>
            </span>
            </div>
        </div>
        <label class="col-md-1 mt-1 font-weight-bold fs-8pt">mm</label>
        <span class="mt-1 validate_tmv" id="cold_feed_size_validate" style="color: red"></span>

        <label  class="col-md-1 col-form-label text-md-left font-weight-bold fs-8pt">
            Location:
            <span style="color: red" class="d-none" id="cold_feed_location-star">*</span>
        </label>
        <div class="col-md-2">
            <div class="form-group">
                <select class="form-control @error('cold_feed_location') is-invalid @enderror" name="cold_feed_location" id="cold_feed_location">
                    <option value="" data-option="0">---Please select an option---</option>
                    @if(isset($cold_feed_location) and !is_null($cold_feed_location))
                        @foreach($cold_feed_location as $val)
                            <option value="{{ $val->id }}" {{ old('cold_feed_location') == $val->id ? "selected" : "" }} {{ isset($equipment->equipmentConstruction->cold_feed_location) ? ($val->id == $equipment->equipmentConstruction->cold_feed_location ? 'selected' : '' ) : ''}} >{{ $val->description }}</option>
                        @endforeach
                    @endif
                </select>
                <span class="invalid-feedback" role="alert" style="width: 300px">
                    <strong>
                    @error('cold_feed_location')
                        {{ $message }}
                        @enderror
                    </strong>
                </span>
            </div>
        </div>
    </div>

    <div class="row register-form parent-element equipment_section" id="outlet_size-form">
        <label  class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">
            Outlet Size:
            <span style="color: red" class="d-none" id="outlet_size-star">*</span>
        </label>
        <div class="col-md-1">
            <div class="form-group">
                <input  type="text" class="form-control @error('outlet_size')
                    is-invalid @enderror" name="outlet_size" id="outlet_size"
                        value="{{ isset($equipment->equipmentConstruction->outlet_size) ? $equipment->equipmentConstruction->outlet_size : old('outlet_size') }}">
                <span class="invalid-feedback" role="alert" style="width: 300px">
                <strong>
                @error('outlet_size')
                    {{ $message }}
                    @enderror
                </strong>
            </span>
            </div>
        </div>
        <label class="col-md-1 mt-1 font-weight-bold fs-8pt">mm</label>
        <span class="mt-1 validate_tmv" id="outlet_size_validate" style="color: red"></span>

        <label  class="col-md-1 col-form-label text-md-left font-weight-bold fs-8pt">
            Location:
            <span style="color: red" class="d-none" id="outlet_location-star">*</span>
        </label>
        <div class="col-md-2">
            <div class="form-group">
                <select class="form-control @error('outlet_location') is-invalid @enderror" name="outlet_location" id="outlet_location">
                    <option value="" data-option="0">---Please select an option---</option>
                    @if(isset($outlet_feed_location) and !is_null($outlet_feed_location))
                        @foreach($outlet_feed_location as $val)
                            <option value="{{ $val->id }}" {{ old('outlet_location') == $val->id ? "selected" : "" }} {{ isset($equipment->equipmentConstruction->outlet_location) ? ($val->id == $equipment->equipmentConstruction->outlet_location ? 'selected' : '' ) : ''}} >{{ $val->description }}</option>
                        @endforeach
                    @endif
                </select>
                <span class="invalid-feedback" role="alert" style="width: 300px">
                    <strong>
                    @error('outlet_location')
                        {{ $message }}
                        @enderror
                    </strong>
                </span>
            </div>
        </div>
    </div>

    @include('shineCompliance.forms.form_select2',['title' =>  'Labelling:', 'data_multis' => $labelling,'data' => explode(",",$equipment->equipmentConstruction->labelling ?? ''),
                                                    'name' => 'labelling', 'key'=> 'id', 'value'=>'description','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Return Temperature:', 'data' => $equipment->equipmentConstruction->construction_return_temp ?? '',
                                                    'name' => 'construction_return_temp' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Flow Temperature Gauge:', 'data' => $equipment->equipmentConstruction->flow_temp_gauge ?? '',
                                                    'name' => 'flow_temp_gauge' ,'class_other' => 'equipment_section','compare' => 1])
    @include('shineCompliance.forms.form_checkbox',['title' => 'Return Temperature Gauge:', 'data' => $equipment->equipmentConstruction->return_temp_gauge ?? '',
                                                    'name' => 'return_temp_gauge' ,'class_other' => 'equipment_section','compare' => 1])
</div>
