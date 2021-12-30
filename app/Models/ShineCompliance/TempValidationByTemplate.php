<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class TempValidationByTemplate extends ModelBase
{
    protected $table = 'cp_temp_validation_by_template';

    protected $fillable = [
        'template_id',
        'tmv',
        'flow_temp_gauge_value_min',
        'flow_temp_gauge_value_max',
        'check_equal_flow_temp_gauge',
        'return_temp_gauge_value_min',
        'return_temp_gauge_value_max',
        'check_equal_return_temp_gauge',
        'flow_temp_min',
        'flow_temp_max',
        'check_equal_flow_temp',
        'inlet_temp_min',
        'inlet_temp_max',
        'check_equal_inlet_temp',
        'stored_temp_min',
        'stored_temp_max',
        'check_equal_stored_temp',
        'top_temp_min',
        'top_temp_max',
        'check_equal_top_temp',
        'bottom_temp_min',
        'bottom_temp_max',
        'check_equal_bottom_temp',
        'return_temp_min',
        'return_temp_max',
        'check_equal_return_temp',
        'incoming_main_pipe_work_temp_min',
        'incoming_main_pipe_work_temp_max',
        'check_equal_incoming_temp',

    ];
}
