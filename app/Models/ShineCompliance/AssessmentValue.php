<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AssessmentValue extends ModelBase
{
    protected $table = 'cp_assessment_values';

    protected $fillable = [
        'assess_id',
        'question_id',
        'answer_id',
        'other',
        'statement',
        'statement_other',
        'observations',
        'created_at',
        'updated_at',
    ];

    public function answerType(){
        return $this->hasOne(AssessmentAnswer::class, 'id', 'answer_id');
    }

    public function otherHazardQuestion(){
        return $this->belongsTo(AssessmentQuestion::class, 'question_id', 'id');
    }
}
