<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentOtherValue extends Model
{
    protected $table = 'cp_assessment_other_info_values';

    protected $fillable = [
        'assess_id',
        'question_id',
        'answer_id',
        'answer_other',
    ];

    public function answer()
    {
        return $this->hasOne(AssessmentOtherAnswer::class, 'id', 'answer_id');
    }
}
