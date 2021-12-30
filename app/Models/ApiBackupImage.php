<?php

namespace App\Models;

use App\Models\ModelBase;

class ApiBackupImage extends ModelBase
{
    protected $table = 'tbl_upload_backup_image';

    protected $fillable = [
        'backup_id',
        'app_id',
        'type',
        'path',
        'file_name',
        'size',
    ];
}