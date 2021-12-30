<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class IncidentReportInvolvedPerson extends ModelBase
{
    protected $table = 'incident_report_involved_persons';

    protected $fillable = [
        'id',
        'incident_report_id',
        'user_id',
        'non_user',
        'injury_type',
        'injury_type_other',
        'part_of_body_affected',
        'apparent_cause',
        'apparent_cause_other',
        'created_at',
        'updated_at',
    ];

    public function user() {
        return $this->belongsTo(User::class,'user_id','id');
    }

}
