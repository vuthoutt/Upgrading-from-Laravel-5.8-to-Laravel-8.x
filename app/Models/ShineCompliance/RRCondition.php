<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class RRCondition extends ModelBase
{
    protected $table = 'tbl_rr_conditions';

    protected $fillable = [
        "description",
        "order",
    ];

}
