<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Elearning extends ModelBase
{
    protected $table = 'tbl_elearning';

    protected $fillable = [
        "user_id",
        "token_id",
        "login_key",

    ];

}
