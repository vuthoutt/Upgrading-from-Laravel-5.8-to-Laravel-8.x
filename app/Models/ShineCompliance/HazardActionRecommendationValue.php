<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardActionRecommendationValue extends ModelBase
{
    protected $table = 'cp_hazard_action_recommendation_value';

    protected $fillable = [
        'hazard_id',
        'parent_id',
        'value',
        'other',
        'created_at',
        'updated_at',
    ];
}
