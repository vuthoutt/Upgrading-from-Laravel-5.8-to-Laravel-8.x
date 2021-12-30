<?php

namespace App\Models;

use App\Models\ModelBase;

class DocumentBluelightService extends ModelBase
{
    protected $table = 'tbl_document_blue_light_service';

    protected $fillable = [
        "zone_id",
        "name",
        "type",
        "size",
        "mime",
        "status",
        "path",
        "number_record",
    ];

}
