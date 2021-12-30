<?php

namespace App\Models;

use App\Models\ModelBase;

class ShineAppDocumentStorage extends ModelBase
{
    protected $table = 'tbl_shine_app_document_storage';

    protected $fillable = [
    "manifest_id",
    "record_id",
    "type",
    "survey_id",
    "path",
    "file_name",
    "mime",
    "size",
    ];
}
