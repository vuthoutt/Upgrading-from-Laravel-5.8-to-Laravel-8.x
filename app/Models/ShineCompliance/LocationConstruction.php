<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class LocationConstruction extends ModelBase
{
    protected $table = 'tbl_location_construction';

    protected $fillable = [
        'location_id',
        'ceiling',
        'ceiling_other',
        'walls',
        'walls_other',
        'floor',
        'floor_other',
        'doors',
        'doors_other',
        'windows',
        'windows_other'
    ];
    protected $hidden = ['location_id','id'];

    public function getStateTextAttribute()
    {
        if ($this->attributes['state'] == "noasbestos") {
            return "No ACM";
        }
        return ucfirst($this->attributes['state']);
    }

}
