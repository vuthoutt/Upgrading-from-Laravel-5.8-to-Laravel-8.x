<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardSpecificLocationValue extends ModelBase
{
    protected $table = 'cp_hazard_specific_location_value';

    protected $fillable = [
        'hazard_id',
        'parent_id',
        'value',
        'other',
        'created_at',
        'updated_at',
    ];
}
