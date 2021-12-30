<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentCleaning extends ModelBase
{
    protected $table = 'cp_equipment_cleaning';

    protected $fillable = [
        'equipment_id',
        'operational_exposure',
        'envidence_stagnation',
        'degree_fouling',
        'degree_biological',
        'extent_corrosion',
        'cleanliness',
        'ease_cleaning',
        'comments',
        'created_at',
        'updated_at',

    ];


    public function operationalExposure() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'operational_exposure');
    }

    public function degreeFouling() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'degree_fouling');
    }

    public function degreeBiological() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'degree_biological');
    }

    public function extentCorrosion() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'extent_corrosion');
    }

    public function cleanlinessRelation() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'cleanliness');
    }

    public function easeCleaning() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'ease_cleaning');
    }

    public function envidenceStagnation() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentDropdownData', 'id', 'envidence_stagnation');
    }
}
