<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class DecommissionReason extends ModelBase
{
    protected $table = 'tbl_decommission_reasons';

    protected $fillable = [
        'type',
        'parent_id',
        'description',
    ];

}
