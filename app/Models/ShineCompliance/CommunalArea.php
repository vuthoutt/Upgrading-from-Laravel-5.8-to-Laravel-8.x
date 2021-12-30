<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class CommunalArea extends ModelBase
{
    protected $table = 'tbl_communal_area';

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
