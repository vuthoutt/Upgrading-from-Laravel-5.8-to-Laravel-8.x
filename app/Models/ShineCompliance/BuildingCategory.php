<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class BuildingCategory extends ModelBase
{
    protected $table = 'tbl_building_category';

    protected $fillable = [
        'id',
        'description',
        'order',
        'score',
        'other',
        'decommisioned',
        'parent_id',
        'removal_cost'
    ];

}
