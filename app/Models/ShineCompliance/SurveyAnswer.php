<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class SurveyAnswer extends ModelBase
{
    protected $table = 'tbl_surveys_answer';

    protected $fillable = [
        "survey_id",
        "question_id",
        "answer_id",
        "answerOther",
        "comment"
    ];

    public function dropdownAnswerData() {
        return $this->belongsTo('App\Models\ShineCompliance\DropdownDataSurvey', 'answer_id','id');
    }

    public function dropdownQuestionData() {
        return $this->belongsTo('App\Models\ShineCompliance\DropdownDataSurvey', 'question_id','id');
    }
}
