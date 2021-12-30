<?php

namespace App\Models;

use App\Models\ModelBase;

class ElearningLog extends ModelBase
{
    protected $table = 'tbl_log_elearning';

    protected $fillable = [
        "user_id",
        "message",
        "exception_message",
        "status_code",
        "ip_address",
        "type",

    ];

}
