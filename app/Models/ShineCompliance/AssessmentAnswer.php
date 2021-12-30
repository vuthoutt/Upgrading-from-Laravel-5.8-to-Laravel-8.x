<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AssessmentAnswer extends ModelBase
{
    protected $table = 'cp_assessment_answers';

    protected $fillable = [
        'type',
        'description'
    ];
}
