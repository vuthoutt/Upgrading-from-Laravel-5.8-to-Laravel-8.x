<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentManagementAnswer extends Model
{
    protected $table = 'cp_assessment_management_info_answers';

    protected $fillable = [
        'question_id',
        'description',
        'order',
        'other',
    ];
}
