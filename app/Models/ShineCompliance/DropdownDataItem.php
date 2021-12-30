<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class DropdownDataItem extends ModelBase
{
    protected $table = 'tbl_dropdown_data_item';

    protected $fillable = [
        'id',
        'description',
        'dropdown_item_id',
        'order',
        'score',
        'other',
        'decommissioned',
        'parent_id',
        'removal_cost'
    ];

    public function childrens()
    {
        return $this->hasMany('App\Models\DropdownDataItem', 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }
}
