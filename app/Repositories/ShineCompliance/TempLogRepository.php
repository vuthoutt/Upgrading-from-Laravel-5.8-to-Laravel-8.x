<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\TempLog;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class TempLogRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return TempLog::class;
    }

    public function getTemplogByDay($equipment_id){
        return DB::select("SELECT
                                            substring_index(group_concat(return_temp order by id desc SEPARATOR '|'), '|', 1) return_temp,
                                            substring_index(group_concat(flow_temp order by id desc SEPARATOR '|'), '|', 1) flow_temp,
                                            substring_index(group_concat(inlet_temp order by id desc SEPARATOR '|'), '|', 1) inlet_temp,
                                            substring_index(group_concat(stored_temp order by id desc SEPARATOR '|'), '|', 1) stored_temp,
                                            substring_index(group_concat(top_temp order by id desc SEPARATOR '|'), '|', 1) top_temp,
                                            substring_index(group_concat(bottom_temp order by id desc SEPARATOR '|'), '|', 1) bottom_temp,
                                            substring_index(group_concat(flow_temp_gauge_value order by id desc SEPARATOR '|'), '|', 1) flow_temp_gauge_value,
                                            substring_index(group_concat(return_temp_gauge_value order by id desc SEPARATOR '|'), '|', 1) return_temp_gauge_value,
                                            substring_index(group_concat(ambient_area_temp order by id desc SEPARATOR '|'), '|', 1) ambient_area_temp,
                                            substring_index(group_concat(incoming_main_pipe_work_temp order by id desc SEPARATOR '|'), '|', 1) incoming_main_pipe_work_temp,
                                            substring_index(group_concat(hot_flow_temp order by id desc SEPARATOR '|'), '|', 1) hot_flow_temp,
                                            substring_index(group_concat(cold_flow_temp order by id desc SEPARATOR '|'), '|', 1) cold_flow_temp,
                                            substring_index(group_concat(pre_tmv_cold_flow_temp order by id desc SEPARATOR '|'), '|', 1) pre_tmv_cold_flow_temp,
                                            substring_index(group_concat(pre_tmv_hot_flow_temp order by id desc SEPARATOR '|'), '|', 1) pre_tmv_hot_flow_temp,
                                            substring_index(group_concat(post_tmv_temp order by id desc SEPARATOR '|'), '|', 1) post_tmv_temp,
                                            UNIX_TIMESTAMP(created_at) created_at
                                      FROM cp_log_temperatures_equipment
                                      WHERE equipment_id = $equipment_id
                                      GROUP BY DATE(created_at) ;");
    }

}
