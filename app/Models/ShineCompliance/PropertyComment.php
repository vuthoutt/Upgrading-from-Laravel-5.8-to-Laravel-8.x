<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PropertyComment extends ModelBase
{
    protected $table = 'tbl_property_comment';

    protected $fillable = [
        "record_id",
        "comment",
        "parent_reference",
    ];

}
