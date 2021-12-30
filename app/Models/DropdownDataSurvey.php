<?php

namespace App\Models;

use App\Models\ModelBase;

class DropdownDataSurvey extends ModelBase
{
    protected $table = 'tbl_dropdown_data_survey';

    protected $fillable = [
        'description',
        'dropdown_survey_id',
        'order',
        'score',
        'other',
        'decommissioned',
        'parent_id',
        'removal_cost'
    ];


    public function getSurveySelectedAnswer($survey_id, $question_id) {

    }
}
