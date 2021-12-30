<?php

namespace App\Models;

use App\Models\ModelBase;

class ServiceArea extends ModelBase
{
    protected $table = 'tbl_service_area';

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
