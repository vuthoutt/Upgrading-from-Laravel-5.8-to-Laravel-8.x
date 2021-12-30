<?php

namespace App\Models;

use App\Models\ModelBase;

class WorkStream extends ModelBase
{
    protected $table = 'tbl_work_stream';

    protected $fillable = [
        'id',
        'description',
        'is_deleted',
    ];

}
