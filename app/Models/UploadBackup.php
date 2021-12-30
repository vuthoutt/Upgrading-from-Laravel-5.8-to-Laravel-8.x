<?php

namespace App\Models;

use App\Models\ModelBase;

class UploadBackup extends ModelBase
{
    protected $table = 'tbl_upload_backup';

    protected $fillable = [
        'id',
        'user_id',
        'survey_id',
        'path',
        'file_name',
        'size',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
