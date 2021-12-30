<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentManagementQuestion extends Model
{
    protected $table = 'cp_assessment_management_info_questions';

    protected $fillable = [
        'description',
        'answer_type',
        'pre_loaded',
        'is_other',
        'order',
    ];

    public function answerValue()
    {
        return $this->hasOne(AssessmentManagementValue::class, 'question_id', 'id');
    }

    public function answers()
    {
        return $this->hasMany(AssessmentManagementAnswer::class, 'question_id');
    }
}
