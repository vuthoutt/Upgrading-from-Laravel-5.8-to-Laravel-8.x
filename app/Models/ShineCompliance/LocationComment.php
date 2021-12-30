<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class LocationComment extends ModelBase
{
    protected $table = 'tbl_location_comment';

    protected $fillable = [
        "record_id",
        "comment",
        "parent_reference",
    ];

}
