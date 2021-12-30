<div class="offset-top40">
    @if(isset($equipment->equipmentConstruction->flow_temp_gauge) and ($equipment->equipmentConstruction->flow_temp_gauge == 1))
    @include('shineCompliance.forms.form_text_small',['title' => 'Flow Temperature Gauge:', 'data' => $equipment->tempAndPh->flow_temp_gauge_value ?? '' ,'measurement' => '°C'])
    @endif
    @if(isset($equipment->equipmentConstruction->return_temp_gauge) and ($equipment->equipmentConstruction->return_temp_gauge == 1))
    @include('shineCompliance.forms.form_text_small',['title' => 'Return Temperature Gauge:', 'data' => $equipment->tempAndPh->return_temp_gauge_value ?? '' ,'measurement' => '°C'])
    @endif
    @if(isset($equipment->equipmentConstruction->tmv_fitted) and ($equipment->equipmentConstruction->tmv_fitted == 1))
    @include('shineCompliance.forms.form_text_small',['title' => 'Mixed Temperature:', 'data' => $equipment->tempAndPh->mixed_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section'])
    @endif
    @if((isset($equipment->equipmentConstruction->construction_return_temp) and
    ($equipment->equipmentConstruction->construction_return_temp == 0))
    and (isset($equipment->equipmentType->template_id) and in_array($equipment->equipmentType->template_id, [10,11,12,13]))
    )
    @else
        @include('shineCompliance.forms.form_text_small',['title' => 'Return Temperature:', 'data' => $equipment->tempAndPh->return_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section'])
    @endif

    @include('shineCompliance.forms.form_text_small',['title' => 'Flow Temperature:', 'data' => $equipment->tempAndPh->flow_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section','id' => 'flow_temp'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Inlet Temperature:', 'data' => $equipment->tempAndPh->inlet_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section','id' => 'inlet_temp'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Stored Temperature:', 'data' => $equipment->tempAndPh->stored_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section','id' => 'stored_temp'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Top Temperature:', 'data' => $equipment->tempAndPh->top_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section','id' => 'top_temp'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Bottom Temperature:', 'data' => $equipment->tempAndPh->bottom_temp ?? '' ,'measurement' => '°C','class_other' => 'equipment_section','id' => 'bottom_temp'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Ambient Area Temperature:', 'id' => 'ambient_area_temp', 'data' => $equipment->tempAndPh->ambient_area_temp ?? '','measurement' => '°C','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Incoming Main Pipework Surface Temperature:', 'id' => 'incoming_main_pipe_work_temp', 'data' => $equipment->tempAndPh->incoming_main_pipe_work_temp ?? '','measurement' => '°C','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Hot Flow Temperature:', 'id' => 'hot_flow_temp', 'data' => $equipment->tempAndPh->hot_flow_temp ?? '','measurement' => '°C','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Cold Flow Temperature:', 'id' => 'cold_flow_temp', 'data' => $equipment->tempAndPh->cold_flow_temp ?? '','measurement' => '°C','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Pre-TMV Cold Flow Temperature:', 'id' => 'pre_tmv_cold_flow_temp', 'data' => $equipment->tempAndPh->pre_tmv_cold_flow_temp ?? '','measurement' => '°C','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Pre-TMV Hot Flow Temperature:', 'id' => 'pre_tmv_hot_flow_temp', 'data' => $equipment->tempAndPh->pre_tmv_hot_flow_temp ?? '','measurement' => '°C','class_other' => 'equipment_section'])
    @include('shineCompliance.forms.form_text_small',['title' => 'Post-TMV Temperature:', 'id' => 'post_tmv_temp', 'data' => $equipment->tempAndPh->post_tmv_temp ?? '','measurement' => '°C','class_other' => 'equipment_section'])

    <div class="row equipment_section" id="ph-form">
        <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >pH:</label>
        <div class="col-md-6 form-input-text" style="display: flex;">
            <span>{{ $equipment->tempAndPh->ph ?? '' }}&nbsp;</span>
            pH
            &nbsp;
            <div style="background-color: {{ ComplianceHelpers::getPhColor($equipment->tempAndPh->ph ?? null) }};width: 20px;height: 20px" class="ml-2"></div>
        </div>
    </div>

</div>
