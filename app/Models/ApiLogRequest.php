<?php

namespace App\Models;

use App\Models\ModelBase;

class ApiLogRequest extends ModelBase
{
    protected $table = 'tbl_api_log_request';

    protected $fillable = [
        "url",
        "method",
        "status_code",
        "body",
        "header",
        "ip",

    ];

}
