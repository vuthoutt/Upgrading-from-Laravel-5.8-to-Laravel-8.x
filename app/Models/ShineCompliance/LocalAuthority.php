<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class LocalAuthority extends ModelBase
{
    protected $table = 'tbl_local_authority';

    protected $fillable = [
        'id',
        'description'
    ];

}
