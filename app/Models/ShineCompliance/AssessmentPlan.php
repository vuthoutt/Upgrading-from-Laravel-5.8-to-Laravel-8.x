<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AssessmentPlan extends ModelBase
{
    protected $table = 'cp_assessment_plans';

    protected $fillable = [
        'reference',
        'assess_id',
        'plan_reference',
        'decription',
        'is_note',
        'note',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
}
