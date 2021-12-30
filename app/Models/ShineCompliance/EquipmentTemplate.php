<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentTemplate extends ModelBase
{
    protected $table = 'cp_equipment_templates';

    protected $fillable = [
        'decription',
    ];

    public function templateSections()
    {
        return $this->hasMany('App\Models\ShineCompliance\EquipmentTemplateSection', 'section_id', 'id');
    }
}
