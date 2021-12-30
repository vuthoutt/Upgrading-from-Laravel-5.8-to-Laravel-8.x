<div style="display:inline-block;width:99%;margin-bottom:5px;margin-left: 20px">
    <div style="display:inline-block;width:30%;">
        <img class="image-item" src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_PHOTO, $is_pdf) }}" />
        <p style='margin-top:5px'>Equipment</p>
    </div>
    <div style="display:inline-block;width:30%;">
        <img  class="image-item"  src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_LOCATION_PHOTO, $is_pdf) }}" />
        <p style='margin-top:5px'>Specific Location</p>
    </div>
    @if(\ComplianceHelpers::checkFile($equipment->id, EQUIPMENT_ADDITION_PHOTO))
        <div style="display:inline-block;width:30%;">
            <img class="image-item"  src="{{ ComplianceHelpers::getSystemFile($equipment->id, EQUIPMENT_ADDITION_PHOTO, $is_pdf) }}" />
            <p style='margin-top:5px'>Additional</p>
        </div>
    @endif
</div>
<div style="display:inline-block;width:56%;margin-top:5px;float: left;">
    <h3 class="mt30">Details</h3>
    <table cellspacing="0" class="unset-border" style="margin-left: 5px" width="56%">
        <tr>
            <td width="25%" style="vertical-align: top;">
                <p >
                    Shine Equipment Reference:
                </p>
            </td>
            <td width="30%">
                {{ $equipment->reference ?? '' }}
            </td>
        </tr>
        <tr style="border-top: unset!important;border-bottom: unset!important;">
            <td>
                <p >Equipment Name:</p>
            </td>
            <td>
                {{ $equipment->name ?? '' }}
            </td>
        </tr>
        <tr>
            <td >
                <p >
                    Equipment Type:
                </p>
            </td>
            <td >
                {{ $equipment->equipmentType->description ?? '' }}
            </td>
        </tr>
        <tr>
            <td >
                <p >
                    Area/floor:
                </p>
            </td>
            <td >
                {{ $equipment->area->area_reference ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td >
                <p >
                    Room/location:
                </p>
            </td>
            <td >
                {{ $equipment->location->location_reference ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td >
                <p >
                    Status:
                </p>
            </td>
            <td >
                {{ (isset($equipment->decommissioned) && ($equipment->decommissioned == 0)) ? 'Live' : 'Decommissioned' }}
            </td>
        </tr>
        <tr>
            <td >
                <p >
                    Operational Use:
                </p>
            </td>
            <td >
                {{ $equipment->operationalUse->description ?? '' }}
            </td>
        </tr>
        <tr>
            <td ><p >Accessibility:</p></td>
            <td >
                {{ $equipment->state_text ?? '' }}
            </td>
        </tr>
        @if(isset($equipment->state) and ($equipment->state == 0))
            <tr>
                <td style="vertical-align: top;"><p >Reason for Inaccessibility:</p></td>
                <td >
                    {{ $equipment->inaccessReason->description ?? '' }}
                </td>
            </tr>
        @endif
        {{--    Show Hot parent, Cold parent when equipment is mixer outlet types    --}}
        @if(!in_array($equipment->type, \App\Models\ShineCompliance\EquipmentType::where('template_id', 5)->get()->pluck('id')->toArray()))
            <tr>
                <td ><p >Parent:</p></td>
                <td >
                    {{ $equipment->parent->reference ?? '' }}
                </td>
            </tr>
        @else
            <tr>
                <td ><p >Hot Parent:</p></td>
                <td >
                    {{ $equipment->hotParent->reference ?? '' }}
                </td>
            </tr>
            <tr>
                <td ><p >Cold Parent:</p></td>
                <td >
                    {{ $equipment->coldParent->reference ?? '' }}
                </td>
            </tr>
        @endif
        <tr>
            <td ><p > Linked:</p></td>
            <td >
                {{ $equipment->system->reference ?? '' }}
            </td>
        </tr>
        <tr>
            <td ><p > Specific Location:</p></td>
            <td >
                {{ $equipment->specificLocationView->specific_location ?? '' }}
            </td>
        </tr>
        <tr>
            <td ><p > Frequency of Use:</p></td>
            <td >
                {{ $equipment->frequencyUse->description ?? '' }}
            </td>
        </tr>
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'extent'))
            <tr>
                <td ><p >Extent:</p></td>
                <td >
                    @if($equipment->extent)
                        {{ $equipment->extent ?? '' }} number
                    @endif
                </td>
            </tr>
        @endif
    </table>

    <h3 class="mt30">Supply</h3>
    <table cellspacing="0" class="unset-border" width="56%" style="margin-left: 5px">
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'stored_temp'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Storage Temperature:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->tempAndPh->stored_temp ?? '' }} °C
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'flow_temp'))
            <tr style="border-top: unset!important;border-bottom: unset!important;">
                <td width="25%" style="vertical-align: top;">
                    <p >Flow Temperature:</p>
                </td>
                <td width="30%">
                    {{ $equipment->tempAndPh->flow_temp ?? '' }} °C
                </td>
            </tr>
        @endif
        @if((isset($equipment->equipmentConstruction->construction_return_temp) and
        ($equipment->equipmentConstruction->construction_return_temp == 0))
        and (isset($equipment->equipmentType->template_id) and in_array($equipment->equipmentType->template_id, [10,11,12,13]))
        )
        @else
            @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'return_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Return Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->return_temp ?? '' }} °C
                    </td>
                </tr>
            @endif
        @endif
        @if(isset($equipment->equipmentConstruction->tmv_fitted) and ($equipment->equipmentConstruction->tmv_fitted == 1))
            @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'mixed_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Mixed Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->mixed_temp ?? '' }} °C
                    </td>
                </tr>
            @endif
        @endif
        @if(isset($equipment->equipmentConstruction->return_temp_gauge) and ($equipment->equipmentConstruction->return_temp_gauge == 1))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Flow Temperature Gauge:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->tempAndPh->flow_temp_gauge_value ?? '' }} °C
                </td>
            </tr>
        @endif
        @if(isset($equipment->equipmentConstruction->flow_temp_gauge) and ($equipment->equipmentConstruction->flow_temp_gauge == 1))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Flow Temperature Gauge:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->tempAndPh->flow_temp_gauge_value ?? '' }} °C
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'top_temp'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Top Temperature:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->tempAndPh->top_temp ?? '' }} °C
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'bottom_temp'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Bottom Temperature:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->tempAndPh->bottom_temp ?? '' }} °C
                </td>
            </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'ambient_area_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Ambient Area Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->ambient_area_temp ?? '' }} °C
                    </td>
                </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'hot_flow_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Hot Flow Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->hot_flow_temp ?? '' }} °C
                    </td>
                </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'cold_flow_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Cold Flow Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->cold_flow_temp ?? '' }} °C
                    </td>
                </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'pre_tmv_cold_flow_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Pre-TMV Cold Flow Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->pre_tmv_cold_flow_temp ?? '' }} °C
                    </td>
                </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'pre_tmv_hot_flow_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Pre-TMV Hot Flow Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->pre_tmv_hot_flow_temp ?? '' }} °C
                    </td>
                </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'post_tmv_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Post-TMV Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->post_tmv_temp ?? '' }} °C
                    </td>
                </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'incoming_main_pipe_work_temp'))
                <tr>
                    <td width="25%" style="vertical-align: top;">
                        <p >
                            Incoming Main Pipework Surface Temperature:
                        </p>
                    </td>
                    <td width="30%">
                        {{ $equipment->tempAndPh->incoming_main_pipe_work_temp ?? '' }} °C
                    </td>
                </tr>
            @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'ph'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        PH:
                    </p>
                </td>
                <td width="30%">
                    <div>
                        <span>{{ $equipment->tempAndPh->ph ?? '' }} ph</span>
                        <span style="background-color: {{ ComplianceHelpers::getPhColor($equipment->tempAndPh->ph ?? null) }};width: 30px;height: 20px" class="ml-2">&ensp;&ensp;</span>
                    </div>
                </td>
            </tr>
        @endif
    </table>
    @if($equipment->equipmentType->template_id != 8)
    <h3 class="mt30">Cleaning</h3>
    <table cellspacing="0" class="unset-border"  width="56%">
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'operational_exposure'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Operational Exposure:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->cleaning->operationalExposure->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'envidence_stagnation'))
            <tr style="border-top: unset!important;border-bottom: unset!important;">
                <td width="25%" style="vertical-align: top;">
                    <p >Evidence of Stagnation:</p>
                </td>
                <td width="30%">
                    {{ $equipment->cleaning->envidenceStagnation->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'degree_fouling'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Degree of Fouling:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->cleaning->degreeFouling->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'degree_biological'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Degree of Biological Slime:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->reference ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'extent_corrosion'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Extent of Corrosion:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->cleaning->extentCorrosion->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'cleanliness'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Cleanliness:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->cleaning->cleanlinessRelation->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'ease_cleaning'))
            <tr>
                <td width="25%" style="vertical-align: top;">
                    <p >
                        Ease of Cleaning:
                    </p>
                </td>
                <td width="30%">
                    {{ $equipment->cleaning->easeCleaning->description ?? '' }}
                </td>
            </tr>
        @endif
    </table>
    @endif
    @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'comments'))
        <h3 class="mt30">Comments</h3>
        <div class="mt30">
            <p>
                {{ $equipment->cleaning->comments ?? '' }}
            </p>
        </div>
    @endif
</div>
<div style="display:inline-block;width:40%;margin-top:5px;">
    @if($equipment->equipmentType->template_id != 8)
    <h3 class="mt30">Model</h3>
    <table cellspacing="0" class="unset-border" width="30%" style="margin-left: 5px">
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'manufacturer'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Manufacturer:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentModel->manufacturer ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'model'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Model:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentModel->model ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'dimensions'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Dimensions:</p></td>
                <td width="30%">
                    Length {{ $equipment->equipmentModel->dimensions_length ?? '' }} metres
                </td>
            </tr>
            <tr>
                <td></td>
                <td width="30%">
                    Width {{ $equipment->equipmentModel->dimensions_width ?? '' }} metres
                </td>
            </tr>
            <tr>
                <td></td>
                <td width="30%">
                    Depth {{ $equipment->equipmentModel->dimensions_depth ?? '' }} metres
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'capacity'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Capacity:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentModel->capacity ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'stored_water'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Volume of Stored Water:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentModel->stored_water ?? '' }}
                </td>
            </tr>
        @endif
    </table>
    @endif
    <h3 class="mt30">
        Construction
    </h3>
    <table cellspacing="0" class="unset-border" width="30%">
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'access'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Access:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->access ?? '' }}
                </td>
            </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'water_meter_fitted'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Water Meter Fitted:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->water_meter_fitted ?? '') }}
                </td>
            </tr>
        @endif
        @if(isset($equipment->equipmentConstruction->water_meter_fitted) and ($equipment->equipmentConstruction->water_meter_fitted == 1))
            @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'water_meter_reading'))
                <tr>
                    <td width="25%" style="vertical-align: top;"><p >Water Meter Reading:</p></td>
                    <td width="30%">
                        {{ $equipment->equipmentConstruction->water_meter_reading ?? '' }}
                    </td>
                </tr>
            @endif
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'material_of_pipework'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p>Material of Pipework:</p></td>
                <td width="30%">
                    {{ ComplianceHelpers::getEquipmentMultiselectWitOther($equipment->equipmentConstruction->material_of_pipework ?? '', $equipment->equipmentConstruction, 'material_of_pipework_other') }}
                </td>
            </tr>
        @endif

        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'size_of_pipework'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Size of Pipework (mm):</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->size_of_pipework ?? '' }} mm
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'condition_of_pipework'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Condition of Pipework:</p></td>
                <td width="30%">
                    {{  $equipment->equipmentConstruction->conditionPipework->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'aerosol_risk'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Aerosol Risk:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->aerosolRisk->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'source'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Source:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->sourceRelation->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'source_accessibility'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Source Accessibility:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->sourceAccessibility->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'source_condition'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Source Condition:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->sourceCondition->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'main_access_hatch'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Main Access Hatch:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->main_access_hatch ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'construction_material'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Construction Material:</p></td>
                <td width="30%">
                    {{ ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->construction_material ?? 0) }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'anti_stratification'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Anti-stratification Pump Fitted:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->anti_stratification ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'direct_fired'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Direct/Indirect Fired:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->directFired->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'flexible_hose'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Flexible Hose:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->flexible_hose ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'horizontal_vertical'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Horizontal/Vertical:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->horizontalVertical->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'water_softener'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Includes Water Softener:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->water_softener ?? '') }}
                </td>
            </tr>
        @endif
        @php
                // new update for insulation_type and pipe_insulation
                $insulation_type = $equipment->equipmentConstruction->insulation_type ?? '';
                $insulation_type = explode(",",$insulation_type);

                // new update for insulation_type
                $pipe_insulation = $equipment->equipmentConstruction->pipe_insulation ?? '';
                $pipe_insulation = explode(",",$pipe_insulation);
        @endphp
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'insulation_type'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Insulation Type:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->insulation_type ?? 0) }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'pipe_insulation'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Pipe Insulation:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->pipe_insulation ?? 0) }}
                </td>
            </tr>
        @endif
        @if (!in_array(221, $insulation_type) and !in_array(256, $insulation_type) and !in_array(257, $insulation_type))
            @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'insulation_thickness'))
                <tr>
                    <td width="25%" style="vertical-align: top;"><p > Insulation Thickness:</p></td>
                    <td width="30%">
                        {{ $equipment->equipmentConstruction->insulation_thickness ?? '' }} mm
                    </td>
                </tr>
            @endif
            @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'insulation_condition'))
                <tr>
                    <td width="25%" style="vertical-align: top;"><p > Insulation Condition:</p></td>
                    <td width="30%">
                        {{  $equipment->equipmentConstruction->insulationCondition->description ?? '' }}
                    </td>
                </tr>
            @endif
        @endif
        @if (!in_array(222, $pipe_insulation) and !in_array(260, $pipe_insulation) and !in_array(261, $pipe_insulation))
            @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'pipe_insulation_condition'))
                <tr>
                    <td width="25%" style="vertical-align: top;"><p > Pipe Insulation Condition:</p></td>
                    <td width="30%" style="vertical-align: top;">
                        {{ $equipment->equipmentConstruction->pipeInsulationCondition->description ?? '' }}
                    </td>
                </tr>
            @endif
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'stop_tap_fitted'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p >Stop Tap Fitted:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->stop_tap_fitted ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'sentinel'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Sentinel:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->sentinel ?? '') }}
                </td>
            </tr>
        @endif
        @if(isset($equipment->equipmentConstruction->sentinel) and $equipment->equipmentConstruction->sentinel == 1)
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Nearest/Furthest:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->nearestFurthest->description ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'system_recirculated'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > System Recirculated:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->system_recirculated ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'screened_lid_vent'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Screened Lid Vent:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->screened_lid_vent ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'air_vent'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Air Vent:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->air_vent ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'overflow_pipe'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Overflow Pipe:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->overflow_pipe ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'rodent_protection'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Rodent Protection:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->rodent_protection ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'tmv_fitted'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > TMV Fitted:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->tmv_fitted ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'can_isolated'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Can it be Isolated:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->can_isolated ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'backflow_protection'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Backflow Protection:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->backflow_protection ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'ball_valve_hatch'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Separate Ball Valve Hatch:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->ball_valve_hatch ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'drain_valve'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Drain Valve:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->drain_valve ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'drain_size'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Drain Size:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->drain_valve ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'drain_location'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Drain Location:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->drain_location ?? 0) }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'cold_feed_size'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Cold Feed Size:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->cold_feed_size ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'cold_feed_location'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Cold Feed Location:</p></td>
                <td width="30%">
                    {{ ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->cold_feed_location ?? 0) }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'outlet_size'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Outlet Size:</p></td>
                <td width="30%">
                    {{ $equipment->equipmentConstruction->outlet_size ?? '' }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'outlet_location'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Outlet Location:</p></td>
                <td width="30%">
                    {{ ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->outlet_location ?? 0) }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'labelling'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Labelling:</p></td>
                <td width="30%">
                    {{ ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->labelling ?? 0) }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'construction_return_temp'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Return Temperature:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->construction_return_temp ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'flow_temp_gauge'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Flow Temperature Gauge:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->flow_temp_gauge ?? '') }}
                </td>
            </tr>
        @endif
        @if(\ComplianceHelpers::equipmentActiveField($equipment->equipmentType->template_id ?? 0, 'return_temp_gauge'))
            <tr>
                <td width="25%" style="vertical-align: top;"><p > Return Temperature Gauge:</p></td>
                <td width="30%">
                    {{ \ComplianceHelpers::getYesNoFromVarible( $equipment->equipmentConstruction->return_temp_gauge ?? '') }}
                </td>
            </tr>
        @endif
    </table>
</div>

<h3 class="mt30" style="width:100%;display:block;clear: both" > Equipment Nonconformities </h3>
<div class="tableItems tableGray" style="margin-top: 10px">
    <table BORDER=1 CELLPADDING=1 CELLSPACING=1 RULES=ALL FRAME=BOX style="width:98%;" class="system">
        <thead>
        <tr>
            <th width="23%">Nonconformity Type</th>
            <th width="10%">Reference</th>
            <th width="20%">Hazard Type</th>
            <th width="10%">Reference</th>
            <th width="20%">Recommendation</th>
            <th width="20%">Risk Type</th>
        </tr>
        </thead>
        <tbody>
            @if(count($equipment->nonconformities))
                @foreach($equipment->nonconformities as $nonconformity)
                    <tr>
                        <td>{{ $nonconformity->type ?? ''}}</td>
                        <td>{{ $nonconformity->reference ?? ''}}</td>
                        <td>{{ $nonconformity->hazardPDF->hazardType->description ?? ''}}</td>
                        <td>{{ $nonconformity->hazardPDF->reference ?? ''}}</td>
                        <td>{{ $nonconformity->hazardPDF->action_recommendations ?? ''}}</td>
                        <td>
                            <span style="color: {{\CommonHelpers::getTotalRiskHazardText($nonconformity->hazardPDF->total_risk ?? null)['color']}} !important;
                                background-color: {{\CommonHelpers::getTotalHazardTextPDF($nonconformity->hazardPDF->total_risk ?? null)}}";>
                                &nbsp;
                                @if(isset($nonconformity->hazardPDF->total_risk))
                                {{ sprintf("%02d",$nonconformity->hazardPDF->total_risk ?? null) }}
                                @endif
                            </span>&nbsp;&nbsp;
                            <span>{{ \CommonHelpers::getTotalHazardText($nonconformity->hazardPDF->total_risk ?? null)['risk'] }}</span>
                        </td>
                    </tr>
                @endforeach
            @else
                <td colspan="6">No Information.</td>
            @endif
        </tbody>

    </table>
</div>

