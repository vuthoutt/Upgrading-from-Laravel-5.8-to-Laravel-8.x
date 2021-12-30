<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HazardLikelihoodHarm extends ModelBase
{
    protected $table = 'cp_hazard_likelihood_harm';

    protected $fillable = [
        'description',
        'order',
        'score',
        'other',
    ];
}
