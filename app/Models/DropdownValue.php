<?php

namespace App\Models;

use App\Models\ModelBase;

class DropdownValue extends ModelBase
{
    protected $table = 'tbl_dropdown_value';

    protected $fillable = [
        'id',
        'item_id',
        'dropdown_item_id',
        'dropdown_data_item_parent_id',
        'dropdown_data_item_id',
        'dropdown_other',
    ];

}
