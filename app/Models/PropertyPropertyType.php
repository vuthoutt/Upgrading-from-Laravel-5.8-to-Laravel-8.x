<?php

namespace App\Models;

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
