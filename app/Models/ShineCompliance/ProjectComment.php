<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ProjectComment extends ModelBase
{
    protected $table = 'tbl_project_comment';

    protected $fillable = [
        "record_id",
        "comment",
        "parent_reference",
    ];

}
