<?php

namespace App\Models\DropdownItemValue;

use App\Models\ModelBase;

class SampleIdValue extends ModelBase
{
    protected $table = 'tbl_item_sample_id_value';

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
    public function items() {
        return $this->hasOne('App\Models\Item', 'id', 'item_id');
    }
}
