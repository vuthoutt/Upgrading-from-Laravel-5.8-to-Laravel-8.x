<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class RoleViewProperty extends ModelBase
{
    protected $table = 'role_view_property';

    protected $fillable = [
        'role_id',
        'property_id',
    ];
}
