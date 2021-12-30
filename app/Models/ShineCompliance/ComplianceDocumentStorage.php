<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ComplianceDocumentStorage extends ModelBase
{
    protected $table = 'compliance_document_storage';

    protected $fillable = [
        "object_id",
        "type",
        "path",
        "file_name",
        "mime",
        "size",
        "addedDate",
        "addedBy",
    ];
}
