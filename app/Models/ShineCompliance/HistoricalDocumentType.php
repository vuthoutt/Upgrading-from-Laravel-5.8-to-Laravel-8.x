<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class HistoricalDocumentType extends ModelBase
{
    protected $table = 'tbl_historical_document_type';

    protected $fillable = [
        "description",
        "is_external_ms",
    ];

}
