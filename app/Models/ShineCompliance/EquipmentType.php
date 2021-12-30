<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentType extends ModelBase
{
    protected $table = 'cp_equipment_types';

    protected $fillable = [
        'description',
        'template_id',
    ];

    public function template()
    {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentTemplate', 'template_id', 'id');
    }
}
