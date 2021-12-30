<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class WorkFlow extends ModelBase
{
    protected $table = 'tbl_work_flow';

    protected $fillable = [
        'id',
        'description',
        'client_id'
    ];

}
