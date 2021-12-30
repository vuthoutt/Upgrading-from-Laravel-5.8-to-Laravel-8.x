<?php

namespace App\Models;

use App\Models\ModelBase;
use Carbon\Carbon;

class WorkEmailCC extends ModelBase
{
    protected $table = 'tbl_work_email_cc';

    protected $fillable = [
        'work_id',
        'email'
    ];
    public $timestamps = false;
}
