<?php

namespace App\Models;

use App\Models\ModelBase;

class SurveySetting extends ModelBase
{
    protected $table = 'tbl_survey_setting';

    protected $fillable = [
        'id',
        'survey_id',
        'is_require_priority_assessment',
        'is_require_construction_details',
        'is_require_location_void_investigations',
        'is_require_location_construction_details',
        'is_require_photos',
        'is_require_license_status',
        'is_require_size',
        'is_require_r_and_d_elements',
        'is_property_plan_photo',
        'is_require_site_objective_scope'
    ];


}
