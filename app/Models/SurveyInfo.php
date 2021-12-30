<?php

namespace App\Models;

use App\Models\ModelBase;

class SurveyInfo extends ModelBase
{
    protected $table = 'tbl_survey_info';

    protected $fillable = [
        'survey_id',
        'objectives_scope',
        'comments',
        'executive_summary',
        'property_data',
        'method',
        'limitations',
        'method_style',
        'property_data',
    ];

    public function getpropertyDataAttribute($value) {
        return json_decode($value);
    }
}
