<?php

namespace App\Models\ShineCompliance\DropdownItemValue;

use App\Models\ModelBase;

class SubSampleIdValue extends ModelBase
{
    protected $table = 'tbl_item_sub_sample_id_value';

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
