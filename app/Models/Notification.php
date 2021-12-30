<?php

namespace App\Models;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends ModelBase
{
    use SoftDeletes;
    protected $table = 'tbl_notifications';

    protected $fillable = [
        "user_id",
        "client_id",
        "contractor_id",
        "project_id",
        "document_id",
        "type",
        "status",
        "added_date",
        "checked_date",
        "checked_user_id",
    ];

}
