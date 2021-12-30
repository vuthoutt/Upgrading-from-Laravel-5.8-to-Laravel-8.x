<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class CPRoleUpdateProperty extends ModelBase
{
    protected $table = 'cp_job_role_edit_property';

    protected $fillable = [
        'role_id',
        'property_id',
    ];
}
