<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class NonconformityValidate extends ModelBase
{
    protected $table = 'cp_validate_nonconformities';

    protected $fillable = [
        'reference',
        'record_id',
        'equipment_id',
        'hazard_id',
        'type',
        'created_by',
        'updated_by'
    ];

    public function equipment() {
        return $this->belongsTo('App\Models\ShineCompliance\Equipment','id', 'equipment_id');
    }

    public function hazard() {
        return $this->hasOne('App\Models\ShineCompliance\Hazard','id', 'hazard_id');
    }

}
