@extends('shineCompliance.layouts.app')

@section('content')
@include('shineCompliance.partials.nav',['breadCrumb' => 'equipment_detail', 'color' => 'red', 'data' => $equipment ?? '']))
<div class="container-cus prism-content pad-up">
    <div class="row">
        <h3 class="title-row">{{ $equipment->name ?? '' }}</h3>
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

        <div class="col-md-9" style="padding: 0" >
            <div class="card-data mar-up">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="card discard-border-radius">
                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>
                                <div class="card-body" style="padding: 15px;">
                                    @include('shineCompliance.forms.form_text',['title' => 'Equipment Name:', 'data' => $equipment->name ?? '', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Equipment Reference:', 'data' => $equipment->reference ?? '', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Equipment Type:', 'data' => $equipment->equipmentType->description ?? '', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Area/Floor:', 'data' => $equipment->area->area_reference ?? 'N/A', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Room/Location:', 'data' => $equipment->location->location_reference ?? 'N/A', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Status:', 'data' => ($equipment->decommissioned == 0) ? 'Live' : 'Decommissioned', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Opreational Use:', 'data' => $equipment->operationalUse->description ?? '', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Accessibility:', 'data' => $equipment->state_text ?? '', 'width_label'=> $width_label ])
                                    @if($equipment->state == 0)
                                        @include('shineCompliance.forms.form_text',['title' => 'Reason For Inaccessibility:', 'data' => $equipment->inaccessReason->description ?? '', 'width_label'=> $width_label ])
                                    @endif
                                    @include('shineCompliance.forms.form_text',['title' => 'Parent:', 'data' => $equipment->parent->reference ?? '' ,'link' => route('shineCompliance.register_equipment.detail', ['id' => $equipment->parent_id ?? 0]),
                                                                                'class_other' => 'equipment_section', 'id' => 'parent_id', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Hot Parent:', 'data' => $equipment->hotParent->reference ?? '' ,'link' => route('shineCompliance.register_equipment.detail', ['id' => $equipment->hot_parent_id ?? 0]),
                                                                                'class_other' => 'equipment_section', 'id' => 'hot_parent_id', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Cold Parent:', 'data' => $equipment->coldParent->reference ?? '' ,'link' => route('shineCompliance.register_equipment.detail', ['id' => $equipment->cold_parent_id ?? 0]),
                                                                                'class_other' => 'equipment_section', 'id' => 'cold_parent_id', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Linked:', 'data' => $equipment->system->reference ?? '' ,'link' => route('shineCompliance.systems.detail',['id' => $equipment->system_id ?? '']),
                                                                                'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Specific Location:', 'data' => $equipment->specificLocationView->specific_location ?? '', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Frequency of Use:', 'data' => $equipment->frequencyUse->description ?? '', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text_small',['title' => 'Extent:', 'data' => $equipment->extent ?? ''
                                                                                            ,'measurement' => 'Number','class_other' => 'equipment_section','id' => 'extent', 'width_label'=> $width_label])
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2" id="model_tab">
                            <div class="card discard-border-radius">
                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Model</strong></div>
                                <div class="card-body" style="padding: 15px;">
                                    @include('shineCompliance.forms.form_text',['title' => 'Manufacturer:', 'data' => $equipment->equipmentModel->manufacturer ?? '', 'id' => 'manufacturer','class_other' => 'equipment_section' , 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Model:', 'data' => $equipment->equipmentModel->model ?? '' , 'id' => 'model','class_other' => 'equipment_section', 'width_label'=> $width_label])
                                    <div class="row register-form equipment_section" id="dimensions-form">
                                        <label  class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt">
                                            Dimensions:
                                        </label>
                                        <div class="col-md-9 row">
                                            <label class="ml-3 mt-1 font-weight-bold fs-8pt">Length:</label>
                                            <span class="fs-8pt ml-2 mr-1 mt-1">{{ $equipment->equipmentModel->dimensions_length ?? '' }}</span>
                                            <label class="mt-1 fs-8pt">m</label>
                                            <label class="ml-3 mt-1 font-weight-bold fs-8pt">Width:</label>
                                            <span class="fs-8pt ml-2 mr-1 mt-1">{{ $equipment->equipmentModel->dimensions_width ?? '' }}</span>
                                            <label class="mt-1 fs-8pt">m</label>
                                            <label class="ml-3 mt-1 font-weight-bold fs-8pt">Depth:</label>
                                            <span class=" fs-8pt ml-2 mr-1 mt-1">{{ $equipment->equipmentModel->dimensions_depth ?? '' }}</span>
                                            <label class="mt-1 fs-8pt">m</label>
                                        </div>
                                    </div>
                                    @include('shineCompliance.forms.form_text_small',['title' => 'Capacity:', 'data' => $equipment->equipmentModel->capacity ?? '',
                                                                                        'measurement' => 'ltrs' , 'id' => 'capacity','class_other' => 'equipment_section', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text_small',['title' => 'Volume of Stored Water:', 'data' => $equipment->equipmentModel->stored_water ?? '',
                                                                                        'measurement' => 'ltrs', 'id' => 'stored_water','class_other' => 'equipment_section', 'width_label'=> $width_label])
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2" id="cleaning_tab">
                            <div class="card discard-border-radius">
                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Cleaning</strong></div>
                                <div class="card-body" style="padding: 15px;">
                                    @include('shineCompliance.forms.form_text',['title' => 'Operational Exposure:', 'data' => $equipment->cleaning->operationalExposure->description ?? ''
                                                                                    ,'class_other' => 'equipment_section','id' => 'operational_exposure', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Evidence of Stagnation:', 'data' => $equipment->cleaning->envidenceStagnation->description ?? ''
                                                                                ,'class_other' => 'equipment_section','id' => 'envidence_stagnation', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Degree of Fouling:', 'data' => $equipment->cleaning->degreeFouling->description ?? ''
                                                                                ,'class_other' => 'equipment_section','id' => 'degree_fouling', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Degree of Biological Slime:', 'data' => $equipment->cleaning->degreeBiological->description ?? ''
                                                                                ,'class_other' => 'equipment_section','id' => 'degree_biological', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Extent of Corrosion:', 'data' => $equipment->cleaning->extentCorrosion->description ?? ''
                                                                                ,'class_other' => 'equipment_section','id' => 'extent_corrosion', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Cleanliness:', 'data' => $equipment->cleaning->cleanlinessRelation->description ?? ''
                                                                                ,'class_other' => 'equipment_section','id' => 'cleanliness', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Ease of Cleaning:', 'data' => $equipment->cleaning->easeCleaning->description ?? ''
                                                                                ,'class_other' => 'equipment_section','id' => 'ease_cleaning', 'width_label'=> $width_label])
                                    @include('shineCompliance.forms.form_text',['title' => 'Comments:', 'data' => $equipment->cleaning->comments ?? ''
                                                                                ,'class_other' => 'equipment_section','id' => 'comments', 'width_label'=> $width_label])
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2" id="sampling_tab">
                            <div class="card discard-border-radius">
                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Sampling</strong></div>
                                <div class="card-body" style="padding: 15px;">
                                    @include('shineCompliance.forms.form_text',['title' => 'Has a Water Sample been collected?', 'data' => $equipment->has_sample ? 'Yes' : 'No', 'id' => 'has_sample','class_other' => 'equipment_section', 'width_label'=> $width_label ])
                                    @include('shineCompliance.forms.form_text',['title' => 'Sample Reference:', 'data' => $equipment->sample_reference ?? '' , 'id' => 'sample_reference','class_other' => 'equipment_section', 'width_label'=> $width_label])
                                </div>
                            </div>
                        </div>
{{--                        <div class="col-md-12 mb-2">--}}
{{--                            <div class="card discard-border-radius">--}}
{{--                                <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Details</strong></div>--}}
{{--                                <div class="card-body" style="padding: 15px;">--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card discard-border-radius">
                        <div class="card-header discard-border-radius" style="background-color: #d1d3d4"><strong>Construction</strong></div>
                        <div class="card-body" style="padding: 15px;">
                            @include('shineCompliance.forms.form_text',['title' => 'Access:', 'data' => $equipment->equipmentConstruction->access ?? '','id' => 'access', 'class_other' => 'equipment_section', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Water Meter Fitted?', 'data' => $equipment->equipmentConstruction->water_meter_fitted ?? '', 'id' => 'water_meter_fitted', 'class_other' => 'equipment_section', 'width_label'=> $width_label])
                            @if(isset($equipment->equipmentConstruction->water_meter_fitted) and ($equipment->equipmentConstruction->water_meter_fitted == 1))
                                @include('shineCompliance.forms.form_text',['title' => 'Water Meter Reading:', 'id' => 'water_meter_reading', 'data' => $equipment->equipmentConstruction->water_meter_reading ?? '', 'class_other' => 'equipment_section', 'width_label'=> $width_label])
                            @endif
                            @include('shineCompliance.forms.form_text',['title' =>  'Material of Pipework:', 'data' => ComplianceHelpers::getEquipmentMultiselectWitOther($equipment->equipmentConstruction->material_of_pipework ?? '', $equipment->equipmentConstruction, 'material_of_pipework_other'),
                                                                            'class_other' => 'equipment_section','id' => 'material_of_pipework', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text_small',['title' => 'Size of Pipework (mm):', 'name' => 'size_of_pipework', 'data' => $equipment->equipmentConstruction->size_of_pipework ?? '','measurement' => 'mm','class_other' => 'equipment_section', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' => 'Condition of Pipework:', 'class_other' => 'equipment_section', 'id' => 'condition_of_pipework',
                                                                            'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->condition_of_pipework ?? 0), 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' => 'Aerosol Risk:', 'data' => $equipment->equipmentConstruction->aerosolRisk->description ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'aerosol_risk', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' => 'Source:', 'data' => $equipment->equipmentConstruction->sourceRelation->description ?? '',
                                                                                         'class_other' => 'equipment_section','id' => 'source', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' => 'Source Accessibility:', 'data' => $equipment->equipmentConstruction->sourceAccessibility->description ?? '',
                                                                                         'class_other' => 'equipment_section','id' => 'source_accessibility', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' => 'Source Condition:', 'data' => $equipment->equipmentConstruction->sourceCondition->description ?? '',
                                                                                         'class_other' => 'equipment_section','id' => 'source_condition', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Main Access Hatch:', 'data' => $equipment->equipmentConstruction->main_access_hatch ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'main_access_hatch', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' =>  'Construction Material:', 'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->construction_material ?? 0),
                                                                                        'class_other' => 'equipment_section','id' => 'construction_material', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Anti-stratification Pump Fitted:', 'data' => $equipment->equipmentConstruction->anti_stratification ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'anti_stratification', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' => 'Direct/Indirect Fired:', 'data' => $equipment->equipmentConstruction->directFired->description ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'direct_fired', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Flexible Hose:', 'data' => $equipment->equipmentConstruction->flexible_hose ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'flexible_hose', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' => 'Horizontal/Vertical:', 'data' =>  $equipment->equipmentConstruction->horizontalVertical->description ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'horizontal_vertical', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Includes Water Softener:', 'data' => $equipment->equipmentConstruction->water_softener ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'water_softener', 'width_label'=> $width_label])

                            @php
                                    // new update for insulation_type and pipe_insulation
                                    $insulation_type = $equipment->equipmentConstruction->insulation_type ?? '';
                                    $insulation_type = explode(",",$insulation_type);

                                    // new update for insulation_type
                                    $pipe_insulation = $equipment->equipmentConstruction->pipe_insulation ?? '';
                                    $pipe_insulation = explode(",",$pipe_insulation);
                            @endphp
                            @include('shineCompliance.forms.form_text',['title' =>  'Insulation Type:', 'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->insulation_type ?? 0),
                                                                                        'class_other' => 'equipment_section','id' => 'insulation_type', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_text',['title' =>  'Pipe Insulation:', 'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->pipe_insulation ?? 0),
                                                                                        'class_other' => 'equipment_section','id' => 'pipe_insulation', 'width_label'=> $width_label])
                            @if (!in_array(221, $insulation_type) and !in_array(256, $insulation_type) and !in_array(257, $insulation_type))
                                @include('shineCompliance.forms.form_text_small',['title' => 'Insulation Thickness:', 'data' => $equipment->equipmentConstruction->insulation_thickness ?? ''
                                                                                        ,'measurement' => 'mm','class_other' => 'equipment_section','id' => 'insulation_thickness', 'width_label'=> $width_label])
                                @include('shineCompliance.forms.form_text',['title' => 'Insulation Condition:', 'data' => $equipment->equipmentConstruction->insulationCondition->description ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'insulation_condition', 'width_label'=> $width_label])
                            @endif
                            {{--     @include('shineCompliance.forms.form_yes_no',['title' => 'Pipe Insulation:', 'data' => $equipment->equipmentConstruction->pipe_insulation ?? '',
                                                                                            'class_other' => 'equipment_section','id' => 'pipe_insulation']) --}}

                            @if (!in_array(222, $pipe_insulation) and !in_array(260, $pipe_insulation) and !in_array(261, $pipe_insulation))

                                @include('shineCompliance.forms.form_text',['title' => 'Pipe Insulation Condition:', 'data' => $equipment->equipmentConstruction->pipeInsulationCondition->description ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'pipe_insulation_condition', 'width_label'=> $width_label])
                            @endif
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Sentinel:', 'data' => $equipment->equipmentConstruction->sentinel ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'sentinel', 'width_label'=> $width_label])

                            @if(isset($equipment->equipmentConstruction->sentinel) and ($equipment->equipmentConstruction->sentinel == 1))
                                @include('shineCompliance.forms.form_text',['title' => 'Nearest/Furthest:', 'data' => $equipment->equipmentConstruction->nearestFurthest->description ?? '', 'width_label'=> $width_label
                                                                                            ])
                            @endif

                            @include('shineCompliance.forms.form_yes_no',['title' => 'System Recirculated:', 'data' => $equipment->equipmentConstruction->system_recirculated ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'system_recirculated', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Screened Lid Vent:', 'data' => $equipment->equipmentConstruction->screened_lid_vent ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'screened_lid_vent', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Air Vent:', 'data' => $equipment->equipmentConstruction->air_vent ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'air_vent', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Overflow Warning:', 'data' => $equipment->equipmentConstruction->warning_pipe ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'warning_pipe', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Overflow Pipe:', 'data' => $equipment->equipmentConstruction->overflow_pipe ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'overflow_pipe', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Rodent Protection:', 'data' => $equipment->equipmentConstruction->rodent_protection ?? '',
                                                                                         'class_other' => 'equipment_section','id' => 'rodent_protection', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'TMV Fitted:', 'data' => $equipment->equipmentConstruction->tmv_fitted ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'tmv_fitted', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Can it be Isolated:', 'data' => $equipment->equipmentConstruction->can_isolated ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'can_isolated', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Backflow Protection:', 'data' => $equipment->equipmentConstruction->backflow_protection ?? '',
                                                                                         'class_other' => 'equipment_section','id' => 'backflow_protection', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Separate Ball Valve Hatch:', 'data' => $equipment->equipmentConstruction->ball_valve_hatch ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'ball_valve_hatch', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Stop Tap Fitted?', 'data' => $equipment->equipmentConstruction->stop_tap_fitted ?? '',
                                                                            'name' => 'stop_tap_fitted' , 'id' => 'stop_tap_fitted', 'class_other' => 'equipment_section', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Drain Valve:', 'data' => $equipment->equipmentConstruction->drain_valve ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'drain_valve', 'width_label'=> $width_label])
                            {{-- TODO Update Relation --}}
                            <div class="row equipment_section" id="drain_size-form">
                                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Drain Size:</label>
                                <div class="col-md-2 form-input-text" >
                                    <span id="drain_size">{!! isset($equipment->equipmentConstruction->drain_size) ? $equipment->equipmentConstruction->drain_size : '' !!}</span>
                                    mm
                                </div>

                                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Location:</label>
                                <div class="col-md-2 form-input-text" >
                                    <span id="drain_location">{!! isset($equipment->equipmentConstruction->drain_location) ? ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->drain_location ?? 0) : '' !!}</span>
                                </div>
                            </div>
                            <div class="row equipment_section" id="cold_feed_size-form">
                                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Cold Feed Size:</label>
                                <div class="col-md-2 form-input-text" >
                                    <span id="cold_feed_size">{!! isset($equipment->equipmentConstruction->cold_feed_size) ? $equipment->equipmentConstruction->cold_feed_size : '' !!}</span>
                                    mm
                                </div>

                                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Location:</label>
                                <div class="col-md-3 form-input-text" >
                                    <span id="cold_feed_location">{!! isset($equipment->equipmentConstruction->cold_feed_location) ? ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->cold_feed_location ?? 0) : '' !!}</span>
                                </div>
                            </div>
                            <div class="row equipment_section" id="outlet_size-form">
                                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Outlet Size:</label>
                                <div class="col-md-2 form-input-text" >
                                    <span id="outlet_size">{!! isset($equipment->equipmentConstruction->outlet_size) ? $equipment->equipmentConstruction->outlet_size : '' !!}</span>
                                    mm
                                </div>

                                <label class="col-md-3 col-form-label text-md-left font-weight-bold fs-8pt" >Location:</label>
                                <div class="col-md-3 form-input-text" >
                                    <span id="outlet_location">{!! isset($equipment->equipmentConstruction->outlet_location) ? ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->outlet_location ?? 0) : '' !!}</span>
                                </div>
                            </div>

                            @include('shineCompliance.forms.form_text',['title' =>  'Labelling:', 'data' => ComplianceHelpers::getEquipmentMultiselect($equipment->equipmentConstruction->labelling ?? 0),
                                                                                        'class_other' => 'equipment_section','id' => 'labelling', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Flow Temperature Gauge:', 'data' => $equipment->equipmentConstruction->flow_temp_gauge ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'flow_temp_gauge', 'width_label'=> $width_label])
                            @include('shineCompliance.forms.form_yes_no',['title' => 'Return Temperature Gauge:', 'data' => $equipment->equipmentConstruction->return_temp_gauge ?? '',
                                                                                        'class_other' => 'equipment_section','id' => 'return_temp_gauge', 'width_label'=> $width_label])
                        </div>
                    </div>
                </div>
            </div>
        @if (count($category))
            {{-- <div class="row" style="justify-content: flex-end; float: left;margin-top: 10px;padding-right: 10px;"> --}}
            <div class="row" style="justify-content: flex-end;">
                <div class="pagination-left mar-up">
                    <nav aria-label="...">
                        {{ $category->appends(request()->except(['_', 'id']))->links('vendor.pagination.customize-pagination',['param' => 'id']) }}
                    </nav>
                </div>
            </div>
        @endif
        </div>
    </div>
</div>
@endsection
@push('javascript')
    <script type="text/javascript">
        $(document).ready(function(){
            // $("#type").change(function(){
            var type = {{ $equipment->type ?? 0 }};

            $.ajax({
                type: "GET",
                url: "{{ route('shineCompliance.equipment.ajax_equipment_template') }}",
                data: {type: type},
                cache: false,
                success: function (response) {
                    if (response.status == 200) {
                        $('.equipment_section').hide();
                        actives = response.data;

                        actives.forEach(function(active) {
                            $('#' + active + '-form').show();
                        });
                        template_id = response.template_id;
                        // Miscellaneous Equipment template
                        // if (template_id == 1) {
                        //     $('#temp_tab').hide();
                        // } else {
                        //     $('#temp_tab').show();
                        // }

                        // Show Sampling tab if Outlet templates
                        if (template_id == 4 || template_id == 5 || template_id == 6) {
                            $('#sampling_tab').show();
                        } else {
                            $('#sampling_tab').hide();
                        }

                        if (template_id == 8) {
                            $('#model_tab').hide();
                            $('#cleaning_tab').hide();
                            $('#drain_valve-form').children('label').text('Drain Valve Fitted?');
                        } else {
                            $('#model_tab').show();
                            $('#cleaning_tab').show();
                            $('#drain_valve-form').children('label').text('Drain Valve:');
                        }

                    } else {
                        $('#overlay').fadeOut();
                    }
                }
            });
            // });
        });
    </script>
@endpush
