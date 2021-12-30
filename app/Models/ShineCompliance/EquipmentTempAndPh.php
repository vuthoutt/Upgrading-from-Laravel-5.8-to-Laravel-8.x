<?php

namespace App\Models\ShineCompliance;

use App\Models\ModelBase;

class EquipmentTempAndPh extends ModelBase
{
    protected $table = 'cp_equipment_temp_and_ph';

    protected $fillable = [
        'equipment_id',
        'return_temp',
        'flow_temp',
        'inlet_temp',
        'stored_temp',
        'top_temp',
        'bottom_temp',
        'flow_temp_gauge_value',
        'return_temp_gauge_value',
        'hot_flow_temp',
        'cold_flow_temp',
        'ambient_area_temp',
        'incoming_main_pipe_work_temp',
        'pre_tmv_cold_flow_temp',
        'pre_tmv_hot_flow_temp',
        'post_tmv_temp',
        'mixed_temp',
        'ph',
        'created_at',
        'updated_at',
    ];
}
