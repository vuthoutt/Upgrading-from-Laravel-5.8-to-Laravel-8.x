<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AssessmentQuestion extends ModelBase
{
    protected $table = 'cp_assessment_questions';

    protected $fillable = [
        'section_id',
        'description',
        'score',
        'answer_type',
        'is_key',
        'good_answer',
        'preloaded_comment',
        'hz_name',
        'hz_verb_id',
        'hz_noun_id',
    ];

    public function answer()
    {
        return $this->hasOne(AssessmentValue::class, 'question_id', 'id');
    }

    public function answerType(){
        return $this->hasMany(AssessmentAnswer::class, 'type', 'answer_type');
    }

    public function statementAnswers()
    {
        return $this->hasMany(AssessmentStatementAnswer::class, 'question_id');
    }
}
