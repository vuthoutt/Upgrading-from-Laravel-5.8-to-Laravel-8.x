<?php

namespace App\Models;

use App\Models\ModelBase;

class DecommissionComment extends ModelBase
{
    protected $table = 'tbl_decommissioned_comment';

    protected $fillable = [
        "record_id",
        "type",
        "category",
        "comment",
        "parent_reference",
        "user_id"
    ];

    public function comments() {
        return $this->belongsTo('App\Models\DecommissionReason', 'comment', 'id');
    }
}
