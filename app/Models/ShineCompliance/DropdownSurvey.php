<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class DropdownSurvey extends ModelBase
{
    protected $table = 'tbl_dropdown_survey';

    protected $fillable = [
        'id',
        'description',
        'endpoint',
        'multi_tiered'
    ];

}
