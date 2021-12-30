<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class WorkScope extends ModelBase
{
    protected $table = 'tbl_work_scope';

    protected $fillable = [
        'work_id',
        'scope_of_work',
        'air_test_type',
        'enclosure_size',
        'duration_of_work',
        'isolation_required',
        'isolation_required_comment',
        'decant_required',
        'decant_required_comment',
        'reinstatement_requirements',
        'number_of_rooms',
        'unusual_requirements',
        'reported_by',
        'access_note',
        'location_note',
    ];

}
