<?php

namespace App\Models;

use App\Models\ModelBase;

class DownloadManifest extends ModelBase
{
    protected $table = 'tbl_download_manifest';

    protected $fillable = [
        'id',
        'user_id',
        'list_survey_id',
        'deleted_by',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

}
