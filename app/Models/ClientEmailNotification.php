<?php

namespace App\Models;

use App\Models\ModelBase;

class ClientEmailNotification extends ModelBase
{
    protected $table = 'tbl_client_email_notification';

    protected $fillable = [
        "user_id",
        "email_type",
        "last_send",

    ];


}
