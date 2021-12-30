<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceDocumentStatus extends ModelBase
{
    protected $table = 'cp_document_status_dropdown';

    protected $fillable = [
        'description',
    ];

}
