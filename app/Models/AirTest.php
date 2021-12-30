<?php

namespace App\Models;

use App\Models\ModelBase;

class AirTest extends ModelBase
{
    protected $table = 'tbl_airtest';

    protected $fillable = [
        'id',
        'description',
        'comment_id',
        'comment_other'
    ];

}
