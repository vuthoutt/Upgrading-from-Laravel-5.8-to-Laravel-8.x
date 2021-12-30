<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class TempLog extends ModelBase
{
    protected $fillable = [
        'assess_id',
        'equipment_id',
        'return_temp',
        'flow_temp',
        'inlet_temp',
        'stored_temp',
        'top_temp',
        'bottom_temp',
        'flow_temp_gauge_value',
        'return_temp_gauge_value',
        'ambient_area_temp',
        'incoming_main_pipe_work_temp',
        'ph',
        'hot_flow_temp',
        'cold_flow_temp',
        'pre_tmv_cold_flow_temp',
        'pre_tmv_hot_flow_temp',
        'post_tmv_temp',
        'created_by',
//        'updated_by',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $table = 'cp_log_temperatures_equipment';
}
