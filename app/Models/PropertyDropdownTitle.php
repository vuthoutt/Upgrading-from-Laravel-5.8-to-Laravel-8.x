<?php

namespace App\Models;

use App\Models\ModelBase;

class PropertyDropdownTitle extends ModelBase
{
    protected $table = 'tbl_property_dropdown_title';

    protected $fillable = [
        'name',
        'key_name',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function propertyDropdownData() {
        return $this->hasMany('App\Models\PropertyDropdown','dropdown_id','id');
    }


}
