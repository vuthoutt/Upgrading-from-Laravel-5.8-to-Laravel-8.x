<?php

namespace App\Models;

use App\Models\ModelBase;

class DropdownDataProperty extends ModelBase
{
    protected $table = 'tbl_dropdown_data_property';


    public static $PRIMARY_AND_SECONDARY_USE = 1;
    public static $PRIMARY_AND_SECONDARY_OTHER = 13;

    protected $fillable = [
        'id',
        'description',
        'dropdown_property_id',
        'order',
        'score',
        'other',
        'decommissioned',
        'parent_id',
        'removal_cost'
    ];

}
