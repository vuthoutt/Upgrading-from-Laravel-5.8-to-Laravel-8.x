<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class SurveyType extends ModelBase
{
    protected $table = 'tbl_survey_type';

    protected $fillable = [
        "description",
        "order",
        "color",
    ];

}
