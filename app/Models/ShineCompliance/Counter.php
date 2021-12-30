<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Counter extends ModelBase
{
    protected $table = 'tbl_counter';

    protected $fillable = [
        'id',
        'count_table_name',
        'total',
    ];

}
