<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardType extends ModelBase
{
    protected $table = 'cp_hazard_type';

    protected $fillable = [
        'description',
        'type',
        'parent_id',
    ];
}
