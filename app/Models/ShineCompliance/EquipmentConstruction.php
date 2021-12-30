<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentConstruction extends ModelBase
{
    protected $table = 'cp_equipment_construction';

    protected $fillable = [
        'access',
        'water_meter_fitted',
        'water_meter_reading',
        'material_of_pipework',
        'material_of_pipework_other',
        'size_of_pipework',
        'condition_of_pipework',
        'stop_tap_fitted',
        'equipment_id',
        'anti_stratification',
        'can_isolated',
        'backflow_protection',
        'direct_fired',
        'flexible_hose',
        'horizontal_vertical',
        'water_softener',
        'insulation_type',
        'rodent_protection',
        'sentinel',
        'nearest_furthest',
        'system_recirculated',
        'screened_lid_vent',
        'tmv_fitted',
        'warning_pipe',
        'overflow_pipe',
        'labelling',
        'aerosol_risk',
        'pipe_insulation',
        'pipe_insulation_condition',
        'construction_material',
        'insulation_thickness',
        'insulation_condition',
        'drain_valve',
        'source',
        'source_accessibility',
        'source_condition',
        'air_vent',
        'main_access_hatch',
        'ball_valve_hatch',
        'drain_size',
        'drain_location',
        'cold_feed_size',
        'cold_feed_location',
        'outlet_size',
        'outlet_location',
        'created_at',
        'updated_at',
        'flow_temp_gauge',
        'construction_return_temp',
        'return_temp_gauge'
    ];

    public function sourceRelation() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'source');
    }

    public function sourceAccessibility() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'source_accessibility');
    }

    public function sourceCondition() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'source_condition');
    }

    public function mainAccessHatch() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'main_access_hatch');
    }

    public function directFired() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'direct_fired');
    }

    public function horizontalVertical() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'horizontal_vertical');
    }

    public function insulationCondition() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'insulation_condition');
    }

    public function conditionPipework() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'insulation_condition');
    }

    public function pipeInsulationCondition() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'pipe_insulation_condition');
    }

    public function aerosolRisk() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'aerosol_risk');
    }

    public function nearestFurthest() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'nearest_furthest');
    }
}
