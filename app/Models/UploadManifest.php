<?php

namespace App\Models;

use App\Models\ModelBase;

class UploadManifest extends ModelBase
{
    protected $table = 'tbl_upload_manifest';

    protected $fillable = [
        'id',
        'survey_id',
        'status',
        'user_id',
        'total_floor',
        'total_room',
        'total_record',
        'total_image',
        'total_note',
        'deleted_by',
        'created_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function survey() {
        return $this->hasOne('App\Models\Survey','survey_id');
    }

    public function uploadDocumentStorage() {
        return $this->hasMany('App\Models\UploadDocumentStorage','manifest_id');
    }

}
