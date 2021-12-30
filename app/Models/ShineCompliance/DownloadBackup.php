<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class DownloadBackup extends ModelBase
{
    protected $table = 'tbl_download_backup';

    protected $fillable = [
        'id',
        'user_id',
        'upload_backup_id',
        'deleted_by',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

}
