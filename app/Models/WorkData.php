<?php

namespace App\Models;

use App\Models\ModelBase;

class WorkData extends ModelBase
{
    protected $table = 'tbl_work_data';

    protected $fillable = [
        'description',
        'other',
        'parent_id',
        'type',
        'compliance_type',
    ];

    public function parents() {
        return $this->hasOne('App\Models\WorkData', 'id', 'parent_id');
    }
}
