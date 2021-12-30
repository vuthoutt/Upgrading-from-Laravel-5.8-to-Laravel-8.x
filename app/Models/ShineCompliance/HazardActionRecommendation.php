<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardActionRecommendation extends ModelBase
{
    protected $table = 'cp_hazard_action_recommendation';

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
        'updated_at',
    ];
}
