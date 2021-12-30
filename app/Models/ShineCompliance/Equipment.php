<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Equipment extends ModelBase
{
    protected $table = 'cp_equipments';

    protected $fillable = [
        'property_id',
        'record_id',
        'reference',
        'is_locked',
        'name',
        'assess_id',
        'area_id',
        'location_id',
        'type',
        'decommissioned',
        'operational_use',
        'state',
        'reason',
        'reason_other',
        'parent_id',
        'hot_parent_id',
        'cold_parent_id',
        'system_id',
        'frequency_use',
        'extent',
        'has_sample',
        'sample_reference',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function property() {
        return $this->hasOne('App\Models\ShineCompliance\Property','id', 'property_id');
    }

    public function assessment() {
        return $this->hasOne('App\Models\ShineCompliance\Assessment','id', 'assess_id');
    }

    public function system() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceSystem','id', 'system_id');
    }

    public function area() {
        return $this->hasOne('App\Models\ShineCompliance\Area','id', 'area_id');
    }

    public function location() {
        return $this->hasOne('App\Models\ShineCompliance\Location','id', 'location_id');
    }

    public function equipmentType() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentType','id', 'type');
    }

    public function parent() {
        return $this->hasOne('App\Models\ShineCompliance\Equipment','id', 'parent_id');
    }

    public function hotParent() {
        return $this->hasOne('App\Models\ShineCompliance\Equipment','id', 'hot_parent_id');
    }

    public function coldParent() {
        return $this->hasOne('App\Models\ShineCompliance\Equipment','id', 'cold_parent_id');
    }

    public function equipmentConstruction() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentConstruction','equipment_id', 'id');
    }

    public function cleaning() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentCleaning','equipment_id', 'id');
    }

    public function tempAndPh() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentTempAndPh','equipment_id', 'id');
    }

    public function equipmentModel() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentModel','equipment_id', 'id');
    }

    public function inaccessReason() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'reason');
    }

    public function operationalUse() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'operational_use');
    }

    public function frequencyUse() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'frequency_use');
    }

    public function specificLocationView() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentSpecificLocationView', 'equipment_id', 'id');
    }

    public function nonconformities() {
        return $this->hasMany('App\Models\ShineCompliance\Nonconformity', 'equipment_id', 'id')->where('is_deleted', 0);
    }

    public function specificLocationValue() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentSpecificLocationValue', 'equipment_id', 'id');
    }

    public function complianceDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceDocumentStorage','object_id', 'id')->where('type',EQUIPMENT_PHOTO);
    }

    public function equipmentPhotoshineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceDocumentStorage','object_id', 'id')->where('type',EQUIPMENT_LOCATION_PHOTO);
    }

    public function equipmentLocationPhotoshineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceDocumentStorage','object_id', 'id')->where('type',EQUIPMENT_PHOTO);
    }

    public function equipmentAdditionalPhotoshineDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceDocumentStorage','object_id', 'id')->where('type',EQUIPMENT_ADDITION_PHOTO);
    }

    public function tempLog() {
        return $this->hasMany(TempLog::class, 'equipment_id', 'id');
    }

    public function getStateTextAttribute() {
        $state = $this->attributes['state'];
        switch ($state) {
            case 0:
                $state_text = 'Inaccessible';
                break;
            case 1:
                $state_text = 'Accessible';
                break;

            default:
                $state_text = '';
                break;
        }

        return $state_text;
    }

    public function getTitlePresentationAttribute($value) {
        return implode(" - ", array_filter([
            $this->attributes['reference'] ?? '',
            $this->attributes['name'] ?? ''
        ]));
    }

    public function getActiveTextAttribute($key) {
        $state_text = '';
        switch ($key) {
            case 'flow_temp_gauge_value':
                if(isset($this->equipmentConstruction->flow_temp_gauge) and ($this->equipmentConstruction->flow_temp_gauge == 1)){
                    $state_text = 'Flow Temperature Gauge';
                }
                break;
            case 'return_temp_gauge_value':
                if(isset($this->equipmentConstruction->return_temp_gauge) and ($this->equipmentConstruction->return_temp_gauge == 1)){
                    $state_text = 'Return Temperature Gauge';
                }
                break;
            case 'return_temp':
                $state_text = 'Return Temperature';
                break;
            case 'flow_temp':
                $state_text = 'Flow Temperature';
                break;
            case 'inlet_temp':
                $state_text = 'Inlet Temperature';
                break;
            case 'stored_temp':
                $state_text = 'Stored Temperature';
                break;
            case 'top_temp':
                $state_text = 'Top Temperature';
                break;
            case 'bottom_temp':
                $state_text = 'Bottom Temperature';
                break;
            case 'ambient_area_temp':
                $state_text = 'Ambient Area Temperature';
                break;
            case 'incoming_main_pipe_work_temp':
                $state_text = 'Incoming Main Pipework Surface Temperature';
                break;
            case 'hot_flow_temp':
                $state_text = 'Hot Flow Temperature';
                break;
            case 'cold_flow_temp':
                $state_text = 'Cold Flow Temperature';
                break;
            case 'pre_tmv_cold_flow_temp':
                $state_text = 'Pre-TMV Cold Flow Temperature';
                break;
            case 'pre_tmv_hot_flow_temp':
                $state_text = 'Pre-TMV Hot Flow Temperature';
                break;
            case 'post_tmv_temp':
                $state_text = 'Post-TMV Temperature';
                break;
            case 'ph':
                $state_text = 'PH';
                break;

            default:
                $state_text = '';
                break;
        }

        return $state_text;
    }
}
