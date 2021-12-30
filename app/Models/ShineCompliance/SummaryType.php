<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class SummaryType extends ModelBase
{
    protected $table = 'tbl_summary_types';

    protected $fillable = [
        "description",
        "value",
        "order",

    ];

}
