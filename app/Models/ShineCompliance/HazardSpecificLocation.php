<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardSpecificLocation extends ModelBase
{
    protected $table = 'cp_hazard_specific_location';

    protected $fillable = [
        'description',
        'dropdown_item_id',
        'assess_type',
        'order',
        'score',
        'other',
        'decommissioned',
        'parent_id',
        'removal_cost',
        'deleted_by',
        'created_by',
        'created_at',
        'updated_at',
    ];

    public function parents() {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public function allParents() {
        return $this->parents()->with('allParents');
    }

    public function childrens()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function allChildrens()
    {
        return $this->childrens()->with('allChildrens');
    }
}
