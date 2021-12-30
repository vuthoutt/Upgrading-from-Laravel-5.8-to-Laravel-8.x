<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class UploadDocumentStorage extends ModelBase
{
    protected $table = 'tbl_upload_document_storage';

    protected $fillable = [
        'id',
        'survey_id',
        'manifest_id',
        'type',
        'data',
        'correct_id',
        'unique_id',
    ];

}
