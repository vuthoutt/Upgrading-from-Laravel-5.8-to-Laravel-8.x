<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentSpecificLocationValue extends ModelBase
{
    protected $table = 'cp_equipment_specific_location_value';

    protected $fillable = [
        'equipment_id',
        'parent_id',
        'value',
        'other',
        'created_at',
        'updated_at',
    ];
}
