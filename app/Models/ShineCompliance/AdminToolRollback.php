<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class AdminToolRollback extends ModelBase
{
    protected $table = 'tbl_admin_tool_rollback';

    protected $fillable = [
        "id",
        "admin_tool_id",
        "data",
        "created_at",
        "updated_at"
    ];

}
