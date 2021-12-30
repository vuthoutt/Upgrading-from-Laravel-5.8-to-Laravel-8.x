<?php

namespace App\Models;

use App\Models\ModelBase;

class DropdownItem extends ModelBase
{
    protected $table = 'tbl_dropdown_item';

    protected $fillable = [
        'id',
        'description',
        'endpoint',
        'multi_tiered'
    ];

}
