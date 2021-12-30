<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PublishedWorkRequest extends ModelBase
{
    protected $table = 'tbl_published_work_request';

    protected $fillable = [
        'work_request_id',
        'name',
        'revision',
        'type',
        'size',
        'filename',
        'mime',
        'path',
        'created_by'
    ];

//    public function workRequest() {
//        return $this->belongsTo('App\Models\WorkRequest', 'survey_id', 'id');
//    }
}
