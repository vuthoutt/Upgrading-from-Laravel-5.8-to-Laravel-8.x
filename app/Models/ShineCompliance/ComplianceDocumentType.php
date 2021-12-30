<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceDocumentType extends ModelBase
{
    protected $table = 'compliance_document_types';

    protected $fillable = [
        'description',
        'type',
        'is_external_ms',
        'is_reinspected',
    ];

}
