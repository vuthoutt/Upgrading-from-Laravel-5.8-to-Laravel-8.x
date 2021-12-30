<?php

namespace App\Models;

use App\Models\ModelBase;

class PrivilegeChild extends ModelBase
{
    protected $table = 'tbl_privilege_child';

    protected $fillable = [
        'name',
        'type',
    ];

}
