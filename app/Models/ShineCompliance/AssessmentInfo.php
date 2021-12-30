<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AssessmentInfo extends ModelBase
{
    protected $table = 'cp_assessment_info';

    protected $fillable = [
        'assess_id',
        'fire_safety',
        'fire_safety_other',
        'setting_property_size_volume',
        'setting_non_conformities',
        'setting_fire_safety',
        'setting_show_vehicle_parking',
        'setting_equipment_details',
        'setting_hazard_photo_required',
        'setting_assessors_note_required',
        'objective_scope',
        'executive_summary',
        'property_information',
        'comment',
        'created_at',
        'updated_at',
    ];

    // Getter/Setter
    public function getVehicleParkingAttribute()
    {
        if ($this->attributes['setting_show_vehicle_parking']) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function getPropertySizeVolumeAttribute()
    {
        if ($this->attributes['setting_property_size_volume']) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function getFireSafetySettingAttribute()
    {
        if ($this->attributes['setting_fire_safety']) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    public function getEquipmentDetailsAttribute()
    {
        if ($this->attributes['setting_equipment_details']) {
            return 'Required';
        } else {
            return 'Not Required';
        }
    }

    public function getHazardPhotoAttribute()
    {
        if ($this->attributes['setting_hazard_photo_required']) {
            return 'Required';
        } else {
            return 'Not Required';
        }
    }

    public function getAssessorsNoteAttribute()
    {
        if ($this->attributes['setting_assessors_note_required']) {
            return 'Required';
        } else {
            return 'Not Required';
        }
    }

    public function getNonConformitiesAttribute()
    {
        if ($this->attributes['setting_non_conformities']) {
            return 'Yes';
        } else {
            return 'No';
        }
    }
}
