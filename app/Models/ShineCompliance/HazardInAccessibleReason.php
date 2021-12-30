<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardInAccessibleReason extends ModelBase
{
    protected $table = 'cp_hazard_inaccessible_reason';

    protected $fillable = [
        'description',
        'other',
        'parent_id',
        'order',
    ];
}
