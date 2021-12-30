<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class PropertyType extends ModelBase
{
    protected $table = 'tbl_property_type';

    protected $fillable = [
        'id',
        'description',
        'code',
        'order',
        'ms_level',
        'color',
        'deleted_by',
        'created_by',
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    public function property() {
        return $this->belongsToMany('App\Models\ShineCompliance\Property', 'property_property_type','property_type_id','property_id');
    }


}
