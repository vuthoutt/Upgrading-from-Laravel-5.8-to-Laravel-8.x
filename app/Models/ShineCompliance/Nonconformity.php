<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Nonconformity extends ModelBase
{
    protected $table = 'cp_equipment_nonconformities';

    protected $fillable = [
        'reference',
        'record_id',
        'property_id',
        'assess_id',
        'equipment_id',
        'hazard_id',
        'field',
        'type',
        'is_deleted',
        'created_by',
        'updated_by'
    ];

    public function equipment() {
        return $this->belongsTo('App\Models\ShineCompliance\Equipment', 'equipment_id','id');
    }

    public function hazard() {
        return $this->hasOne('App\Models\ShineCompliance\Hazard','id', 'hazard_id');
    }

    public function hazardPDF() {
        return $this->hasOne('App\Models\ShineCompliance\Hazard','id', 'hazard_id')->where('is_temp', 0);
    }

}
