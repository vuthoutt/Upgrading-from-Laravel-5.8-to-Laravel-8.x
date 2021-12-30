<?php

namespace App\Models;

use App\Models\ModelBase;

class LocationInfo extends ModelBase
{
    protected $table = 'tbl_location_info';

    protected $fillable = [
        'location_id',
        'description',
        'location_reference',
        'reason_inaccess_key',
        'reason_inaccess_other',
        'photo_reference',
        'comments',
    ];
    protected $hidden = ['location_id','id'];

    public function dropdownDataLocation() {
        return $this->hasOne('App\Models\DropdownDataLocation','id','reason_inaccess_key');
    }
}
