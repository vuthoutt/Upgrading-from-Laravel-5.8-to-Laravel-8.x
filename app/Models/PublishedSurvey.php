<?php

namespace App\Models;

use App\Models\ModelBase;

class PublishedSurvey extends ModelBase
{
    protected $table = 'tbl_published_surveys';

    protected $fillable = [
        'id',
        'survey_id',
        'name',
        'revision',
        'type',
        'size',
        'filename',
        'mime',
        'path',
        'is_large_file',
        'created_by'
    ];

    public function survey() {
        return $this->belongsTo('App\Models\Survey', 'survey_id', 'id');
    }
}
