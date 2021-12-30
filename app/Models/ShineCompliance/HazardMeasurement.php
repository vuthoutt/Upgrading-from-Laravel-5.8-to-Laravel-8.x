<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardMeasurement extends ModelBase
{
    protected $table = 'cp_hazard_measurement';

    protected $fillable = [
        'description',
        'dropdown_item_id',
        'order',
        'score',
        'other',
        'decommissioned',
        'parent_id',
        'removal_cost',
        'deleted_by',
        'created_by',
        'created_at',
        'updated_at',
    ];
}
