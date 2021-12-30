<?php


namespace App\Models\ShineCompliance;


use Illuminate\Database\Eloquent\Model;

class AssessmentStatementAnswer extends Model
{
    protected $table = 'cp_assessment_statement_answers';

    protected $fillable = [
        'question_id',
        'description',
        'order',
        'other',
    ];
}
