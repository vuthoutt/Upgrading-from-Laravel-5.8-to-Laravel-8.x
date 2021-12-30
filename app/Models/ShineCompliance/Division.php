<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class Division extends ModelBase
{
    protected $table = 'tbl_division';

    protected $fillable = [
        'id',
        'description',
        'order',
        'score',
        'other',
        'decommisioned',
        'parent_id',
        'removal_cost'
    ];

}
