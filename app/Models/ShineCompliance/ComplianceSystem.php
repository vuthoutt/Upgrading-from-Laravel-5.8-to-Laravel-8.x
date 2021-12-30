<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceSystem extends ModelBase
{
    protected $table = 'compliance_systems';

    protected $fillable = [
        'record_id',
        'is_locked',
        'property_id',
        'assess_id',
        'name',
        'reference',
        'type',
        'classification',
        'decommissioned',
        'comment',
        'created_by',
        'updated_by',
    ];

    public function systemType() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceSystemType', 'id', 'type');
    }

    public function systemClassification() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceSystemClassification', 'id', 'classification');
    }

    public function property() {
        return $this->belongsTo('App\Models\ShineCompliance\Property', 'property_id', 'id');
    }

    public function equipments(){
        return $this->hasMany(Equipment::class, 'system_id', 'id');
    }

    public function registerEquipments(){
        return $this->hasMany(Equipment::class, 'system_id', 'id')
            ->where('assess_id', COMPLIANCE_ASSESSMENT_REGISTER)
            ->where('decommissioned',COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED);
    }

    public function programmes(){
        return $this->hasMany(ComplianceProgramme::class, 'system_id', 'id');
    }

    public function documents(){
        return $this->hasMany(ComplianceDocument::class, 'system_id', 'id');
    }

    public function assessment() {
        return $this->hasOne('App\Models\ShineCompliance\Assessment','id', 'assess_id');
    }

    public function complianceDocumentStorage() {
        return $this->hasOne('App\Models\ShineCompliance\ComplianceDocumentStorage','object_id', 'id')->where('type',COMPLIANCE_SYSTEM_PHOTO);
    }

    public function getTitleAttribute($value) {
        return $this->attributes['reference'] . ' - ' .$this->attributes['name'];
    }
}
