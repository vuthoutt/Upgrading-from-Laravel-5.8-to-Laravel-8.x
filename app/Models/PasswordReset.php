<?php

namespace App\Models;

use App\Models\ModelBase;

class PasswordReset extends ModelBase
{
    protected $table = 'tbl_password_resets';

    protected $fillable = [
        "email",
        "token",
        "created_at",
    ];
}