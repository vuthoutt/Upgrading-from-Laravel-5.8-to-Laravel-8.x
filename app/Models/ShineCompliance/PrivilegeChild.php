<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PrivilegeChild extends ModelBase
{
    protected $table = 'cp_privilege_child';

    protected $fillable = [
        'name',
        'privilege_id',
        'is_view',
    ];

}
