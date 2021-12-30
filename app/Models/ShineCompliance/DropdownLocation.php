<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class DropdownLocation extends ModelBase
{
    protected $table = 'tbl_dropdown_location';

    protected $fillable = [
        'id',
        'description',
        'endpoint',
        'multi_tiered'
    ];

}
