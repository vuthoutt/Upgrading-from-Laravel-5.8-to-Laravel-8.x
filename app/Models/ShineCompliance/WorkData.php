<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class WorkData extends ModelBase
{
    protected $table = 'tbl_work_data';

    protected $fillable = [
        'description',
        'other',
        'parent_id',
        'type',
    ];

    public function parents() {
        return $this->hasOne('App\Models\ShineCompliance\WorkData', 'id', 'parent_id');
    }
}
