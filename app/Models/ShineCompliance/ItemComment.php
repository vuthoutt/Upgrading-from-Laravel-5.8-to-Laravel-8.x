<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class ItemComment extends ModelBase
{
    protected $table = 'tbl_item_comment';

    protected $fillable = [
        "record_id",
        "comment",
        "parent_reference",
    ];

}
