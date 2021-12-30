<?php

namespace App\Models;

use App\Models\ModelBase;

class WorkSupportingDocument extends ModelBase
{
    protected $table = 'tbl_work_supporting_documents';

    protected $fillable = [
        'reference',
        'work_id',
        'name',
        'added',
        'created_at',
        'updated_at',

    ];

    public function shineDocumentStorage() {
        return $this->hasOne('App\Models\ShineDocumentStorage','object_id', 'id')->where('type',WORK_REQUEST_FILE);
    }

}
