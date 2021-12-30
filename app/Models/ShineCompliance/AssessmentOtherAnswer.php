<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentOtherAnswer extends Model
{
    protected $table = 'cp_assessment_other_info_answers';

    protected $fillable = [
        'description',
        'order',
        'other',
    ];
}
