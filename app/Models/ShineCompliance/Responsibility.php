<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Responsibility extends ModelBase
{
    protected $table = 'tbl_responsibility';

    protected $fillable = [
        'id',
        'description',
        'order',
        'is_deleted',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

}
