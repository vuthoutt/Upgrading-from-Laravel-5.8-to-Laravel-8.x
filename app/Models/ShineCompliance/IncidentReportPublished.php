<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class IncidentReportPublished extends ModelBase
{
    protected $table = 'incident_report_published';

    protected $fillable = [
        'incident_id',
        'name',
        'revision',
        'type',
        'size',
        'filename',
        'mime',
        'path',
        'created_by'
    ];

    public function incidentReport() {
        return $this->belongsTo(incidentReport::class, 'incident_id', 'id');
    }

}
