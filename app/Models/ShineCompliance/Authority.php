<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Authority extends ModelBase
{
    protected $table = 'tbl_local_authority';

    protected $fillable = [
        'id',
        'description'
    ];

}
