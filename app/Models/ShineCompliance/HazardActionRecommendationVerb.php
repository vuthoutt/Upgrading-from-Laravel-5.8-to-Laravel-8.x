<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardActionRecommendationVerb extends ModelBase
{
    protected $table = 'cp_hazard_action_recommendation_verb';

    protected $fillable = [
        'description',
        'graphical_chart_type',
        'order',
        'other',
        'type',
    ];
}
