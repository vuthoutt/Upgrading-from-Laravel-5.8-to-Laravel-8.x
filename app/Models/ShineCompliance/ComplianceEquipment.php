<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceEquipment extends ModelBase
{
    protected $table = 'compliance_equipments';

    protected $fillable = [
        'record_id',
        'is_locked',
        'reference',
        'property_id',
        'system_id',
        'name',
        'type',
        'status',
        'state',
        'reason',
        'decommissioned',
        'created_by',
        'updated_by'
    ];

    public function equipmentType() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceEquipmentType', 'id', 'type');
    }

    public function system() {
        return $this->belongsTo('App\Models\ShineCompliance\ComplianceSystem', 'system_id', 'id');
    }

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property', 'property_id', 'id');
    }

    public function documents(){
        return $this->hasMany(ComplianceDocument::class,'equipment_id','id');
    }

    public function getStateTextAttribute() {
        if ($this->attributes['state'] == EQUIPMENT_INACCESSIBLE_STATE) {
            return 'Inaccessible';
        }
        if ($this->attributes['state'] == EQUIPMENT_ACCESSIBLE_STATE) {
            return 'Accessible';
        }
        return '';
    }
}
