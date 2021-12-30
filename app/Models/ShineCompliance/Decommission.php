<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Decommission extends ModelBase
{
    protected $table = 'tbl_decommission';

    protected $fillable = [
        'description',
    ];

}
