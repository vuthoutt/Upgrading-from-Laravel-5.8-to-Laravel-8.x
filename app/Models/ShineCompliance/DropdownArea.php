<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class DropdownArea extends ModelBase
{
    protected $table = 'tbl_dropdown_area';

    protected $fillable = [
        'id',
        'description',
        'endpoint',
        'multi_tiered'
    ];

}
