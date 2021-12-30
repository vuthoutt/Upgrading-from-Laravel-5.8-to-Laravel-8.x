<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardActionRecommendationNoun extends ModelBase
{
    protected $table = 'cp_hazard_action_recommendation_noun';

    protected $fillable = [
        'description',
        'order',
        'other',
        'type',
    ];
}
