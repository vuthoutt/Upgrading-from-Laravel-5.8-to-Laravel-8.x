<?php

namespace App\Models;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;

class LogOrchard extends ModelBase
{
    protected $table = 'tbl_log_orchard';

    protected $fillable = [
        'id',
        'description',
        'file_name',
        'size',
        'action',
        'date'
    ];
}
