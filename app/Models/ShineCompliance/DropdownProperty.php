<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class DropdownProperty extends ModelBase
{
    protected $table = 'tbl_dropdown_property';

    protected $fillable = [
        'id',
        'description',
        'endpoint',
        'multi_tiered'
    ];

}
