<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentManagementValue extends Model
{
    protected $table = 'cp_assessment_management_info_values';

    protected $fillable = [
        'assess_id',
        'question_id',
        'answer_id',
        'answer_other',
    ];

    public function answer()
    {
        return $this->hasOne(AssessmentManagementAnswer::class, 'id', 'answer_id');
    }
}
