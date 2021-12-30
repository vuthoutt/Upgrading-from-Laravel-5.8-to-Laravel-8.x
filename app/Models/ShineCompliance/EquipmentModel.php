<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentModel extends ModelBase
{
    protected $table = 'cp_equipment_model';

    protected $fillable = [
        'equipment_id',
        'manufacturer',
        'model',
        'dimensions_length',
        'dimensions_width',
        'dimensions_depth',
        'capacity',
        'stored_water',
        'created_at',
        'updated_at'
    ];
}
