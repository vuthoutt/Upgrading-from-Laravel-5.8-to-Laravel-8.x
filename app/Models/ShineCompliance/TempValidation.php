<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class TempValidation extends ModelBase
{
    protected $table = 'cp_temp_validation';

    protected $fillable = [
        'type_id',
        'tmv',
        'flow_temp_gauge_value_min',
        'flow_temp_gauge_value_max',
        'return_temp_gauge_value_min',
        'return_temp_gauge_value_max',
        'flow_temp_min',
        'flow_temp_max',
        'inlet_temp_min',
        'inlet_temp_max',
        'stored_temp_min',
        'stored_temp_max',
        'top_temp_min',
        'top_temp_max',
        'bottom_temp_min',
        'bottom_temp_max',
        'return_temp_min',
        'return_temp_max'
    ];
}
