<?php

namespace App\Models;

use App\Models\ModelBase;

class ProjectSponsor extends ModelBase
{
    protected $table = 'tbl_project_sponsor';

    protected $fillable = [
        "description",
        "position",

    ];

}
