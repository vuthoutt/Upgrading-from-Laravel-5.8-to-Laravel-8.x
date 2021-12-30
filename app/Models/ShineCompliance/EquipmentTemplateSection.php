<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentTemplateSection extends ModelBase
{
    protected $table = 'cp_equipment_template_sections';

    protected $fillable = [
        'template_id',
        'section_id',
    ];

    public function section()
    {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentSection','id', 'section_id');
    }
}
