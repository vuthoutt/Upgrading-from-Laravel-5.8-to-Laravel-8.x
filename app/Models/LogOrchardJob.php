<?php

namespace App\Models;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;

class LogOrchardJob extends ModelBase
{
    protected $table = 'tbl_orchard_job_log';

    protected $fillable = [
        'job_id',
        'sent_request',
        'response_request',
        'created_date',
        'step',
        'status_code',
    ];
}
