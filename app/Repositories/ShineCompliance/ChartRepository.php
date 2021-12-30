<?php
namespace App\Repositories\ShineCompliance;
use App\Models\Project;
use App\Models\ProjectType;
use App\Models\Survey;
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

    public function getDataAccessibilityFireExist(){
        $result = DB::table('cp_fire_exits as fe')
            ->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw("COUNT(accessibility) as count, accessibility")->groupBy('accessibility')->get();
        $inaccessible_data = $result->where('accessibility', 0)->first()->count ?? 0;
        $accessible_data = $result->where('accessibility', 1)->first()->count ?? 0;
        return ['inaccessible' => $inaccessible_data, 'accessible' => $accessible_data];
    }

    public function getDataAccessibilityAssemblyPoint(){
        $result = DB::table('cp_assembly_points as ap')
            ->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw("COUNT(accessibility) as count, accessibility")->groupBy('accessibility')->get();
        $inaccessible_data = $result->where('accessibility', 0)->first()->count ?? 0;
        $accessible_data = $result->where('accessibility', 1)->first()->count ?? 0;
        return ['inaccessible' => $inaccessible_data, 'accessible' => $accessible_data];
    }

    public function getDataAccessibilityVehicleParking(){
        $result = DB::table('cp_vehicle_parking as vp')
            ->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw("COUNT(accessibility) as count, accessibility")->groupBy('accessibility')->get();
        $inaccessible_data = $result->where('accessibility', 0)->first()->count ?? 0;
        $accessible_data = $result->where('accessibility', 1)->first()->count ?? 0;
        return ['inaccessible' => $inaccessible_data, 'accessible' => $accessible_data];
    }

    public function getDataAccessibilityAreaFloor(){
        $result = DB::table('tbl_area as ar')
            ->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER,
                'survey_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw("COUNT(id) as count, state")->groupBy('state')->get();
        $inaccessible_data = $result->where('state', AREA_INACCESSIBLE_STATE)->first()->count ?? 0;
        $accessible_data_new = $result->where('state', AREA_ACCESSIBLE_STATE)->first()->count ?? 0;
        $accessible_data_old = $result->where('state', NULL)->first()->count ?? 0;
        return ['inaccessible' => $inaccessible_data, 'accessible' => $accessible_data_new + $accessible_data_old];
    }


    public function getDataAccessibilityRoomLocation(){
        $result = DB::table('tbl_location as loc')
            ->where(['decommissioned' => LOCATION_UNDECOMMISSION, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER,
                'survey_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw("COUNT(id) as count, state")->groupBy('state')->get();

        $inaccessible_data = $result->where('state', LOCATION_STATE_INACCESSIBLE)->first()->count ?? 0;
        $accessible_data_new = $result->where('state', LOCATION_STATE_ACCESSIBLE)->first()->count ?? 0;
        $accessible_data_old = $result->where('state', NULL)->first()->count ?? 0;
        return ['inaccessible' => $inaccessible_data, 'accessible' => $accessible_data_new + $accessible_data_old];
    }

    public function getDataAccessibilityVoids(){
        $condition = "";
        $const_ceiling_inacc = LOCATION_VOID_INACC_CELLING;
        $const_ceiling_acc = LOCATION_VOID_ACC_CELLING;
        $const_floor_inacc = LOCATION_VOID_INACC_FLOOR;
        $const_floor_acc = LOCATION_VOID_ACC_FLOOR;
        $const_cavities_inacc = LOCATION_VOID_INACC_CAVITIES;
        $const_cavities_acc = LOCATION_VOID_ACC_CAVITIES;
        $const_risers_inacc = LOCATION_VOID_INACC_RISERS;
        $const_risers_acc = LOCATION_VOID_ACC_RISERS;
        $const_ducting_inacc = LOCATION_VOID_INACC_DUCTING;
        $const_ducting_acc = LOCATION_VOID_ACC_DUCTING;
        $const_boxing_inacc = LOCATION_VOID_INACC_BOXING;
        $const_boxing_acc = LOCATION_VOID_ACC_BOXING;
        $const_pipework_inacc = LOCATION_VOID_INACC_PIPEWORK;
        $const_pipework_acc = LOCATION_VOID_ACC_PIPEWORK;
        //1062
        $count_ceilling_inacc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.ceiling, ',', 1) = $const_ceiling_inacc")
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_ceilling_acc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.ceiling, ',', 1) = $const_ceiling_acc")
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_floor_inacc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.floor, ',', 1) = $const_floor_inacc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_floor_acc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.floor, ',', 1) = $const_floor_acc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_cavities_inacc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.cavities, ',', 1) = $const_cavities_inacc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_cavities_acc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.cavities, ',', 1) = $const_cavities_acc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_risers_inacc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.risers, ',', 1) = $const_risers_inacc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_risers_acc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.risers, ',', 1) = $const_risers_acc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_ducting_inacc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.ducting, ',', 1) = $const_ducting_inacc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_ducting_acc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.ducting, ',', 1) = $const_ducting_acc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_boxing_inacc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.boxing, ',', 1) = $const_boxing_inacc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_boxing_acc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.boxing, ',', 1) = $const_boxing_acc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_pipework_inacc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.pipework, ',', 1) = $const_pipework_inacc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $count_pipework_acc = DB::table('tbl_location as l')
            ->leftJoin('tbl_location_void as lv','lv.location_id','=','l.id')
            ->whereRaw("SUBSTRING_INDEX( lv.pipework, ',', 1) = $const_pipework_acc ".$condition)
            ->where(['l.decommissioned'=>LOCATION_UNDECOMMISSION,'l.survey_id'=>0, 'l.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw('COUNT(DISTINCT l.id) AS count')
            ->count();

        $inaccessible_data = $count_ceilling_inacc + $count_floor_inacc + $count_cavities_inacc + $count_risers_inacc + $count_ducting_inacc + $count_boxing_inacc + $count_pipework_inacc;
        $accessible_data = $count_ceilling_acc + $count_floor_acc + $count_cavities_acc + $count_risers_acc + $count_ducting_acc + $count_boxing_acc + $count_pipework_acc;
        return ['inaccessible' => $inaccessible_data, 'accessible' => $accessible_data];
    }

    public function getDataAccessibilityAcm(){
        $result = DB::table('tbl_items as i')
            ->leftJoin('tbl_location as l','i.location_id','=','l.id')
            ->leftJoin('tbl_area as a','l.area_id','=','a.id')
            ->leftJoin('tbl_property as p','a.property_id','=','p.id')
            ->where(['i.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'i.survey_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->where( 'i.state',"!=",ITEM_NOACM_STATE)
            ->where(['l.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                    'a.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                    'p.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED])
            ->selectRaw("COUNT(i.id) as count, i.state")->groupBy('i.state')->get();
        $inaccessible_data = $result->where('state', ITEM_INACCESSIBLE_STATE)->first()->count ?? 0;
        $accessible_data = $result->where('state', ITEM_ACCESSIBLE_STATE)->first()->count ?? 0;
        return ['inaccessible' => $inaccessible_data, 'accessible' => $accessible_data];
    }

    public function getDataAccessibilityEquipment(){
        $result = DB::table('cp_equipments as eq')
            ->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->selectRaw("COUNT(state) as count, state")->groupBy('state')->get();
        $inaccessible_data = $result->where('state', 0)->first()->count ?? 0;
        $accessible_data = $result->where('state', 1)->first()->count ?? 0;
        return ['inaccessible' => $inaccessible_data, 'accessible' => $accessible_data];
    }

    public function getDataAsbestosRisk(){
        $result = DB::table('tbl_items as i')
            ->leftJoin('tbl_location as l','i.location_id','=','l.id')
            ->leftJoin('tbl_area as a','l.area_id','=','a.id')
            ->leftJoin('tbl_property as p','a.property_id','=','p.id')
            ->where(['i.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'i.survey_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->where( 'i.state',"!=",ITEM_NOACM_STATE)
            ->where(['l.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'a.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'p.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED])
            ->selectRaw("
                SUM(CASE WHEN total_risk >= 20 && total_risk < 25  THEN 1 ELSE 0 END) high_risk,
                SUM(CASE WHEN total_risk >= 14 && total_risk < 20 THEN 1 ELSE 0 END) medium_risk,
                SUM(CASE WHEN total_risk >= 10 && total_risk < 14  THEN 1 ELSE 0 END) low_risk,
                SUM(CASE WHEN total_risk < 10 && total_risk >= 0 THEN 1 ELSE 0 END) very_low_risk,
                COUNT(DISTINCT i.id) as total_action_recommendation
            ")
            ->groupBy("i.decommissioned")->get();
        return ['very_low_risk' => $result[0]->very_low_risk ?? 0,
                'low_risk' => $result[0]->low_risk ?? 0,
                'medium_risk' => $result[0]->medium_risk ?? 0,
                'high_risk' => $result[0]->high_risk ?? 0];
    }

    public function getDataFireWaterRisk($assessType){
        $result = DB::table('cp_hazards as hz')
            ->leftJoin('tbl_property as p','hz.property_id','=','p.id')
            ->where(['hz.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'hz.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER, 'p.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'hz.assess_type' => $assessType,
                'hz.is_temp' => COMPLIANCE_NORMAL_HAZARD,
                'hz.is_deleted' => COMPLIANCE_UNDELETED_HAZARD
                ])
            ->selectRaw("
                SUM(CASE WHEN total_risk >= 21 && total_risk <= 25  THEN 1 ELSE 0 END) very_high_risk,
                SUM(CASE WHEN total_risk >= 16 && total_risk <= 20 THEN 1 ELSE 0 END) high_risk,
                SUM(CASE WHEN total_risk >= 10 && total_risk <= 15  THEN 1 ELSE 0 END) medium_risk,
                SUM(CASE WHEN total_risk >= 4 && total_risk <= 9 THEN 1 ELSE 0 END) low_risk,
                SUM(CASE WHEN total_risk >= 0 && total_risk <= 3 THEN 1 ELSE 0 END) very_low_risk")
            ->groupBy("hz.decommissioned")->get();
        return ['very_low_risk' => $result[0]->very_low_risk ?? 0,
            'low_risk' => $result[0]->low_risk ?? 0,
            'medium_risk' => $result[0]->medium_risk ?? 0,
            'high_risk' => $result[0]->high_risk ?? 0,
            'very_high_risk' => $result[0]->very_high_risk ?? 0];
    }

    public function getDataAsbestosActRecommendation($from_duty_manage = false){
        $condition = "TRUE";
        if($from_duty_manage){
            $condition .= " AND prisk.property_id > 0";
        }
        $action_recommendation_remove = implode(",",ACTION_RECOMMENDATION_REMOVE);
        $action_recommendation_restrict_access_remove = implode(",",ACTION_RECOMMENDATION_RESTRICT_ACCESS_REMOVE);
        $action_recommendation_manage = implode(",",ACTION_RECOMMENDATION_MANAGE);
        $action_recommendation_remedial = implode(",",ACTION_RECOMMENDATION_REMEDIAL);
        $action_recommendation_further_investigation_required = implode(",",ACTION_RECOMMENDATION_FURTHER_INVESTIGATION_REQUIRED);
        $action_recommendation_other = implode(",",ACTION_RECOMMENDATION_OTHER);
        $result = DB::table('tbl_items as i')
            ->leftJoin('tbl_location as l','i.location_id','=','l.id')
            ->leftJoin('tbl_area as a','l.area_id','=','a.id')
            ->leftJoin('tbl_property as p','a.property_id','=','p.id')
            ->leftJoin(DB::raw('(
                SELECT property_id  FROM property_property_type as ppt
                JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                WHERE pt.ms_level = 1
                GROUP BY property_id
            ) prisk'),
                function($join)
                {
                    $join->on('p.id', '=', 'prisk.property_id');
                })
            ->join('tbl_item_action_recommendation_value as val','val.item_id','=','i.id')
            ->leftJoin('tbl_item_action_recommendation as val2','val2.id','=','val.dropdown_data_item_id')
            ->where(['i.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'i.survey_id' => COMPLIANCE_ASSESSMENT_REGISTER])
            ->where( 'i.state',"!=",ITEM_NOACM_STATE)
            ->whereRaw($condition)
            ->where(['l.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'a.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'p.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED])
            ->selectRaw("
                SUM(CASE WHEN dropdown_data_item_id IN ($action_recommendation_remove) || val2.parent_id IN ($action_recommendation_remove)  THEN 1 ELSE 0 END) remove,
                SUM(CASE WHEN dropdown_data_item_id IN ($action_recommendation_restrict_access_remove) || val2.parent_id IN ($action_recommendation_restrict_access_remove)  THEN 1 ELSE 0 END) restrict_access_remove,
                SUM(CASE WHEN dropdown_data_item_id IN ($action_recommendation_manage) || val2.parent_id IN ($action_recommendation_manage)  THEN 1 ELSE 0 END) manage,
                SUM(CASE WHEN dropdown_data_item_id IN ($action_recommendation_remedial) || val2.parent_id IN ($action_recommendation_remedial) THEN 1 ELSE 0 END) remedial,
                SUM(CASE WHEN dropdown_data_item_id IN ($action_recommendation_further_investigation_required) || val2.parent_id IN ($action_recommendation_further_investigation_required) THEN 1 ELSE 0 END) further_investigation_required,
                SUM(CASE WHEN dropdown_data_item_id IN ($action_recommendation_other) || val2.parent_id IN ($action_recommendation_other) THEN 1 ELSE 0 END) other,
                COUNT(DISTINCT i.id) as total_action_recommendation
            ")
            ->groupBy("i.decommissioned")->get();
        return [
            'remove' => $result[0]->remove ?? 0,
            'restrict_access_remove' => $result[0]->restrict_access_remove ?? 0,
            'manage' => $result[0]->manage ?? 0,
            'remedial' => $result[0]->remedial ?? 0,
            'further_investigation_required' => $result[0]->further_investigation_required ?? 0,
            'other' => $result[0]->other ?? 0,
            'total_action_recommendation' => $result[0]->total_action_recommendation ?? 0];
    }

    public function getDataFireWaterActRecommendation($hazard_type = 0, $from_duty_manage = false){
        $condition = "TRUE";
        if($hazard_type){
            $condition .= " AND hz.assess_type = $hazard_type";
        }
        if($from_duty_manage){
            $condition .= " AND prisk.property_id > 0";
        }
        $action_recommendation_remove = ACTION_RECOMMENDATION_HZ_REMOVE;
        $action_recommendation_restrict_access_remove = ACTION_RECOMMENDATION_HZ_RESTRICT_ACCESS_REMOVE;
        $action_recommendation_manage = ACTION_RECOMMENDATION_HZ_MANAGE;
        $action_recommendation_remedial = ACTION_RECOMMENDATION_HZ_REMEDIAL;
        $action_recommendation_further_investigation_required = ACTION_RECOMMENDATION_HZ_FURTHER_INVESTIGATION_REQUIRED;
        $action_recommendation_other = ACTION_RECOMMENDATION_HZ_OTHER;
        $result = DB::table('cp_hazards as hz')
            ->leftJoin('tbl_property as p','hz.property_id','=','p.id')
            ->leftJoin(DB::raw('(
                SELECT property_id  FROM property_property_type as ppt
                JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                WHERE pt.ms_level = 1
                GROUP BY property_id
            ) prisk'),
                function($join)
                {
                    $join->on('p.id', '=', 'prisk.property_id');
                })
            ->leftJoin('cp_hazard_action_recommendation_verb as verb','verb.id','=','hz.act_recommendation_verb')
            ->where(['hz.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'hz.assess_id' => COMPLIANCE_ASSESSMENT_REGISTER, 'p.decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
                'hz.is_temp' => COMPLIANCE_NORMAL_HAZARD,
                'hz.is_deleted' => COMPLIANCE_UNDELETED_HAZARD
            ])
            ->where('hz.act_recommendation_verb', '>', 0)
            ->whereRaw($condition)
            ->selectRaw("
                SUM(CASE WHEN verb.graphical_chart_type = $action_recommendation_remove  THEN 1 ELSE 0 END) remove,
                SUM(CASE WHEN verb.graphical_chart_type = $action_recommendation_restrict_access_remove  THEN 1 ELSE 0 END) restrict_access_remove,
                SUM(CASE WHEN verb.graphical_chart_type = $action_recommendation_manage  THEN 1 ELSE 0 END) manage,
                SUM(CASE WHEN verb.graphical_chart_type = $action_recommendation_remedial  THEN 1 ELSE 0 END) remedial,
                SUM(CASE WHEN verb.graphical_chart_type = $action_recommendation_further_investigation_required  THEN 1 ELSE 0 END) further_investigation_required,
                SUM(CASE WHEN verb.graphical_chart_type = $action_recommendation_other  THEN 1 ELSE 0 END) other,
                COUNT(DISTINCT hz.id) total_action_recommendation")
            ->groupBy("hz.decommissioned")
            ->get();
//        dd($result->toSql());
        return [
            'remove' => $result[0]->remove ?? 0,
            'restrict_access_remove' => $result[0]->restrict_access_remove ?? 0,
            'manage' => $result[0]->manage ?? 0,
            'remedial' => $result[0]->remedial ?? 0,
            'further_investigation_required' => $result[0]->further_investigation_required ?? 0,
            'other' => $result[0]->other ?? 0,
            'total_action_recommendation' => $result[0]->total_action_recommendation ?? 0];
    }

    public function getDataFireWaterPrePlanned(){
        $document_reinspected = DOCUMENT_REINSPECTED;
        $result = DB::table('compliance_programmes as pro')
            ->join(DB::raw("(
                SELECT programme_id, substring_index(group_concat(id Order By date DESC separator ','),',',1) ,
                substring_index(group_concat(date Order By date DESC separator ','),',',1) date
                 FROM compliance_documents
                WHERE is_reinspected = $document_reinspected AND programme_id > 0 GROUP BY programme_id
            ) doc"),
                function($join)
                {
                    $join->on('doc.programme_id', '=', 'pro.id');
                })
            ->selectRaw("
                DATEDIFF(FROM_UNIXTIME(doc.date + (pro.inspection_period * 86400), '%Y-%m-%d'),CONVERT(CURDATE(),DATETIME)) as last_day
            ")
            ->whereRaw("pro.inspection_period > 0 AND doc.date > 0 AND pro.decommissioned = 0")
        ->get();
        $deadline = $result->where('last_day', '>', 120)->count() ?? 0;
        $attention = $result->where('last_day', '<=', 120)->where('last_day', '>', 60)->count() ?? 0;
        $important = $result->where('last_day', '<=', 60)->where('last_day', '>', 30)->count() ?? 0;
        $urgent = $result->where('last_day', '<=', 30)->where('last_day', '>=', 15)->count() ?? 0;
        $critical = $result->where('last_day', '<', 15)->count() ?? 0;
        return [
            'critical' => $critical,
            'urgent' => $urgent,
            'important' => $important,
            'attention' => $attention,
            'deadline' => $deadline
        ];
    }

    public function getDataAsbestosReinspection(){
        $result = DB::table('tbl_property as p')
            ->leftJoin(DB::raw('(
                SELECT MIN(date) his_add, property_id  FROM compliance_documents
                WHERE property_id > 0 AND is_external_ms = 1 AND compliance_type = 1 GROUP BY property_id
            )
               h'),
                function($join)
                {
                    $join->on('p.id', '=', 'h.property_id');
                })
            ->leftJoin(DB::raw('(
                SELECT MAX(date) his_add, property_id  FROM compliance_documents
                WHERE property_id > 0 AND is_external_ms = 2 AND compliance_type = 1 GROUP BY property_id
            )
               h2'),
                function($join)
                {
                    $join->on('p.id', '=', 'h2.property_id');
                })
            ->leftJoin(DB::raw('(
                SELECT COUNT(id) as switch_acm, property_id  FROM compliance_documents
                WHERE property_id > 0 AND (is_external_ms = 2 || is_external_ms = 1) AND is_identified_acm = 1 AND compliance_type = 1 GROUP BY property_id
            )
               h3'),
                function($join)
                {
                    $join->on('p.id', '=', 'h3.property_id');
                })
            ->leftJoin(DB::raw('(
                SELECT tbl_survey.id, completed_date, surveying_finish_date, surveying_start_date, property_id  FROM tbl_survey
                JOIN tbl_survey_date ON tbl_survey.id = tbl_survey_date.survey_id
                WHERE survey_type = 1 AND decommissioned = 0 AND status = 5
                GROUP BY property_id
            ) s'),
                function($join)
                {
                    $join->on('p.id', '=', 's.property_id');
                })
            ->leftJoin(DB::raw('(
                SELECT tbl_survey.id,  MAX(completed_date) as completed_date, MAX(surveying_finish_date) as surveying_finish_date, MAX(surveying_start_date) as surveying_start_date, property_id  FROM tbl_survey
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
            ->whereRaw("GREATEST( IFNULL( s.surveying_start_date, 0), IFNULL(h.his_add, 0)) > 0")
            ->whereRaw("(i.count_item > 0 OR IFNULL(h3.switch_acm, 0) > 0)")
            ->selectRaw('p.id AS property_id, p.pblock,
                 DATEDIFF(FROM_UNIXTIME(
                    CASE
                        WHEN GREATEST(IFNULL(s.surveying_start_date, 0), IFNULL(h.his_add, 0)) > GREATEST(IFNULL(ss.surveying_start_date, 0), IFNULL(h2.his_add, 0))
                            THEN
                                CASE
                                    WHEN LEAST(IFNULL(s.surveying_start_date, 0), IFNULL(h.his_add, 0)) > 0
                                    THEN LEAST(IFNULL(s.surveying_start_date, 0), IFNULL(h.his_add, 0))
                                    ELSE GREATEST(IFNULL(s.surveying_start_date, 0), IFNULL(h.his_add, 0))
                                END
                        ELSE
                            GREATEST(IFNULL(ss.surveying_start_date, 0), IFNULL(h2.his_add, 0))
                    END
                  + (365 * 86400), \'%Y-%m-%d\'),CONVERT(CURDATE(),DATETIME)) as last_day
            ')->get();
        $deadline = $result->where('last_day', '>', 120)->count() ?? 0;
        $attention = $result->where('last_day', '<=', 120)->where('last_day', '>', 60)->count() ?? 0;
        $important = $result->where('last_day', '<=', 60)->where('last_day', '>', 30)->count() ?? 0;
        $urgent = $result->where('last_day', '<=', 30)->where('last_day', '>=', 15)->count() ?? 0;
        $critical = $result->where('last_day', '<', 15)->count() ?? 0;
        return [
                'critical' => $critical,
                'urgent' => $urgent,
                'important' => $important,
                'attention' => $attention,
                'deadline' => $deadline
            ];
    }

    public function getDataFireWaterReinspection($type, $assess_type){
        $result = DB::table('tbl_property as p')
            ->leftJoin(DB::raw("(
                SELECT MAX(date) his_add, property_id  FROM compliance_documents
                WHERE property_id > 0 AND compliance_type = $type GROUP BY property_id
            )
               h"),
                function($join)
                {
                    $join->on('p.id', '=', 'h.property_id');
                })
            ->leftJoin(DB::raw("(
                SELECT MAX(assess_finish_date) assess_finish_date, property_id  FROM cp_assessments
                WHERE classification = $assess_type AND decommissioned = 0 AND status = 5
                GROUP BY property_id
            ) ass"),
                function($join)
                {
                    $join->on('p.id', '=', 'ass.property_id');
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
            ->whereRaw("GREATEST( IFNULL( ass.assess_finish_date, 0), IFNULL(h.his_add, 0)) > 0")
            ->selectRaw('p.id AS property_id, p.pblock,
                 DATEDIFF(FROM_UNIXTIME(
                    GREATEST( IFNULL( ass.assess_finish_date, 0), IFNULL(h.his_add, 0))
                  + (730 * 86400), \'%Y-%m-%d\'),CONVERT(CURDATE(),DATETIME)) as last_day
            ')->get();
        $deadline = $result->where('last_day', '>', 120)->count() ?? 0;
        $attention = $result->where('last_day', '<=', 120)->where('last_day', '>', 60)->count() ?? 0;
        $important = $result->where('last_day', '<=', 60)->where('last_day', '>', 30)->count() ?? 0;
        $urgent = $result->where('last_day', '<=', 30)->where('last_day', '>=', 15)->count() ?? 0;
        $critical = $result->where('last_day', '<', 15)->count() ?? 0;
        return [
            'critical' => $critical,
            'urgent' => $urgent,
            'important' => $important,
            'attention' => $attention,
            'deadline' => $deadline
        ];
    }

    public function getDataWaterReinspection(){

    }

    public function getDataDutyManageCompliant(){
        $sql = " SELECT
                 COUNT(DISTINCT p.id) total_duty_manage,
                 SUM(CASE WHEN s.count_survey > 0 OR doc.count_asbestos > 0 THEN 1 ELSE 0 END) count_duty_manage_asbestos,
                 SUM(CASE WHEN ass.count_fire > 0 OR doc.count_fire > 0 THEN 1 ELSE 0 END) count_duty_manage_fire,
                 SUM(CASE WHEN ass.count_gas > 0 OR doc.count_gas > 0 THEN 1 ELSE 0 END) count_duty_manage_gas,
                 SUM(CASE WHEN ass.count_water > 0 OR doc.count_water > 0 THEN 1 ELSE 0 END) count_duty_manage_water
                 FROM tbl_property as p
                 JOIN (SELECT property_id  FROM property_property_type as ppt
                        JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                        WHERE pt.ms_level = 1
                        GROUP BY property_id) prisk ON prisk.property_id = p.id
                 LEFT JOIN (
                        SELECT count(*) count_survey, property_id FROM tbl_survey
                        WHERE decommissioned = 0 AND status = 5 AND survey_type IN(1,2)
                        GROUP BY property_id) s ON s.property_id = p.id
                LEFT JOIN (
                        SELECT
                            SUM(CASE WHEN `classification` = 2 THEN 1 ELSE 0 END) count_fire,
                            SUM(CASE WHEN `classification` = 3 THEN 1 ELSE 0 END) count_gas,
                            SUM(CASE WHEN `classification` = 4 THEN 1 ELSE 0 END) count_water,
                            property_id FROM cp_assessments
                        WHERE decommissioned = 0 AND status = 5
                        GROUP BY property_id) ass ON ass.property_id = p.id
                 LEFT JOIN (
                        SELECT
                            SUM(CASE WHEN cd.`type` = 2 OR cd.`type` = 1 THEN 1 ELSE 0 END) count_asbestos,
                            SUM(CASE WHEN cdt.`type` = 2 THEN 1 ELSE 0 END) count_fire,
                            SUM(CASE WHEN cdt.`type` = 3 THEN 1 ELSE 0 END) count_gas,
                            SUM(CASE WHEN cdt.`type` = 5 THEN 1 ELSE 0 END) count_water,
                            property_id
                        FROM compliance_documents cd
                        JOIN compliance_document_types cdt ON cdt.id = cd.`type`
                        WHERE cd.property_id > 0 GROUP BY cd.property_id
                    ) doc ON doc.property_id = p.id
                    WHERE p.decommissioned = 0
                    GROUP BY p.decommissioned
        ";
        $result = DB::select(DB::raw($sql));
        return [
            'total_duty_manage' => $result[0]->total_duty_manage ?? 0,
            'count_duty_manage_asbestos' => $result[0]->count_duty_manage_asbestos ?? 0,
            'count_duty_manage_fire' => $result[0]->count_duty_manage_fire ?? 0,
            'count_duty_manage_gas' => $result[0]->count_duty_manage_gas ?? 0,
            'count_duty_manage_water' => $result[0]->count_duty_manage_water ?? 0
        ];
    }

    public function getDataRoomCompliant(){
        $sql = "
            SELECT
                COUNT(l.id) total_rooms,
                SUM(CASE WHEN l.`state` = 1 THEN 1 ELSE 0 END) total_accessible_rooms
            FROM tbl_items i
            JOIN tbl_property p ON p.id = i.property_id
            JOIN tbl_location as l ON l.id = i.location_id AND l.decommissioned = 0
            JOIN tbl_area as a ON a.id = i.area_id AND a.decommissioned = 0
            JOIN (SELECT property_id  FROM property_property_type as ppt
                        JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                        WHERE pt.ms_level = 1
                        GROUP BY property_id) prisk ON prisk.property_id = p.id
            JOIN tbl_item_action_recommendation_value as val ON val.item_id = i.id
            WHERE i.decommissioned = 0 AND p.decommissioned = 0 AND i.survey_id = 0 AND i.`state` != 1
            GROUP BY i.decommissioned
        ";
        $result = DB::select(DB::raw($sql));
        return [
            'total_accessible_rooms' => $result[0]->total_accessible_rooms ?? 0,
            'total_rooms' => $result[0]->total_rooms ?? 0
        ];
    }

    public function getDataReinspectionAssessment($type){
        $sql = "
            SELECT
                COUNT(DISTINCT ass.property_id) as count_assessment
            FROM cp_assessments ass
            WHERE ass.`classification` = $type AND decommissioned = 0 AND status = 5
            AND DATEDIFF(FROM_UNIXTIME(IFNULL( ass.assess_finish_date, 0) + (730 * 86400), '%Y-%m-%d'),CONVERT(CURDATE(),DATETIME)) > 0
          GROUP BY decommissioned
          ";

        $sql_total_reinspection_property = "
            SELECT COUNT(DISTINCT p.id) count_property FROM tbl_property p
            JOIN (SELECT property_id  FROM property_property_type as ppt
                        JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                        WHERE pt.ms_level = 1
                        GROUP BY property_id) prisk ON prisk.property_id = p.id
        ";

        $result = DB::select(DB::raw($sql));
        $result_total_reinspection_property = DB::select(DB::raw($sql_total_reinspection_property));
        return [
            'count_assessment' => $result[0]->count_assessment ?? 0,
            'count_property' => $result_total_reinspection_property[0]->count_property ?? 0
        ];
    }

    public function getDataReinspectionAsbestos(){
        $sql = "
            SELECT
                COUNT(DISTINCT sur.property_id) as count_survey
            FROM tbl_survey sur
            JOIN tbl_survey_date sd ON sd.survey_id = sur.id
            WHERE sur.`survey_type` = 3 AND decommissioned = 0 AND status = 5 AND survey_type = 3
            AND DATEDIFF(FROM_UNIXTIME(IFNULL( sd.surveying_start_date, 0) + (365 * 86400), '%Y-%m-%d'),CONVERT(CURDATE(),DATETIME)) > 0
            GROUP BY sur.decommissioned
          ";

        $sql_total_reinspection_property = "
            SELECT COUNT(DISTINCT p.id) count_property FROM tbl_property p
            JOIN (SELECT property_id  FROM property_property_type as ppt
                        JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                        WHERE pt.ms_level = 1
                        GROUP BY property_id) prisk ON prisk.property_id = p.id
            LEFT JOIN ( SELECT COUNT(id) as switch_acm, property_id FROM compliance_documents
                        WHERE property_id > 0 AND (is_external_ms = 2 || is_external_ms = 1) AND is_identified_acm = 1 AND compliance_type = 1 GROUP BY property_id
                        ) h3 ON h3.property_id = p.id
            LEFT JOIN ( SELECT COUNT(id) as count_item, property_id  FROM tbl_items
                        WHERE decommissioned = 0 AND survey_id = 0 AND state != 1
                        GROUP BY property_id
                        ) i ON i.property_id = p.id
            WHERE (i.count_item > 0 OR IFNULL(h3.switch_acm, 0) > 0)
        ";
        $result = DB::select(DB::raw($sql));
        $result_total_reinspection_property = DB::select(DB::raw($sql_total_reinspection_property));
        return [
            'count_survey' => $result[0]->count_survey ?? 0,
            'count_property' => $result_total_reinspection_property[0]->count_property ?? 0
        ];
    }

    public function getDataPrePlanned(){
        $document_reinspected = DOCUMENT_REINSPECTED;
        $result = DB::table('compliance_programmes as pro')
            ->join(DB::raw("(
                SELECT programme_id, substring_index(group_concat(id Order By date DESC separator ','),',',1) ,
                substring_index(group_concat(date Order By date DESC separator ','),',',1) date
                 FROM compliance_documents
                WHERE is_reinspected = $document_reinspected AND programme_id > 0 GROUP BY programme_id
            ) doc"),
                function($join)
                {
                    $join->on('doc.programme_id', '=', 'pro.id');
                })
            ->join("tbl_property as p","p.id","=","pro.property_id")
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
//            ->join('compliance_documents as cd','cd.id', '=', 'doc.id')
            ->selectRaw("
                COUNT(*) as count_programme
            ")
            ->whereRaw("pro.inspection_period > 0 AND doc.date > 0 AND pro.decommissioned = 0")
            ->whereRaw("DATEDIFF(FROM_UNIXTIME(doc.date + (pro.inspection_period * 86400), '%Y-%m-%d'),CONVERT(CURDATE(),DATETIME)) > 0")
            ->first();

        $sql_total_programmes = "
            SELECT COUNT(pro.id) count_programme FROM compliance_programmes pro
            LEFT JOIN (
                SELECT programme_id, substring_index(group_concat(id Order By `date` DESC separator ','),',',1) ,
                substring_index(group_concat(`date` Order By date DESC separator ','),',',1) `date`
                 FROM compliance_documents
                WHERE is_reinspected = 1 AND programme_id > 0 GROUP BY programme_id
            ) doc ON doc.programme_id = pro.id
            JOIN tbl_property p ON p.id = pro.property_id
            JOIN (SELECT property_id  FROM property_property_type as ppt
                JOIN tbl_property_type as pt on ppt.property_type_id = pt.id
                WHERE pt.ms_level = 1
                GROUP BY property_id) prisk ON prisk.property_id = p.id
            WHERE pro.property_id > 0 AND pro.decommissioned = 0 AND pro.inspection_period > 0 AND p.decommissioned = 0
            GROUP BY p.decommissioned
        ";
        $result_total_programmes = DB::select(DB::raw($sql_total_programmes));
        return [
            'count_programme' => $result->count_programme ?? 0,
            'total' => $result_total_programmes[0]->count_programme ?? 0
        ];
    }

    public function getDataProjectDocuments() {
        $all_project_types = ProjectType::pluck('id')->toArray();
        $project_types = '('.implode(',',$all_project_types).')';
        $timeSQL = '';
        if (!\CommonHelpers::isSystemClient()) {
//            $timeSQL .= " AND tbl_documents.contractor = " .\Auth::user()->client_id;
            $join_privs = '';
        } else {
            //privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $join_privs = "JOIN $table_join_privs on permission.prop_id = pj.property_id";
        }
        $sqlQuery = "SELECT COUNT(`tbl_documents`.`id`) as count_documents
                        FROM `tbl_documents`
                        LEFT JOIN tbl_project as pj
                        ON `tbl_documents`.project_id = pj.id
                        LEFT JOIN (SELECT sv.project_id, MAX( sd.published_date ) as published_date FROM tbl_survey sv LEFT JOIN tbl_survey_date sd ON sv.id = sd.survey_id WHERE sd.published_date IS NOT NULL AND sd.published_date > 0 GROUP BY sv.project_id) s ON s.project_id= pj.id
                        $join_privs
                        WHERE `tbl_documents`.`status` <> 2
                            AND pj.lead_key = '". \Auth::user()->id ."'
                            AND pj.`status` <> 5
                            $timeSQL
                            ";

        $sql_critical = $sqlQuery .  " AND `deadline` <= ". (15 * 86400 + time());
        $critical = DB::select(DB::raw($sql_critical));

        $sql_urgent = $sqlQuery .  " AND `deadline` <= ". (30 * 86400 + time()) ." AND `deadline` > " . (15 * 86400 + time());
        $urgent = DB::select(DB::raw($sql_urgent));

        $sql_important = $sqlQuery .  " AND `deadline` <= ". (60 * 86400 + time()) ." AND `deadline` > " . (30 * 86400 + time());
        $important = DB::select(DB::raw($sql_important));

        $sql_attention = $sqlQuery .  " AND `deadline` <= ". (120 * 86400 + time()) ." AND `deadline` > " . (60 * 86400 + time());
        $attention = DB::select(DB::raw($sql_attention));

        $sql_deadline = $sqlQuery .  " AND `deadline` > ". (120 * 86400 + time());
        $deadline = DB::select(DB::raw($sql_deadline));

        return [
            'critical' => $critical[0]->count_documents,
            'urgent' => $urgent[0]->count_documents,
            'important' => $important[0]->count_documents,
            'attention' => $attention[0]->count_documents,
            'deadline' => $deadline[0]->count_documents
        ];
    }

    public function getDataAssessments() {
        $timeSQL = '';
        $join_privs = '';
        if (!\CommonHelpers::isSystemClient()) {
            $timeSQL .= " AND s.client_id = " .\Auth::user()->client_id;
        } else {
            //privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $join_privs = "JOIN $table_join_privs on permission.prop_id = s.property_id";
        }

        $sqlQuery = "SELECT COUNT(s.id) as count_assessments
                        FROM cp_assessments s
                        $join_privs
                        WHERE s.decommissioned = 0
                        AND s.status != 5
                        AND ( s.lead_by = '". \Auth::user()->id ."'
                            OR s.second_lead_by = '". \Auth::user()->id ."'
                            OR s.quality_checker = '". \Auth::user()->id ."' )
                        AND s.classification IN ('". ASSESSMENT_FIRE_TYPE ."', '". ASSESSMENT_WATER_TYPE ."', '". ASSESSMENT_HS_TYPE ."')
                        $timeSQL
                        ";

        $sql_critical = $sqlQuery .  " AND s.due_date <= ". (15 * 86400 + time());
        $critical = DB::select(DB::raw($sql_critical));

        $sql_urgent = $sqlQuery .  " AND s.due_date <= ". (30 * 86400 + time()) ." AND s.due_date > " . (15 * 86400 + time());
        $urgent = DB::select(DB::raw($sql_urgent));

        $sql_important = $sqlQuery .  " AND s.due_date <= ". (60 * 86400 + time()) ." AND s.due_date > " . (30 * 86400 + time());
        $important = DB::select(DB::raw($sql_important));

        $sql_attention = $sqlQuery .  " AND s.due_date <= ". (120 * 86400 + time()) ." AND s.due_date > " . (60 * 86400 + time());
        $attention = DB::select(DB::raw($sql_attention));

        $sql_deadline = $sqlQuery .  " AND s.due_date > ". (120 * 86400 + time());
        $deadline = DB::select(DB::raw($sql_deadline));

        return [
            'critical' => $critical[0]->count_assessments,
            'urgent' => $urgent[0]->count_assessments,
            'important' => $important[0]->count_assessments,
            'attention' => $attention[0]->count_assessments,
            'deadline' => $deadline[0]->count_assessments
        ];
    }

    public function getDataSurveys() {
        $user_id = \Auth::user()->id;
        $client_id = \Auth::user()->client_id;
        if ($client_id != 1) {
            $surveys1 = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')
                ->select(['tbl_survey.*', 'sd.due_date'])
                ->leftJoin('tbl_survey_date as sd', 'sd.survey_id', '=', 'tbl_survey.id')
                ->where('status','!=',COMPLETED_SURVEY_STATUS)
                ->where('decommissioned', 0)
                ->where(['client_id' => $client_id])
                ->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                })
                ->get();
            $project_ids = Project::whereRaw("FIND_IN_SET('$client_id', REPLACE(checked_contractors, ' ', ''))")->where('status', '!=' , 5)->orderBy('id','desc')->pluck('id')->toArray();
            $surveys2 = Survey::with('project','surveyDate','publishedSurvey','project', 'property', 'surveyDate','clients','surveySetting')
                ->select(['tbl_survey.*', 'sd.due_date'])
                ->leftJoin('tbl_survey_date as sd', 'sd.survey_id', '=', 'tbl_survey.id')
                ->where('decommissioned', 0)
                ->where('status','!=',COMPLETED_SURVEY_STATUS)
                ->whereIn('project_id', $project_ids)
                ->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                })
                ->get();
            $surveys = $surveys1->merge($surveys2);
        } else {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $surveys = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')
                ->select(['tbl_survey.*', 'sd.due_date'])
                ->leftJoin('tbl_survey_date as sd', 'sd.survey_id', '=', 'tbl_survey.id')
                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                ->where('status','!=',COMPLETED_SURVEY_STATUS)
                ->where('decommissioned', 0)
                ->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                })
                ->get();
        }
        $critical = $surveys->where('sd.due_date', '<=', (15 * 86400 + time()))->count() ?? 0;
        $urgent = $surveys->where('sd.due_date', '<=', (30 * 86400 + time()))->where('sd.due_date', '>', (15 * 86400 + time()))->count() ?? 0;
        $important = $surveys->where('sd.due_date', '<=', (60 * 86400 + time()))->where('sd.due_date', '>', (30 * 86400 + time()))->count() ?? 0;
        $attention = $surveys->where('sd.due_date', '<=', (120 * 86400 + time()))->where('sd.due_date', '>', (60 * 86400 + time()))->count() ?? 0;
        $deadline = $surveys->where('sd.due_date', '>', (120 * 86400 + time()))->count() ?? 0;

        return [
            'critical' => $critical,
            'urgent' => $urgent,
            'important' => $important,
            'attention' => $attention,
            'deadline' => $deadline
        ];
    }

    public function getDataCertificates() {

        return [
            'critical' => 0,
            'urgent' => 0,
            'important' => 0,
            'attention' => 0,
            'deadline' => 0
        ];
    }

    public function getDataQualityAssuranceCertificates() {

        return [
            'technical_in_progress' => 0,
            'approval' => 0,
            'rejected' => 0
        ];
    }

    public function getDataIncidentReports() {
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $sqlQuery = "SELECT COUNT(i.id) as count_incident_reports
                        FROM incident_reports i
                        JOIN $table_join_privs on permission.prop_id = i.property_id
                        WHERE i.decommissioned = 0
                            AND (i.asbestos_lead = '". \Auth::user()->id ."' OR i.report_recorder = '". \Auth::user()->id ."')
                            AND i.status = ". INCIDENT_REPORT_REJECT;

        $sql_critical = $sqlQuery .  " AND i.due_date <= ". (15 * 86400 + time());
        $critical = DB::select(DB::raw($sql_critical));

        $sql_urgent = $sqlQuery .  " AND i.due_date <= ". (30 * 86400 + time()) ." AND i.due_date > " . (15 * 86400 + time());
        $urgent = DB::select(DB::raw($sql_urgent));

        $sql_important = $sqlQuery .  " AND i.due_date <= ". (60 * 86400 + time()) ." AND i.due_date > " . (30 * 86400 + time());
        $important = DB::select(DB::raw($sql_important));

        $sql_attention = $sqlQuery .  " AND i.due_date <= ". (120 * 86400 + time()) ." AND i.due_date > " . (60 * 86400 + time());
        $attention = DB::select(DB::raw($sql_attention));

        $sql_deadline = $sqlQuery .  " AND i.due_date > ". (120 * 86400 + time());
        $deadline = DB::select(DB::raw($sql_deadline));

        return [
            'critical' => $critical[0]->count_incident_reports,
            'urgent' => $urgent[0]->count_incident_reports,
            'important' => $important[0]->count_incident_reports,
            'attention' => $attention[0]->count_incident_reports,
            'deadline' => $deadline[0]->count_incident_reports
        ];
    }

    public function getDataWorkRequests() {
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $sqlQuery = "SELECT COUNT(wsd.id) as count_work_request_documents
                        FROM tbl_work_supporting_documents wsd
                        LEFT JOIN tbl_work_request as wq ON wq.id = wsd.work_id
                        JOIN $table_join_privs on permission.prop_id = wq.property_id
                        WHERE wq.decommissioned = 0
                          AND wq.asbestos_lead = '". \Auth::user()->id ."'
                          AND wq.status = ". WORK_REQUEST_REJECT;

        $sql_critical = $sqlQuery .  " AND wq.due_date <= ". (15 * 86400 + time());
        $critical = DB::select(DB::raw($sql_critical));

        $sql_urgent = $sqlQuery .  " AND wq.due_date <= ". (30 * 86400 + time()) ." AND wq.due_date > " . (15 * 86400 + time());
        $urgent = DB::select(DB::raw($sql_urgent));

        $sql_important = $sqlQuery .  " AND wq.due_date <= ". (60 * 86400 + time()) ." AND wq.due_date > " . (30 * 86400 + time());
        $important = DB::select(DB::raw($sql_important));

        $sql_attention = $sqlQuery .  " AND wq.due_date <= ". (120 * 86400 + time()) ." AND wq.due_date > " . (60 * 86400 + time());
        $attention = DB::select(DB::raw($sql_attention));

        $sql_deadline = $sqlQuery .  " AND wq.due_date > ". (120 * 86400 + time());
        $deadline = DB::select(DB::raw($sql_deadline));

        return [
            'critical' => $critical[0]->count_work_request_documents,
            'urgent' => $urgent[0]->count_work_request_documents,
            'important' => $important[0]->count_work_request_documents,
            'attention' => $attention[0]->count_work_request_documents,
            'deadline' => $deadline[0]->count_work_request_documents
        ];
    }

    public function getDataQualityAssuranceProjectDocuments() {
        $all_project_types = ProjectType::pluck('id')->toArray();
        $project_types = '('.implode(',',$all_project_types).')';
        $timeSQL = '';
        if (!\CommonHelpers::isSystemClient()) {
            $timeSQL .= " AND tbl_documents.contractor = " .\Auth::user()->client_id;
            $join_privs = '';
        } else {
            //privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $join_privs = "JOIN $table_join_privs on permission.prop_id = pj.property_id";
        }
        $sqlQuery = "SELECT COUNT(`tbl_documents`.`id`) as count_documents
                        FROM `tbl_documents`
                        LEFT JOIN `tbl_refurb_doc_types` as dt
                            ON `tbl_documents`.type = dt.id
                        LEFT JOIN (SELECT tbl_project.*,  tbl_property.pblock,tbl_property.name,tbl_property.property_reference,tbl_property.id as prop_id
                            FROM tbl_project LEFT JOIN tbl_property ON tbl_project.property_id = tbl_property.id
                            where tbl_project.project_type in $project_types
                            )
                            as pj
                        ON `tbl_documents`.project_id = pj.id
                        LEFT JOIN (SELECT sv.project_id, MAX( sd.published_date ) as published_date FROM tbl_survey sv LEFT JOIN tbl_survey_date sd ON sv.id = sd.survey_id WHERE sd.published_date IS NOT NULL AND sd.published_date > 0 GROUP BY sv.project_id) s ON s.project_id= pj.id
                        $join_privs
                        WHERE `tbl_documents`.category IN (1, 2, 3, 6, 5, 9, 10, 11)
                            AND pj.lead_key = '". \Auth::user()->id ."'
                            AND pj.`status` NOT IN (1,2,5)
                            $timeSQL
                            ";

        $sql_technical_in_progress = $sqlQuery .  " AND `tbl_documents`.`status` IN (1, 5)";
        $technical_in_progress = DB::select(DB::raw($sql_technical_in_progress));

        $sql_approval = $sqlQuery .  " AND `tbl_documents`.`status` = 4";
        $approval = DB::select(DB::raw($sql_approval));

        $sql_rejected = $sqlQuery .  " AND `tbl_documents`.`status` = 3";
        $rejected = DB::select(DB::raw($sql_rejected));

        return [
            'technical_in_progress' => $technical_in_progress[0]->count_documents,
            'approval' => $approval[0]->count_documents,
            'rejected' => $rejected[0]->count_documents
        ];
    }

    public function getDataQualityAssuranceAssessments() {
        $timeSQL = '';
        $join_privs = '';
        if (!\CommonHelpers::isSystemClient()) {
            $timeSQL .= " AND a.client_id = " .\Auth::user()->client_id;
        } else {
            //privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $join_privs = "JOIN $table_join_privs on permission.prop_id = a.property_id";
        }

        $sqlQuery = "SELECT COUNT(a.id) as count_assessments
                        FROM cp_assessments a
                        $join_privs
                        WHERE a.decommissioned = 0
                            AND a.classification IN ('". ASSESSMENT_FIRE_TYPE ."', '". ASSESSMENT_WATER_TYPE ."', '". ASSESSMENT_HS_TYPE ."')
                            AND ( a.lead_by = '". \Auth::user()->id ."'
                                OR a.second_lead_by = '". \Auth::user()->id ."'
                                OR a.quality_checker = '". \Auth::user()->id ."' )
                            $timeSQL
                            ";

        $sql_technical_in_progress = $sqlQuery .  " AND a.status NOT IN (". ASSESSMENT_STATUS_COMPLETED .",". ASSESSMENT_STATUS_REJECTED .",". ASSESSMENT_STATUS_PUBLISHED .")";
        $technical_in_progress = DB::select(DB::raw($sql_technical_in_progress));

        $sql_approval = $sqlQuery .  " AND a.status = ". ASSESSMENT_STATUS_PUBLISHED;
        $approval = DB::select(DB::raw($sql_approval));

        $sql_rejected = $sqlQuery .  " AND a.status = ". ASSESSMENT_STATUS_REJECTED;
        $rejected = DB::select(DB::raw($sql_rejected));

        return [
            'technical_in_progress' => $technical_in_progress[0]->count_assessments,
            'approval' => $approval[0]->count_assessments,
            'rejected' => $rejected[0]->count_assessments
        ];
    }

    public function getDataQualityAssuranceSurveys() {
        $user_id = \Auth::user()->id;
        $client_id = \Auth::user()->client_id;
        if ($client_id != 1) {
            $surveys1 = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')
                ->where('decommissioned', 0)
                ->where(['client_id' => $client_id])
                ->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                })
                ->get();
            $project_ids = Project::whereRaw("FIND_IN_SET('$client_id', REPLACE(checked_contractors, ' ', ''))")->where('status', '!=' , 5)->orderBy('id','desc')->pluck('id')->toArray();
            $surveys2 = Survey::with('project','surveyDate','publishedSurvey','project', 'property', 'surveyDate','clients','surveySetting')
                ->where('decommissioned', 0)
                ->whereIn('project_id', $project_ids)
                ->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                })
                ->get();
            $surveys = $surveys1->merge($surveys2);
        } else {
            // property privilege
            $table_join_privs = \CompliancePrivilege::getPropertyPermission();
            $surveys = Survey::with('project', 'property', 'surveyDate','clients','surveySetting')
                ->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'property_id')
                ->where('decommissioned', 0)
                ->where(function ($query) use ($user_id) {
                    $query->where(['lead_by' => $user_id]);
                    $query->orWhere(['second_lead_by' => $user_id]);
                    $query->orWhere(['quality_id' => $user_id]);
                })
                ->get();
        }
        $technical_in_progress = $surveys->whereNotIn('status', [COMPLETED_SURVEY_STATUS, REJECTED_SURVEY_STATUS, PULISHED_SURVEY_STATUS])->count() ?? 0;
        $approval = $surveys->where('status', PULISHED_SURVEY_STATUS)->count() ?? 0;
        $rejected = $surveys->where('status', REJECTED_SURVEY_STATUS)->count() ?? 0;

        return [
            'technical_in_progress' => $technical_in_progress,
            'approval' => $approval,
            'rejected' => $rejected
        ];
    }

    public function getDataQualityAssuranceIncidentReports() {
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $sqlQuery = "SELECT COUNT(i.id) as count_incident_reports
                        FROM incident_reports i
                        JOIN $table_join_privs on permission.prop_id = i.property_id
                        WHERE i.decommissioned = 0
                            AND (i.asbestos_lead = '". \Auth::user()->id ."' OR i.report_recorder = '". \Auth::user()->id ."')";

        $sql_technical_in_progress = $sqlQuery .  " AND i.status NOT IN (". INCIDENT_REPORT_COMPLETE .",". INCIDENT_REPORT_REJECT .",". INCIDENT_REPORT_AWAITING_APPROVAL .")";
        $technical_in_progress = DB::select(DB::raw($sql_technical_in_progress));

        $sql_approval = $sqlQuery .  " AND i.status = ". INCIDENT_REPORT_AWAITING_APPROVAL;
        $approval = DB::select(DB::raw($sql_approval));

        $sql_rejected = $sqlQuery .  " AND i.status = ". INCIDENT_REPORT_REJECT;
        $rejected = DB::select(DB::raw($sql_rejected));

        return [
            'technical_in_progress' => $technical_in_progress[0]->count_incident_reports,
            'approval' => $approval[0]->count_incident_reports,
            'rejected' => $rejected[0]->count_incident_reports
        ];
    }

    public function getDataQualityAssuranceWorkRepuests() {
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $sqlQuery = "SELECT COUNT(wsd.id) as count_work_request_documents
                        FROM tbl_work_supporting_documents wsd
                        LEFT JOIN tbl_work_request as wq ON wq.id = wsd.work_id
                        JOIN $table_join_privs on permission.prop_id = wq.property_id
                        WHERE wq.decommissioned = 0
                            AND wq.asbestos_lead = '". \Auth::user()->id ."'";

        $sql_technical_in_progress = $sqlQuery .  " AND wq.status NOT IN (". WORK_REQUEST_COMPLETE .",". WORK_REQUEST_REJECT .",". WORK_REQUEST_AWAITING_APPROVAL .")";
        $technical_in_progress = DB::select(DB::raw($sql_technical_in_progress));

        $sql_approval = $sqlQuery .  " AND wq.status = ". WORK_REQUEST_AWAITING_APPROVAL;
        $approval = DB::select(DB::raw($sql_approval));

        $sql_rejected = $sqlQuery .  " AND wq.status = ". WORK_REQUEST_REJECT;
        $rejected = DB::select(DB::raw($sql_rejected));

        return [
            'technical_in_progress' => $technical_in_progress[0]->count_work_request_documents,
            'approval' => $approval[0]->count_work_request_documents,
            'rejected' => $rejected[0]->count_work_request_documents
        ];
    }
}
