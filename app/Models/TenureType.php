<?php

namespace App\Models;

use App\Models\ModelBase;

class TenureType extends ModelBase
{
    protected $table = 'tbl_tenure_type';

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
