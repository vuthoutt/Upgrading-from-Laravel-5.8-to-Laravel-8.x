<?php

namespace App\Models;

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
