<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class IncidentReportDropdownData extends ModelBase
{
    protected $table = 'incident_report_dropdown_data';

    protected $fillable = [
        'dropdown_id',
        'description',
        'order',
        'other',
    ];
}
