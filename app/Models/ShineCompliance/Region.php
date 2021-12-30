<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Region extends ModelBase
{
    protected $table = 'tbl_region';

    protected $fillable = [
        'id',
        'description'
    ];

}
