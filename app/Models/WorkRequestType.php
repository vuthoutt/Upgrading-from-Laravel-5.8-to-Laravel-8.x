<?php

namespace App\Models;

use App\Models\ModelBase;

class WorkRequestType extends ModelBase
{
    protected $table = 'tbl_work_request_type';

    protected $fillable = [
        'description'
    ];
}
