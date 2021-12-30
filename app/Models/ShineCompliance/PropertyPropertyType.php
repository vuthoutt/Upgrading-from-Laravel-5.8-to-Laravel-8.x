<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;
use Illuminate\Support\Facades\DB;

class PropertyPropertyType extends ModelBase
{
    protected $table = 'property_property_type';

    protected $fillable = [
        'property_id',
        'property_type_id'
    ];

}
