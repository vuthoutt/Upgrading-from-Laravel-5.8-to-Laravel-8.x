<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class DropdownDataArea extends ModelBase
{
    protected $table = 'tbl_dropdown_data_area';

    protected $fillable = [
        'id',
        'description',
        'dropdown_area_id',
        'order',
        'score',
        'other',
        'decommissioned',
        'parent_id',
        'removal_cost'
    ];

}
