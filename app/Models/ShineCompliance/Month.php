<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Month extends ModelBase
{
    protected $table = 'tbl_month';

    protected $fillable = [
        "id",
        "description",
        "value"
    ];

}
