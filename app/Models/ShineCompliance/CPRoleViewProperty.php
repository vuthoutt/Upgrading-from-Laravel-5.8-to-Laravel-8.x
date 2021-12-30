<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class CPRoleViewProperty extends ModelBase
{
    protected $table = 'cp_job_role_view_property';

    protected $fillable = [
        'role_id',
        'property_id',
    ];
}
