<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AssessmentResult extends ModelBase
{
    protected $table = 'cp_assessment_result';

    protected $fillable = [
        'assess_id',
        'section_id',
        'total_question',
        'total_yes',
        'total_no',
        'total_score'
    ];
}
