<?php

namespace App\Models;

use App\Models\ModelBase;

class ApiBackup extends ModelBase
{
    protected $table = 'tbl_upload_backup';

    protected $fillable = [
        "user_id",
        "survey_id",
        "version",
        "path",
        "file_name",
        "image_count",
        "upload_success",
        "size",
    ];

    public function uploadImages() {
        return $this->hasMany('App\Models\ApiBackupImage', 'backup_id', 'id');
    }
}
