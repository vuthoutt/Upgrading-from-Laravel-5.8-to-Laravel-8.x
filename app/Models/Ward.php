<?php

namespace App\Models;

use App\Models\ModelBase;

class Ward extends ModelBase
{
    protected $table = 'tbl_ward';

    protected $fillable = [
        'id',
        'description',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

}
