<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ProjectType extends ModelBase
{
    protected $table = 'tbl_project_types';

    protected $fillable = [
        "description",
        "order",
        "compliance_type",
    ];

}
