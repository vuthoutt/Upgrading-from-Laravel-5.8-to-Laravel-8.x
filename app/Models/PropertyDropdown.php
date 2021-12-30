<?php

namespace App\Models;

use App\Models\ModelBase;

class PropertyDropdown extends ModelBase
{
    protected $table = 'tbl_property_dropdown';

    protected $fillable = [
        'id',
        'dropdown_id',
        'description',
        'order',
        'color',
        'other',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];


}
