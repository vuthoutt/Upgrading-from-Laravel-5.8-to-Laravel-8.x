<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Template extends ModelBase
{
    protected $table = 'tbl_templates';

    protected $fillable = [
        "category_id",
        "name",
        "size",
        "file_name",
        "mime",
        "added"
    ];

}
