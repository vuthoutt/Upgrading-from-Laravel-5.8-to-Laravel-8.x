<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogLogin extends ModelBase
{
    use SoftDeletes;
    protected $table = 'tbl_log_login';

    protected $fillable = [
        'id',
        'user_id',
        'logusername',
        'logpassword',
        'logip',
        'logtime',
        'success',
        'created_at',
    ];

}
