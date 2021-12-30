<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ShineDocumentStorage extends ModelBase
{
    protected $table = 'tbl_shine_document_storage';

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
