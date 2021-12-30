<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentSpecificLocation extends ModelBase
{
    protected $table = 'cp_equipment_specific_location';

    protected $fillable = [
        'description',
        'dropdown_item_id',
        'order',
        'score',
        'other',
        'decommissioned',
        'parent_id',
        'removal_cost',
        'deleted_by',
        'created_by',
        'created_at',
    ];

    public function parents() {
        return $this->hasOne('App\Models\ShineCompliance\EquipmentSpecificLocation', 'id', 'parent_id');
    }

    public function allParents() {
        return $this->parents()->with('allParents');
    }

    public function childrens()
    {
        return $this->hasMany('App\Models\ShineCompliance\EquipmentSpecificLocation', 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }
}
