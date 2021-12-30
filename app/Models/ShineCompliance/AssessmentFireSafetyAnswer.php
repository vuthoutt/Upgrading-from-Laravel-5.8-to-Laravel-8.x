<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentFireSafetyAnswer extends Model
{
    protected $table = 'cp_assessment_fire_safety_answers';

    protected $fillable = [
        'description',
        'order',
        'other',
    ];
}
