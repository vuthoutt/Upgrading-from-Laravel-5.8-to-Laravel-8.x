<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardPotential extends ModelBase
{
    protected $table = 'cp_hazard_potential';

    protected $fillable = [
        'description',
        'order',
        'score',
        'other',
    ];
}
