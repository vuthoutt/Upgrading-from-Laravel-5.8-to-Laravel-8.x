<?php

namespace App\Models\DropdownItem;

use Illuminate\Database\Eloquent\Model as ModelBase;

class SampleComment extends ModelBase
{
    protected $table = 'tbl_item_sample_comment';

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
        return $this->hasOne('App\Models\DropdownItem\SampleComment', 'id', 'parent_id');
    }

    public function allParents() {
        return $this->parents()->with('allParents');
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\DropdownItem\SampleComment', 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }
}
