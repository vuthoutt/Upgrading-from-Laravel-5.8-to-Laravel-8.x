<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class IncidentReportDocument extends ModelBase
{
    protected $table = 'incident_report_documents';

    protected $fillable = [
        'id',
        'incident_report_id',
        'reference',
        'path',
        'filename',
        'mime',
        'size',
        'added_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function incidentReport() {
        return $this->belongsTo(IncidentReport::class,'incident_report_id','id');
    }
}
