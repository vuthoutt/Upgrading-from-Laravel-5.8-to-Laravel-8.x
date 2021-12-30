<?php

namespace App\Models;

use App\Models\ModelBase;

class DropdownShort extends ModelBase
{
    protected $table = 'tbl_dropdown_short';

    protected $fillable = [
        'id',
        'short_text'
    ];

}
