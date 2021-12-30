<?php

namespace App\Models;

use App\Models\ModelBase;

class Policy extends ModelBase
{
    protected $table = 'tbl_policies';

    public static $PROGRAMME_TYPE_OTHER = 17;

    protected $fillable = [
        "name",
        "size",
        "file_name",
        "mime",
        "added",
        "added_by",
        "client_id",
    ];

}
