<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentSection extends ModelBase
{
    protected $table = 'cp_equipment_sections';

    protected $fillable = [
        'description',
        'field_name',
        'section'
    ];
}
