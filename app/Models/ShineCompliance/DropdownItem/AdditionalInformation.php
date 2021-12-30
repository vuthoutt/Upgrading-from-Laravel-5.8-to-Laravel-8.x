<?php

namespace App\Models\ShineCompliance\DropdownItem;

use Illuminate\Database\Eloquent\Model as ModelBase;

class AdditionalInformation extends ModelBase
{
    protected $table = 'tbl_item_additional_information';

    protected $fillable = [
        "description",
        "dropdown_item_id",
        "order",
        "score",
        "other",
        "decommissioned",
        "parent_id",
        "removal_cost",
        "deleted_by",
        "created_by",
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    public function parents() {
        return $this->hasOne('App\Models\DropdownItem\AdditionalInformation', 'id', 'parent_id');
    }

    public function allParents() {
        return $this->parents()->with('allParents');
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\DropdownItem\AdditionalInformation', 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }
}
