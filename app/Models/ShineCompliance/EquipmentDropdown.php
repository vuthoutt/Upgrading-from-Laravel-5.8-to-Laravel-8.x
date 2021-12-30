<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentDropdown extends ModelBase
{
    protected $table = 'cp_equipment_dropdown';

    protected $fillable = [
        'description',
    ];
}
