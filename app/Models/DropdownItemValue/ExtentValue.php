<?php

namespace App\Models\DropdownItemValue;

use App\Models\ModelBase;

class ExtentValue extends ModelBase
{
    protected $table = 'tbl_item_extent_value';

    protected $fillable = [
        "item_id",
        "dropdown_item_id",
        "dropdown_data_item_parent_id",
        "dropdown_data_item_id",
        "dropdown_other",
        "deleted_by",
        "created_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];
    protected $hidden = ['item_id','id'];

}
