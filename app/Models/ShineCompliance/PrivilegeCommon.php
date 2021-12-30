<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PrivilegeCommon extends ModelBase
{
    protected $table = 'cp_privilege_common';

    protected $fillable = [
        'name',
        'type',
    ];

}
