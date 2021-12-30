<?php

namespace App\Models\DropdownItemValue;

use App\Models\ModelBase;

class ItemNoAccessValue extends ModelBase
{
    protected $table = 'tbl_item_no_access_value';

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
    public function ItemNoAccess() {
        return $this->belongsTo('App\Models\DropdownItem\ItemNoAccess','dropdown_data_item_id', 'id');
    }


}
