<?php
namespace App\Repositories;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ChartType;
use Illuminate\Support\Facades\DB;

class ChartRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ChartType::class;
    }

    public function getDataMSMissingChart($zone_id = NULL){
        $condition = '';
        if($zone_id && $zone_id > 0){
            $condition = " AND z.id = " . $zone_id;
        }
        $result = DB::table('tbl_property as p')
            ->leftJoin('tbl_survey as s',function($join){
                $join->on('s.property_id', '=', 'p.id')
                    ->where(['s.survey_type'=>1,'s.decommissioned'=>SURVEY_UNDECOMMISSION]);
            })
            ->leftJoin('compliance_documents as h',function($join){
                $join->on('h.property_id', '=', 'p.id')
                    ->where(['h.is_external_ms'=>1, 'compliance_type' => 1]);
            })
            ->leftJoin('property_property_type as ppt','ppt.property_id','=','p.id')
            ->leftJoin('tbl_property_type as pt','ppt.property_type_id','=','pt.id')
            ->leftJoin('tbl_zones as z','z.id','=','p.zone_id')
            ->selectRaw('COUNT(DISTINCT p.id) AS count')
            ->whereRaw('pt.ms_level = 1
                    AND p.decommissioned = 0
                    AND s.id IS NULL
                    AND h.id IS NULL '.$condition)
            ->value('count');
        return $result;
    }

    public function getDataMSComplete($zone_id){
        $condition = '';
        if($zone_id && $zone_id > 0){
            $condition = " AND z.id = " . $zone_id;
        }
        $result = DB::table('tbl_property as p')
            ->leftJoin('tbl_survey as s',function($join){
                $join->on('s.property_id', '=', 'p.id')
                    ->where(['s.survey_type'=>1,'s.decommissioned'=>SURVEY_UNDECOMMISSION, 's.status'=>COMPLETED_SURVEY_STATUS]);
            })
            ->leftJoin(', \'compliance_type\' => 1 as h',function($join){
                $join->on('h.property_id', '=', 'p.id')
                    ->where(['h.is_external_ms'=>1, 'compliance_type' => 1]);
            })
            ->leftJoin('property_property_type as ppt','ppt.property_id','=','p.id')
            ->leftJoin('tbl_property_type as pt','ppt.property_type_id','=','pt.id')
            ->leftJoin('tbl_zones as z','z.id','=','p.zone_id')
            ->selectRaw('COUNT(DISTINCT p.id) AS count')
            ->whereRaw('pt.ms_level = 1
                    AND p.decommissioned = 0
                    AND (s.id IS NOT NULL
                    OR h.id IS NOT NULL) '.$condition)
            ->value('count');
        return $result;
    }

    public function getDataMSDecommissioned($zone_id){
        $condition = '';
        if($zone_id && $zone_id > 0){
            $condition = " AND z.id = " . $zone_id;
        }
        $result = DB::table('tbl_property as p')
            ->leftJoin('tbl_zones as z','z.id','=','p.zone_id')
            ->selectRaw('COUNT(DISTINCT p.id) AS count')
            ->whereRaw('p.decommissioned != 0 '.$condition)
            ->value('count');
        return $result;
    }

    public function getDataMSDemolished($zone_id){
        $condition = '';
        if($zone_id && $zone_id > 0){
            $condition = " AND z.id = " . $zone_id;
        }
        $result = DB::table('tbl_property as p')
            ->leftJoin('tbl_zones as z','z.id','=','p.zone_id')
            ->selectRaw('COUNT(DISTINCT p.id) AS count')
            ->whereRaw('p.decommissioned = -1 '.$condition)
            ->value('count');
        return $result;
    }

    public function getDataMSRiskType($zone_id, $risk_type_id){
        $condition = 'ppt.property_type_id = '.$risk_type_id;
        if($zone_id && $zone_id > 0){
            $condition .= " AND z.id = " . $zone_id;
        }
        $result = DB::table('tbl_property as p')
            ->leftJoin('tbl_zones as z','z.id','=','p.zone_id')
            ->join('property_property_type as ppt','ppt.property_id','=','p.id')
            ->selectRaw('COUNT(DISTINCT p.id) AS count')
            ->whereRaw($condition)
            ->where('p.decommissioned',0)
            ->value('count');
        return $result;
    }

    //for NA chart
    public function getDataNAInaccessibleVoids($zone_id){
        $condition = '';
//        dd(Auth::user()->userRole->property);
        if($zone_id && $zone_id > 0){
            $condition = " AND z.id = " . $zone_id;
        }

        $count_ceilling = DB::table('tbl_zones as z')
            ->leftJoin('tbl_property as p','p.zone_id','=','z.id')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.ceiling, ',', 1) = 1108 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('z.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_floor = DB::table('tbl_zones as z')
            ->leftJoin('tbl_property as p','p.zone_id','=','z.id')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.floor, ',', 1) = 1453 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('z.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_cavities = DB::table('tbl_zones as z')
            ->leftJoin('tbl_property as p','p.zone_id','=','z.id')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.cavities, ',', 1) = 1216 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('z.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_risers = DB::table('tbl_zones as z')
            ->leftJoin('tbl_property as p','p.zone_id','=','z.id')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.risers, ',', 1) = 1280 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('z.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_ducting = DB::table('tbl_zones as z')
            ->leftJoin('tbl_property as p','p.zone_id','=','z.id')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.ducting, ',', 1) = 1344 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('z.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_boxing = DB::table('tbl_zones as z')
            ->leftJoin('tbl_property as p','p.zone_id','=','z.id')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.boxing, ',', 1) = 1733 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('z.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        $count_pipework = DB::table('tbl_zones as z')
            ->leftJoin('tbl_property as p','p.zone_id','=','z.id')
            ->leftJoin('tbl_location as l','l.property_id','=','p.id')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.pipework, ',', 1) = 1606 ".$condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0])
            ->groupBy('z.id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');

        return $count_ceilling + $count_floor + $count_cavities + $count_risers + $count_ducting + $count_boxing + $count_pipework;
    }

    public function getDataNAInaccessibleItems($zone_id){
        $condition = [];
        if($zone_id && $zone_id > 0){
            $condition = ['p.zone_id' => $zone_id];
//            $condition = " AND z.id = " . $zone_id;
        }

        $result = DB::table('tbl_items as i')
            ->leftJoin('tbl_property as p','p.id','=','i.property_id')
            ->where($condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'i.decommissioned'=>ITEM_UNDECOMMISSION,'i.state'=>ITEM_INACCESSIBLE_STATE,'i.survey_id'=>0])
            ->groupBy('p.zone_id')
            ->selectRaw('COUNT(DISTINCT i.id) AS count')
            ->value('count');
        return $result;
    }

    public function getDataNAInaccessibleLocations($zone_id){
        $condition = [];
        if($zone_id && $zone_id > 0){
            $condition = ['p.zone_id' => $zone_id];
//            $condition = " AND z.id = " . $zone_id;
        }

        $result = DB::table('tbl_location as l')
            ->leftJoin('tbl_property as p','p.id','=','l.property_id')
            ->where($condition)
            ->where(['p.decommissioned'=>PROPERTY_UNDECOMMISSION,'l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.state'=>LOCATION_STATE_INACCESSIBLE,'l.survey_id'=>0])
            ->groupBy('p.zone_id')
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->value('count');
        return $result;
    }

    //for Reinspection Chart
    public function getPropertyRein($zone_id){
        $condition = [];
        if($zone_id && $zone_id > 0){
            $condition = ['p.zone_id' => $zone_id];
        }

        $result = DB::table('tbl_property as p')
            ->leftJoin(DB::raw('(
                SELECT MIN(date) his_add, property_id  FROM compliance_documents
                WHERE property_id > 0 AND is_external_ms = 1 GROUP BY property_id
            )
               h'),
                function($join)
                {
                    $join->on('p.id', '=', 'h.property_id');
                })
            ->leftJoin(DB::raw('(
                SELECT tbl_survey.id,  surveying_start_date, completed_date, surveying_finish_date, property_id  FROM tbl_survey
                JOIN tbl_survey_date ON tbl_survey.id = tbl_survey_date.survey_id
                WHERE survey_type = 1 AND decommissioned = 0 AND status = 5
                GROUP BY property_id
            ) s'),
                function($join)
                {
                    $join->on('p.id', '=', 's.property_id');
                })
            ->leftJoin(DB::raw('(
                SELECT tbl_survey.id,  MAX(completed_date) as completed_date, MAX(surveying_finish_date) as surveying_finish_date, property_id  FROM tbl_survey
                JOIN tbl_survey_date ON tbl_survey.id = tbl_survey_date.survey_id
                WHERE survey_type = 3 AND decommissioned = 0 AND status = 5
                GROUP BY property_id
            ) ss'),
                function($join)
                {
                    $join->on('p.id', '=', 'ss.property_id');
                })
            ->leftJoin(DB::raw('(
                SELECT COUNT(id) as count_item, property_id  FROM tbl_items
                WHERE decommissioned = 0 AND survey_id = 0 AND state != 1
                GROUP BY property_id
            ) i'),
                function($join)
                {
                    $join->on('p.id', '=', 'i.property_id');
                })
            ->join(DB::raw('(
                SELECT property_id  FROM property_property_type as ppt
                JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                WHERE pt.ms_level = 1
                GROUP BY property_id
            ) prisk'),
                function($join)
                {
                    $join->on('p.id', '=', 'prisk.property_id');
                })
            ->leftJoin('tbl_zones as z','z.id','=','p.zone_id')
            ->where($condition)
            ->where('i.count_item', '>',0)
            ->selectRaw('p.id AS property_id, p.pblock,
                DATEDIFF(FROM_UNIXTIME(GREATEST(
                            IFNULL(s.surveying_finish_date, 0),
                            IFNULL(ss.surveying_finish_date, 0),
                            IFNULL(h.his_add, 0)
                        )  + (365 * 86400), \'%Y-%m-%d\'),CONVERT(CURDATE(),DATETIME)) as last_day,
                z.id as zone_id,
                z.zone_name
            ')->get();
        return $result;
    }

    public function getPropertyReinGroup($zone_id, $type, $date_data = NULL){
        $condition = $where = '';
        if($type){
            switch ($type){
                case 1: // critical
                    $condition = 'last_day < 15';
                    break;
                case 2://urgent
                    $condition = 'last_day <= 30 AND last_day >= 15';
                    break;
                case 3://important
                    $condition = 'last_day <= 60 AND last_day > 30';
                    break;
                case 4://attention
                    $condition = 'last_day <= 120 AND last_day > 60';
                    break;
                case 5://deadline
                    $condition = 'last_day > 120';
                    break;
            }
        }

        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where = ' AND completed_date >= ' . $date_data['start_date'];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where .= ' AND completed_date <= ' . $date_data['end_date'];
        }

        $result = DB::table('tbl_property as p')
            ->leftJoin(DB::raw('(
                SELECT MIN(date) his_add, property_id  FROM compliance_documents
                WHERE property_id > 0 AND is_external_ms = 1 GROUP BY property_id
            )
               h'),
                function($join)
                {
                    $join->on('p.id', '=', 'h.property_id');
                })
            ->leftJoin(DB::raw("(
                SELECT tbl_survey.id,  completed_date, surveying_finish_date, property_id  FROM tbl_survey
                JOIN tbl_survey_date ON tbl_survey.id = tbl_survey_date.survey_id
                WHERE survey_type = 1 AND decommissioned = 0 AND status = 5 $where
                GROUP BY property_id
            ) s"),
                function($join)
                {
                    $join->on('p.id', '=', 's.property_id');
                })
            ->leftJoin(DB::raw("(
                SELECT tbl_survey.id,  MAX(completed_date) as completed_date, MAX(surveying_finish_date) as surveying_finish_date, property_id  FROM tbl_survey
                JOIN tbl_survey_date ON tbl_survey.id = tbl_survey_date.survey_id
                WHERE survey_type = 3 AND decommissioned = 0 AND status = 5 $where
                GROUP BY property_id
            ) ss"),
                function($join)
                {
                    $join->on('p.id', '=', 'ss.property_id');
                })
            ->leftJoin(DB::raw('(
                SELECT COUNT(id) as count_item, property_id  FROM tbl_items
                WHERE decommissioned = 0 AND survey_id = 0 AND state != 1
                GROUP BY property_id
            ) i'),
                function($join)
                {
                    $join->on('p.id', '=', 'i.property_id');
                })
            ->join(DB::raw('(
                SELECT property_id  FROM property_property_type as ppt
                JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                WHERE pt.id IN (1,2)
                GROUP BY property_id
            ) prisk'),
                function($join)
                {
                    $join->on('p.id', '=', 'prisk.property_id');
                })
            ->leftJoin('tbl_zones as z','z.id','=','p.zone_id')
            ->where(['p.zone_id' => $zone_id, 'p.decommissioned' => 0])
            ->where('i.count_item', '>',0)
            ->selectRaw('p.id AS property_id, p.pblock,
            DATEDIFF(FROM_UNIXTIME(GREATEST(
                            IFNULL(s.surveying_finish_date, 0),
                            IFNULL(ss.surveying_finish_date, 0),
                            IFNULL(h.his_add, 0)
                        )  + (365 * 86400), \'%Y-%m-%d\'),CONVERT(CURDATE(),DATETIME)) as last_day,
                z.id as zone_id,
                z.zone_name
            ')
            ->groupBy('p.id')
            ->havingRaw($condition)
            ->get()
            ->count();
        return $result;
    }

    // for Project Deadline chart (PD)
    public function getDataLeaderPDChart(){
        $result = DB::table('tbl_project as p')
            ->leftJoin('tbl_users as us','p.lead_key','=','us.id')
            ->leftJoin('tbl_property as pp','p.property_id','=','pp.id')
            ->selectRaw("DISTINCT lead_key,
                                                CONCAT(
                                                    us.first_name,
                                                    ' ',
                                                    us.last_name
                                                ) AS userName")
            ->whereRaw('p.client_id = 1 AND pp.decommissioned = 0 AND LENGTH(userName) > 1 AND us.id != 104') // remove Robert Hornby from the column category
            ->orderBy('lead_key','DESC')
            ->get();
        return $result;
    }

    //for Project Deadline chart
    //count total property by condition
    public function getPropertyPropjectDeadlinesPD($lead_id, $type){
        $condition = '';
        if($type){
            $toTime = time();
            $deadlineTime = 120 * 86400 + $toTime;
            $attentionTime = 60 * 86400 + $toTime;
            $importantTime = 30 * 86400 + $toTime;
            $urgentTime = 15 * 86400 + $toTime;
            switch ($type){
                case 1: // critical
                    $condition = "due_date < $urgentTime";
                    break;
                case 2://urgent
                    $condition = "due_date > $urgentTime AND due_date <= $importantTime";
                    break;
                case 3://important
                    $condition = "due_date > $importantTime AND due_date <= $attentionTime";
                    break;
                case 4://attention
                    $condition = "due_date > $attentionTime AND due_date <= $deadlineTime";
                    break;
                case 5://deadline
                    $condition = "due_date > $deadlineTime";
                    break;
            }
        }
        $result = DB::table('tbl_project as pj')
            ->leftJoin('tbl_property as p','pj.property_id','=','p.id')
            ->whereRaw("pj.client_id = 1 AND p.decommissioned = 0 AND pj.status != 5 AND pj.lead_key = $lead_id AND $condition")
            ->selectRaw('COUNT(p.id) as count_property')
            ->value('count_property');
        return $result;
    }

    //for Product Debris Type Chart
    //get items types
    public function getItemProductDerisTypes($other, $date_data = NULL){
        $where = TRUE;
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where = 'UNIX_TIMESTAMP(i.created_at) <= ' . $date_data['end_date'];
        }
        $result = DB::table('tbl_items as i')
            ->leftJoin('tbl_property as p','i.property_id','=','p.id')
            ->leftJoin('tbl_item_product_debris_type_value as d','i.id','=','d.item_id')
            ->where(['p.client_id'=>'1','i.decommissioned'=>0,'i.survey_id'=>0,'p.decommissioned'=>0])
            ->where('i.state','!=',ITEM_NOACM_STATE)
            ->whereRaw($other)
            ->whereRaw($where) // for summary chart, add date condition
            ->selectRaw('COUNT(p.id) as count_property')
            ->first()->count_property;
        return $result;
    }

    //for User login chart
    // get total login time for a department
    public function getTotalLoginDepartment($depart_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['l.logtime','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['l.logtime','<=',$date_data['end_date']];
        }
        $result = DB::table('tbl_log_login as l')
            ->leftJoin('tbl_users as us','l.user_id','=','us.id')
            ->leftJoin('tbl_clients as c','c.id','=','us.client_id')
            ->where(['l.success'=>'1','us.client_id'=>1,'us.department_id'=>$depart_id])
            ->where($where)
            ->selectRaw('COUNT(l.id) as count_login')
            ->first()->count_login;
        return $result;
    }
    // get total login time for constractors
    public function getTotalLoginConstractor($date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['l.logtime','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['l.logtime','<=',$date_data['end_date']];
        }
        $result = DB::table('tbl_log_login as l')
            ->leftJoin('tbl_users as us','l.user_id','=','us.id')
            ->leftJoin('tbl_clients as c','c.id','=','us.client_id')
            ->where(['l.success'=>'1','c.client_type'=>1])
            ->where('us.client_id','!=',1)
            ->where($where)
            ->selectRaw('COUNT(l.id) as count_login')
            ->first()->count_login;
        return $result;
    }
    // for Project Document Deadline chart (PDD)
    public function getDataLeaderPDDChart(){
        $result = DB::table('tbl_project as p')
            ->leftJoin('tbl_users as us','p.lead_key','=','us.id')
            ->leftJoin('tbl_property as pp','p.property_id','=','pp.id')
            ->selectRaw("DISTINCT lead_key,
                                                CONCAT(
                                                    us.first_name,
                                                    ' ',
                                                    us.last_name
                                                ) AS userName")
            ->whereRaw('pp.client_id = 1 AND pp.decommissioned = 0 AND LENGTH(userName) > 1 AND us.id != 104')
            ->orderBy('lead_key','DESC')
            ->get();
        return $result;
    }

    //get document project deadline
    public function getPropjectDocmentDeadlines($lead_id, $type){
        $condition = '';
        if($type){
            $toTime = time();
            $deadlineTime = 120 * 86400 + $toTime;
            $attentionTime = 60 * 86400 + $toTime;
            $importantTime = 30 * 86400 + $toTime;
            $urgentTime = 15 * 86400 + $toTime;
            switch ($type){
                case 1: // critical
                    $condition = "deadline < $urgentTime";
                    break;
                case 2://urgent
                    $condition = "deadline > $urgentTime AND deadline <= $importantTime";
                    break;
                case 3://important
                    $condition = "deadline > $importantTime AND deadline <= $attentionTime";
                    break;
                case 4://attention
                    $condition = "deadline > $attentionTime AND deadline <= $deadlineTime";
                    break;
                case 5://deadline
                    $condition = "deadline > $deadlineTime";
                    break;
            }
        }
        $result = DB::table('tbl_documents as d')
            ->leftJoin('tbl_project as pj','pj.id','=','d.project_id')
            ->leftJoin('tbl_property as p','pj.property_id','=','p.id')
            ->where(['p.decommissioned'=>0,'p.client_id'=>1,'d.status'=>1,'pj.lead_key'=>$lead_id])
            ->whereIn('d.category',[5])
            ->whereNotIn('pj.status',[1,2,5])
            ->whereRaw("$condition")
            ->selectRaw('COUNT(d.id) as count_document')
            ->value('count_document');
        return $result;
    }

    //for item decommission chart
    // get total item decommissoned by zone and quarter
    public function getItemDecommissonItem($stat_time, $end_time, $zone_id){
        $result = DB::table('tbl_items as i')
            ->join('tbl_property as p','i.property_id','=','p.id')
            ->join('tbl_zones as z','z.id','=','p.zone_id')
            ->leftJoin(DB::raw("(
                    SELECT MAX(date) as auditDate, object_id FROM tbl_audit_trail WHERE object_type = 'item' AND action_type = 'decommission' GROUP BY object_id
                )
               au"),
                function($join)
                {
                    $join->on('i.id', '=', 'au.object_id');
                })
            ->where(['i.survey_id'=>0,'i.decommissioned'=>1,'z.id'=>$zone_id])
            ->whereRaw("IFNULL(au.auditDate, UNIX_TIMESTAMP(i.created_at)) BETWEEN $stat_time AND $end_time")
            ->selectRaw('COUNT(i.id) as count_item')
            ->value('count_item');
        return $result;
    }

    //for Decommissioned Items Product chart
    //get total decommissioned items product by zone_id and types
    public function getItemDecommissonProductTypes($stat_time, $end_time, $zone_id, $other){
        $condition = [];
        if($zone_id){
            $condition = ['z.id'=>$zone_id];
        }
        $result = DB::table('tbl_items as i')
            ->leftJoin('tbl_item_product_debris_type_value as d','i.id','=','d.item_id')
            ->join('tbl_property as p','i.property_id','=','p.id')
            ->join('tbl_zones as z','z.id','=','p.zone_id')
            ->leftJoin(DB::raw("(
                    SELECT MAX(date) as auditDate, object_id FROM tbl_audit_trail WHERE object_type = 'item' AND action_type = 'decommission' GROUP BY object_id
                )
               au"),
                function($join)
                {
                    $join->on('i.id', '=', 'au.object_id');
                })
            ->where(['i.survey_id'=>0,'i.decommissioned'=>1])
            ->where($condition)
            ->whereRaw("IFNULL(au.auditDate, UNIX_TIMESTAMP(i.created_at)) BETWEEN $stat_time AND $end_time")
            ->whereRaw($other)
            ->selectRaw('COUNT(i.id) as count_item')
            ->value('count_item');
        return $result;
    }

    //for project sponsor chart
    public function getProjectSponsorData($other, $sponsor_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['p.date','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['p.date','<=',$date_data['end_date']];
        }

        $result = DB::table('tbl_project as p')
            ->leftJoin('tbl_property as s','p.property_id','=','s.id')
            ->where(['s.decommissioned' => PROPERTY_UNDECOMMISSION, 'p.sponsor_id' => $sponsor_id, 'p.project_type' => $other ])
            ->selectRaw('COUNT(p.id) as count_project')
            ->value('count_project');
        return $result;
    }

    //for project complete chart
    public function getProjectCompleteData( $pj_type_id, $contractor_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['p.completed_date','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['p.completed_date','<=',$date_data['end_date']];
        }
        $where = count($where) > 0 ? $where : TRUE;

        $result = DB::table('tbl_project as p')
            ->leftJoin('tbl_property as s','p.property_id','=','s.id')
            ->where(['s.decommissioned' => PROPERTY_UNDECOMMISSION, 'p.project_type' => $pj_type_id, 'p.status' => PROJECT_COMPLETE_STATUS])
            ->where($where)
            ->whereRaw("FIND_IN_SET('$contractor_id', REPLACE(contractors, ' ', ''))")
            ->selectRaw('COUNT(p.id) as count_project')
            ->value('count_project');
        return $result;
    }

    public function getDocumentProvideData($category_id, $contractor_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['p.completed_date','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['p.completed_date','<=',$date_data['end_date']];
        }
        $where = count($where) > 0 ? $where : TRUE;
        $result = DB::table('tbl_documents as d')
            ->leftJoin('tbl_project as p','d.project_id','=','p.id')
            ->leftJoin('tbl_property as s','p.property_id','=','s.id')
            ->where(['s.decommissioned' => PROPERTY_UNDECOMMISSION, 'd.contractor' => $contractor_id, 'd.category' => $category_id])
            ->where($where)
            ->selectRaw('COUNT(d.id) as count_document')
            ->value('count_document');
        return $result;
    }

    public function getTenderPerformanceData($other, $contractor_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['p.completed_date','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['p.completed_date','<=',$date_data['end_date']];
        }
        $where = count($where) > 0 ? $where : TRUE;
        $result = DB::table('tbl_documents as d')
            ->leftJoin('tbl_project as p','d.project_id','=','p.id')
            ->leftJoin('tbl_property as s','p.property_id','=','s.id')
            ->where(['s.decommissioned' => PROPERTY_UNDECOMMISSION, 'd.category' => TENDER_DOC_CATEGORY])
            ->whereRaw("FIND_IN_SET('$contractor_id', REPLACE(p.contractors, ' ', ''))")
            ->where($where)
            ->whereRaw($other)
            ->selectRaw('COUNT(d.id) as count_document')
            ->value('count_document');
        return $result;
    }

    public function getCompleteSurveyData($other, $contractor_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['sd.completed_date','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['sd.completed_date','<=',$date_data['end_date']];
        }
        $where = count($where) > 0 ? $where : TRUE;
        $result = DB::table('tbl_survey as s')
            ->join('tbl_survey_date as sd','sd.survey_id','=','s.id')
            ->leftJoin('tbl_property as p','p.id','=','s.property_id')
            ->where(['s.decommissioned' => PROPERTY_UNDECOMMISSION, 'p.decommissioned' => PROPERTY_UNDECOMMISSION, 's.status' => COMPLETED_SURVEY_STATUS, 's.client_id' => $contractor_id])
            ->where($where)
            ->whereRaw($other)
            ->selectRaw('COUNT(s.id) as count_survey')
            ->value('count_survey');
        return $result;
    }

    public function getDOSurveyInfoData($other, $contractor_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['sd.completed_date','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['sd.completed_date','<=',$date_data['end_date']];
        }
        $where = count($where) > 0 ? $where : TRUE;

        $result = DB::table('tbl_items as i')
            ->join('tbl_survey as s','i.survey_id','=','s.id')
            ->join('tbl_survey_date as sd','sd.survey_id','=','s.id')
            ->join('tbl_property as p','p.id','=','s.property_id')
            ->leftJoin('tbl_item_asbestos_type_value as v1','v1.item_id','=','i.id')
            ->leftJoin('tbl_item_asbestos_type as d1', function($join){
                $join->on('d1.id', '=', 'v1.dropdown_data_item_id');
                $join->where('d1.other', '=', -1);
            })
            ->leftJoin('tbl_item_extent_value as v2','v2.item_id','=','i.id')
            ->leftJoin('tbl_item_extent as d2', function($join){
                $join->on('d2.id', '=', 'v2.dropdown_data_item_id');
                $join->where('d2.other', '=', -1);
            })
            ->leftJoin('tbl_item_product_debris_type_value as v3','v3.item_id','=','i.id')
            ->leftJoin('tbl_item_product_debris_type as d3', function($join){
                $join->on('d3.id', '=', 'v3.dropdown_data_item_id');
                $join->where('d3.other', '=', -1);
            })
            ->leftJoin('tbl_item_action_recommendation_value as v4','v4.item_id','=','i.id')
            ->leftJoin('tbl_item_action_recommendation as d4', function($join){
                $join->on('d4.id', '=', 'v4.dropdown_data_item_id');
                $join->where('d4.other', '=', -1);
            })
            ->leftJoin('tbl_item_additional_information_value as v5','v5.item_id','=','i.id')
            ->leftJoin('tbl_item_additional_information as d5', function($join){
                $join->on('d5.id', '=', 'v5.dropdown_data_item_id');
                $join->where('d5.other', '=', -1);
            })
            ->leftJoin('tbl_item_sample_comment_value as v6','v6.item_id','=','i.id')
            ->leftJoin('tbl_item_sample_comment as d6', function($join){
                $join->on('d6.id', '=', 'v6.dropdown_data_item_id');
                $join->where('d6.other', '=', -1);
            })
            ->leftJoin('tbl_item_specific_location_value as v7','v7.item_id','=','i.id')
            ->leftJoin('tbl_item_specific_location as d7', function($join){
                $join->on('d7.id', '=', 'v7.dropdown_data_item_id');
                $join->where('d7.other', '=', -1);
            })
            ->leftJoin('tbl_item_accessibility_vulnerability_value as v8','v8.item_id','=','i.id')
            ->leftJoin('tbl_item_accessibility_vulnerability as d8', function($join){
                $join->on('d8.id', '=', 'v8.dropdown_data_item_id');
                $join->where('d8.other', '=', -1);
            })
            ->leftJoin('tbl_item_licensed_non_licensed_value as v9','v9.item_id','=','i.id')
            ->leftJoin('tbl_item_licensed_non_licensed as d9', function($join){
                $join->on('d9.id', '=', 'v9.dropdown_data_item_id');
                $join->where('d9.other', '=', -1);
            })
            ->leftJoin('tbl_item_unable_to_sample_value as v10','v10.item_id','=','i.id')
            ->leftJoin('tbl_item_unable_to_sample as d10', function($join){
                $join->on('d10.id', '=', 'v10.dropdown_data_item_id');
                $join->where('d10.other', '=', -1);
            })
            ->leftJoin('tbl_item_no_access_value as v11','v11.item_id','=','i.id')
            ->leftJoin('tbl_item_no_access as d11', function($join){
                $join->on('d11.id', '=', 'v11.dropdown_data_item_id');
                $join->where('d11.other', '=', -1);
            })
            ->leftJoin('tbl_item_no_acm_comments_value as v12','v12.item_id','=','i.id')
            ->leftJoin('tbl_item_no_acm_comments as d12', function($join){
                $join->on('d12.id', '=', 'v12.dropdown_data_item_id');
                $join->where('d12.other', '=', -1);
            })
            ->leftJoin('tbl_item_sample_id_value as v13','v13.item_id','=','i.id')
            ->leftJoin('tbl_item_sample_id as d13', function($join){
                $join->on('d13.id', '=', 'v13.dropdown_data_item_id');
                $join->where('d13.other', '=', -1);
            })
            ->where(['s.decommissioned' => PROPERTY_UNDECOMMISSION, 'p.decommissioned' => PROPERTY_UNDECOMMISSION, 's.status' => COMPLETED_SURVEY_STATUS, 's.client_id' => $contractor_id])
            ->where($where)
            ->whereRaw($other)
            ->whereRaw("(d1.id IS NOT NULL OR d2.id IS NOT NULL OR d3.id IS NOT NULL OR d4.id IS NOT NULL OR
            d5.id IS NOT NULL OR d6.id IS NOT NULL OR d7.id IS NOT NULL OR
            d8.id IS NOT NULL OR d9.id IS NOT NULL OR d10.id IS NOT NULL OR
            d11.id IS NOT NULL OR d12.id IS NOT NULL OR d13.id IS NOT NULL )")
            ->selectRaw('COUNT(DISTINCT i.id) as count_item')
            ->value('count_item');
        return $result;
    }

    public function getCAFSSurveyInfoData($other, $contractor_id, $date_data = NULL){
        $where = "";
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where .= " sd.completed_date >= ".$date_data['start_date'];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $condition = " sd.completed_date <=".$date_data['end_date'];
            $where .= isset($where) ? " AND ".$condition : $condition;
        }

        $result = DB::select("SELECT COUNT(DISTINCT i.id) as count_item from tbl_items as i
                                    LEFT JOIN tbl_survey s ON i.survey_id = s.id
                                    LEFT JOIN tbl_survey_date sd ON sd.survey_id = s.id
                                    LEFT JOIN tbl_property p ON p.id = s.property_id
                                    LEFT JOIN tbl_item_sample_id_value AS v ON v.item_id = i.id
                                    LEFT JOIN (
                                        SELECT DISTINCT
                                            i2.record_id AS record_id,
                                            a2.id AS area_id,
                                            spl2.id AS sample_id
                                        FROM
                                            tbl_items AS i2
                                        LEFT JOIN tbl_item_sample_id_value AS v2 ON v2.item_id = i2.id
                                        LEFT JOIN tbl_sample spl2 ON v2.dropdown_data_item_id = spl2.id
                                        LEFT JOIN tbl_survey AS s2 ON s2.id = i2.survey_id
                                        LEFT JOIN tbl_property AS st2 ON st2.id = s2.property_id
                                        LEFT JOIN tbl_area AS a2 ON a2.id = i2.area_id
                                        WHERE
                                            s2.decommissioned = 0
                                        AND st2.decommissioned = 0
                                        AND i2.decommissioned = 0
                                        AND s2.`status` = 5
                                        AND spl2.original_item_id = i2.record_id
                                    ) c ON c.sample_id = v.dropdown_data_item_id
                                    WHERE
                                    p.decommissioned = 0
                                    AND s.decommissioned = 0
                                    AND i.decommissioned = 0
                                    AND s.`status` = 5
                                    AND c.record_id != i.record_id
                                    AND c.area_id != i.area_id
                                    AND s.client_id = $contractor_id
                                    AND $other
                                    AND $where LIMIT 1");
        return isset($result[0]->count_item) ? $result[0]->count_item : 0;
    }

    public function getSPIRSurveyInfoData($other, $contractor_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['sd.completed_date','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['sd.completed_date','<=',$date_data['end_date']];
        }
        $where = count($where) > 0 ? $where : TRUE;
        $result = DB::table('tbl_items as i')
            ->join('tbl_survey as s','i.survey_id','=','s.id')
            ->join('tbl_survey_date as sd','sd.survey_id','=','s.id')
            ->join('tbl_property as p','p.id','=','s.property_id')
            ->join('tbl_item_sample_id_value as v','v.item_id','=','i.id')
            ->join('tbl_sample as spl',function($join){
                $join->on('spl.id', '=', 'v.dropdown_data_item_id');
                $join->on('spl.original_item_id', '=', 'i.record_id');
            })
            ->where(['i.decommissioned' => ITEM_UNDECOMMISSION,'s.decommissioned' => PROPERTY_UNDECOMMISSION,
                'p.decommissioned' => PROPERTY_UNDECOMMISSION, 's.status' => COMPLETED_SURVEY_STATUS, 's.client_id' => $contractor_id])
            ->where($where)
            ->whereRaw($other)
            ->selectRaw('COUNT(DISTINCT i.id) as count_item')
            ->value('count_item');
        return $result;
    }

    public function getRPSSurveyInfoData($other, $contractor_id, $date_data = NULL){
        $where = [];
        if(isset($date_data['start_date']) && isset($date_data['start_date']) > 0){
            $where[] = ['a.date','>=',$date_data['start_date']];
        }
        if(isset($date_data['end_date']) && isset($date_data['end_date']) > 0){
            $where[] = ['a.date','<=',$date_data['end_date']];
        }
        $where = count($where) > 0 ? $where : TRUE;
        $result = DB::table('tbl_audit_trail as a')
            ->join('tbl_survey as s','a.object_id','=','s.id')
            ->join('tbl_property as p','p.id','=','s.property_id')
            ->where(['s.decommissioned' => PROPERTY_UNDECOMMISSION,'p.decommissioned' => PROPERTY_UNDECOMMISSION,
                's.status' => COMPLETED_SURVEY_STATUS, 's.client_id' => $contractor_id, 'a.action_type' => 'rejected', 'a.object_type' => 'survey'])
            ->where($where)
            ->whereRaw($other)
            ->selectRaw('COUNT(DISTINCT a.id) as count_audit')
            ->value('count_audit');
        return $result;
    }
}
