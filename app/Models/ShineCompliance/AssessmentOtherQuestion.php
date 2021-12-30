<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentOtherQuestion extends Model
{
    protected $table = 'cp_assessment_other_info_questions';

    protected $fillable = [
        'description',
        'answer_type',
        'order',
    ];

    public function answerValue()
    {
        return $this->hasOne(AssessmentOtherValue::class, 'question_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(AssessmentOtherAnswer::class, 'question_id');
    }
}
