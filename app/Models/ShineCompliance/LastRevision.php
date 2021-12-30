<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class LastRevision extends ModelBase
{
    protected $table = 'tbl_last_revision';

    protected $fillable = [
        "last_revision",
        "zone_id",
        "status",
        "path"
    ];

}
