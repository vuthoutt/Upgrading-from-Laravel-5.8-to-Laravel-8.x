<?php

namespace App\Models;

use App\Models\ModelBase;

class WorkPropertyInfo extends ModelBase
{
    protected $table = 'tbl_work_property_info';

    protected $fillable = [
        "work_id",
        "site_occupied",
        "site_availability",
        "security_requirements",
        "parking_arrangements",
        "parking_arrangements_other",
        "electricity_availability",
        "water_availability",
        "ceiling_height",

    ];

     public function ceiling() {
        return $this->hasOne('App\Models\WorkData','id','ceiling_height');
    }

    public function parking() {
        return $this->hasOne('App\Models\WorkData','id','parking_arrangements');
    }
}
