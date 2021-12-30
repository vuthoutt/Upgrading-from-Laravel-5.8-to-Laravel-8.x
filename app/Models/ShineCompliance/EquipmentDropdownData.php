<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentDropdownData extends ModelBase
{
    protected $table = 'cp_equipment_dropdown_data';

    protected $fillable = [
        'dropdown_id',
        'description',
        'other',
        'parent_id',
        'order',
    ];
}
