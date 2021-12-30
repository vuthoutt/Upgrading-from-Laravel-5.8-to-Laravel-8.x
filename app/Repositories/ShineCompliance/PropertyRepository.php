<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\DropdownDataProperty;
use App\Models\ShineCompliance\Property;
use App\Models\ShineCompliance\PropertyDropdownTitle;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class PropertyRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Property::class;
    }

    function getListByZone($client_id, $zone_id, $relation, $request, $pagination){
        // add privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        if($request->has('q') || $request->has('asset_class') || $request->has('property_status') || $request->has('status') || $request->has('identified_risks') || $request->has('system_type') || $request->has('equipment_types')){
            if(is_array($zone_id)){
                $builder = $this->model->with($relation)->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
                    ->where('client_id', $client_id)
                    ->whereIn('zone_id', $zone_id);
            } else {
                $builder = $this->model->with($relation)->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
                    ->where('client_id', $client_id)
                    ->where('zone_id', $zone_id);
            }
            if(isset($request->q) && !empty($request->q)){
                $condition_raw = "( `name` LIKE '%" . $request->q . "%'
                                    OR `property_reference` LIKE '%" . $request->q . "%'
                                    OR `reference` LIKE '%" . $request->q . "%'
                                    OR `pblock` LIKE '%" . $request->q . "%' )";
                $builder->whereRaw($condition_raw);
            }

            if(isset($request->asset_class) && !empty($request->asset_class)){
                $condition = explode(",", $request->asset_class);
                if(count($condition) > 0){
                    $builder->whereIn('asset_class_id', $condition);
                }
            }

            if(isset($request->property_status) && !empty($request->property_status)){
                $condition = explode(",", $request->property_status);

                if(count($condition) > 0){
                    $builder->whereHas('propertySurvey', function($q) use ($condition)
                    {
                        $q->whereIn('property_status', $condition);
                    });
                }
            }
            if(isset($request->status)){
                $condition = explode(",", $request->status);
                if(count($condition) > 0){
                    $builder->whereIn('decommissioned', $condition);
                }
            }

            if(isset($request->system_type) && !empty($request->system_type)){
                $condition = explode(",", $request->system_type);

                if(count($condition) > 0){
                    $builder->whereHas('system', function($q) use ($condition)
                    {
                        $q->where('decommissioned', COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED)->whereIn('type', $condition);

                    });
                }
            }

            if(isset($request->equipment_types) && !empty($request->equipment_types)){
                $condition = explode(",", $request->equipment_types);

                if(count($condition) > 0){
                    $builder->whereHas('equipment', function($q) use ($condition)
                    {
                        $q->where('decommissioned', COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED)->whereIn('type', $condition);

                    });
                }
            }
            if(isset($request->identified_risks) && !empty($request->identified_risks)){
                $condition = explode(",", $request->identified_risks);
                if(count($condition) > 0){
                    if(in_array(FILTER_INACCESSIBLE_ROOM_LOCATIONS, $condition)){
                        $builder->whereHas('locations', function($q) use ($condition)
                        {
                            $q->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED, 'survey_id' => 0, 'state' => LOCATION_STATE_INACCESSIBLE]);
                        });
                    }
                    if(in_array(FILTER_INACCESSIBLE_VOIDS, $condition)){
                        $const_ceiling_inacc = LOCATION_VOID_INACC_CELLING;
                        $const_floor_inacc = LOCATION_VOID_INACC_FLOOR;
                        $const_cavities_inacc = LOCATION_VOID_INACC_CAVITIES;
                        $const_risers_inacc = LOCATION_VOID_INACC_RISERS;
                        $const_ducting_inacc = LOCATION_VOID_INACC_DUCTING;
                        $const_boxing_inacc = LOCATION_VOID_INACC_BOXING;
                        $const_pipework_inacc = LOCATION_VOID_INACC_PIPEWORK;
                        $builder->whereHas('locations', function($q) use($const_ceiling_inacc, $const_floor_inacc, $const_cavities_inacc, $const_risers_inacc, $const_ducting_inacc, $const_boxing_inacc, $const_pipework_inacc)
                        {
                            $q->whereHas('locationVoid', function($q2) use($const_ceiling_inacc, $const_floor_inacc, $const_cavities_inacc, $const_risers_inacc, $const_ducting_inacc, $const_boxing_inacc, $const_pipework_inacc)
                            {
                                $q2->whereRaw("(SUBSTRING_INDEX( ceiling, ',', 1) = $const_ceiling_inacc)
                                                    OR (SUBSTRING_INDEX( floor, ',', 1) = $const_floor_inacc)
                                                    OR (SUBSTRING_INDEX( cavities, ',', 1) = $const_cavities_inacc)
                                                    OR (SUBSTRING_INDEX( risers, ',', 1) = $const_risers_inacc)
                                                    OR (SUBSTRING_INDEX( ducting, ',', 1) = $const_ducting_inacc)
                                                    OR (SUBSTRING_INDEX( boxing, ',', 1) = $const_boxing_inacc)
                                                    OR (SUBSTRING_INDEX( pipework, ',', 1) = $const_pipework_inacc)");
                            })->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED, 'survey_id' => 0, 'state' => LOCATION_STATE_INACCESSIBLE]);
                        });
                    }
                    if(in_array(FILTER_ASBESTOS_CONTAINING_MATERIALS, $condition)){
                        $builder->whereHas('items', function($q) use ($condition)
                        {
                            $q->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED, 'survey_id' => 0])->where('state', '!=', ITEM_NOACM_STATE);
                        });
                    }
                    if(in_array(FILTER_HAZARDS, $condition)){
                        $builder->whereHas('hazard', function($q) use ($condition)
                        {
                            $q->where(['decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED, 'assess_id' => 0, 'is_temp' => COMPLIANCE_NORMAL_HAZARD, 'is_deleted' => COMPLIANCE_UNDELETED_HAZARD]);
                        });
                    }
                    if(in_array(FILTER_NONCONFORMITY, $condition)){
                        $builder->whereHas('nonconformity', function($q)
                        {
                            $q->where(['assess_id' => 0, 'is_deleted' => COMPLIANCE_UNDELETED_HAZARD]);

                        });
                    }
                }
            }
            return $builder->paginate($pagination);
        }
        if(is_array($zone_id)){
            return $this->model->with($relation)->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
                ->where('client_id', $client_id)
                ->whereIn('zone_id', $zone_id)->paginate($pagination);
        } else {
            return $this->model->with($relation)->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
                ->where('client_id', $client_id)
                ->where('zone_id', $zone_id)->paginate($pagination);
        }
    }

    function getAllProperty(){
        $sql = "SELECT id FROM `tbl_property`";
        $results = collect(DB::select($sql))->pluck('id')->toArray();
        return $results;

    }

    function getProperty($id, $relation){
        return $this->model->with($relation)->where('id', $id)->first();
    }

    function updateProperty($id, $data){
        return $this->model->where('id', $id)->update($data);
    }

    public function getPropertyDropdownData($id) {
        $dropdowns = PropertyDropdown::where('dropdown_id', $id)->get();
        return is_null($dropdowns) ? [] : $dropdowns;
    }

    public function getParentProperty($parent_id){
        return $this->model->where('id', $parent_id)->first();
    }

    public function getOrchardWarningMessage($property_id) {
        $property = Property::find($property_id);
        // When Post 2000 Property has been selected in the Property Risk Type
        $build_after_2000 = false;
        if(isset($property->propertyType) &&  !$property->propertyType->isEmpty()){
            foreach ($property->propertyType as $p_risk_type){
                if($p_risk_type->id == 2){
                    $build_after_2000 = true;
                    break;
                }
            }
        }
        if($build_after_2000){
            return NULL;
            //duplicated
//            return [
//                'sort' => "No Asbestos Detected",
//                'long' => "Property Build In or After 2000, No Asbestos Detected"
//            ];
        } else {

            // CHECK MANAGEMENT SURVEY NOT COMPLETED
            $sql_not_complete_management_survey = "SELECT `id`
                                        FROM `tbl_survey`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and survey_type = 1 and `status` not in (5) ";
            $not_complete_management_survey = \DB::select($sql_not_complete_management_survey);


            // CHECK MANAGEMENT SURVEY EXIST
            $sql_first_management_survey = "SELECT id
                                        FROM `tbl_survey`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and survey_type = 1 ";
            $first_management_survey = \DB::select($sql_first_management_survey);

            // CHECK REFURBISH SURVEY NOT COMPLETED
            $sql_not_complete_refurbish_survey = "SELECT id
                                        FROM `tbl_survey`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and survey_type = 2 and `status` not in (5) ";
            $not_complete_refurbish_survey = \DB::select($sql_not_complete_refurbish_survey);

            // CHECK REFURBISH SURVEY EXIST
            $sql_first_refurbish_survey = "SELECT id
                                        FROM `tbl_survey`
                                        WHERE  `property_id` = '" . $property_id . "'
                                        AND `decommissioned` = 0 and survey_type = 2 ";
            $first_refurbish_survey = \DB::select($sql_first_refurbish_survey);

            // CHECK INACCESSIBLE LOCATION AVAILABLE IN REGISTER
            $location_in_access_sql = "SELECT id
                                            FROM `tbl_location`
                                            WHERE `survey_id` = 0
                                                AND  `property_id` =  $property_id
                                                AND `state` = 0
                                                AND `decommissioned` = 0
                                            LIMIT 1";
            $location_in_accessible = \DB::select($location_in_access_sql);

            // CHECK ITEM ACM
            $item_acm_register_sql = "SELECT id
                                    FROM `tbl_items`
                                    WHERE
                                        property_id = $property_id
                                        AND `state` != 1
                                        AND `decommissioned` = 0
                                    LIMIT 1";
            $item_acm_register = \DB::select($item_acm_register_sql);

            // Management Survey was completed and there was no Inaccessible Room/locations OR ACM Items found in the Survey.
            // Inaccessible Room/locations in completed management survey
            $location_in_access_in_management_survey_sql = "SELECT l.id
                                            FROM `tbl_location` l
                                            LEFT JOIN tbl_survey s on l.survey_id = s.id
                                            WHERE s.`property_id` =  $property_id
                                                AND l.`state` = 0
                                                AND s.`survey_type` = 1
                                                AND l.`decommissioned` = 0
                                                AND s.`decommissioned` = 0
                                                AND s.`status` in (5)
                                            LIMIT 1";
            $location_in_access_in_management_survey = \DB::select($location_in_access_in_management_survey_sql);

            // Inaccessible Room/locations in completed FS survey
            $location_in_access_in_refurbish_survey_sql = "SELECT l.id
                                            FROM `tbl_location` l
                                            LEFT JOIN tbl_survey s on l.survey_id = s.id
                                            WHERE s.`property_id` =  $property_id
                                                AND l.`state` = 0
                                                AND s.`survey_type` = 1
                                                AND l.`decommissioned` = 0
                                                AND s.`decommissioned` = 0
                                                AND s.`status` in (5)
                                            LIMIT 1";
            $location_in_access_in_refurbish_survey = \DB::select($location_in_access_in_refurbish_survey_sql);

            // ACM in completed management survey
            $ACM_in_management_survey_sql = "SELECT i.id
                                            FROM `tbl_items` i
                                            LEFT JOIN tbl_survey s on i.survey_id = s.id
                                            WHERE s.`property_id` =  $property_id
                                                AND i.`state` != 1
                                                AND s.`survey_type` = 1
                                                AND i.`decommissioned` = 0
                                                AND s.`decommissioned` = 0
                                                AND s.`status` in (5)
                                            LIMIT 1";
            $ACM_in_management_survey = \DB::select($ACM_in_management_survey_sql);

            // ACM in completed FS survey
            $ACM_in_refurbish_survey_sql = "SELECT i.id
                                            FROM `tbl_items` i
                                            LEFT JOIN tbl_survey s on i.survey_id = s.id
                                            WHERE s.`property_id` =  $property_id
                                                AND i.`state` != 1
                                                AND s.`survey_type` = 2
                                                AND i.`decommissioned` = 0
                                                AND s.`decommissioned` = 0
                                                AND s.`status` in (5)
                                            LIMIT 1";
            $ACM_in_refurbish_survey = \DB::select($ACM_in_refurbish_survey_sql);

            if ($not_complete_management_survey  || !$first_management_survey) {
                if ($first_refurbish_survey and !$not_complete_refurbish_survey) {//have a RS completed
                    // When a <2000 Property on the shineAsbestos Software has not had a Management Survey completed but has had a Refurbishment Survey completed that found no Inaccessible Room/location or ACMs
                    //Refurbishment Survey was completed on the property and there was an Inaccessible Room/location in the Survey
                    if($location_in_access_in_refurbish_survey) {
                        return [
                            'sort' => "Asbestos Presumed",
                            'long' => "Targetted Refurbishment Survey Conducted only - Not Assessed and Inaccessible Room/locations; Asbestos must be Presumed to be Present"
                        ];
                        // Refurbishment Survey was completed on the property and there was an ACM in the Survey
                    } else if ($ACM_in_refurbish_survey) {
                        return [
                            'sort' => "Asbestos Identified",
                            'long' => "Targeted Refurbishment Survey Conducted; Asbestos Present within Property"
                        ];
                    }

                    if($item_acm_register){
                        // Refurbishment Survey completed and no Management Survey. There is ACMs in the Register
                        return [
                            'sort' => "Asbestos Identified",
                            'long' => "Targeted Refurbishment Survey Conducted only; Asbestos Present within the Property"
                        ];
                    } else if ( $location_in_accessible) {
                        // Refurbishment Survey completed and no Management Survey. There is ACMs in the Register
                        return [
                            'sort' => "Asbestos Presumed",
                            'long' => "Targetted Refurbishment Survey Conducted only - Not Assessed and Inaccessible Room/locations; Asbestos must be Presumed to be Present"
                        ];
                    } else {
                        return [
                            'sort' => "Asbestos Presumed",
                            'long' => "Targetted Refurbishment Survey only; Asbestos must be Presumed to be Present in the Remaining Unsurveyed Room/locations"
                        ];
                    }
                }
                // Management Survey not completed on the Property
                return [
                    'sort' => "Asbestos Presumed",
                    'long' => "Management Survey Not Completed; Asbestos must be Presumed to be Present"
                ];
            } else {
                // Management Survey was completed and there was an Inaccessible Room/location found in the Survey.
                if($location_in_access_in_management_survey) {
                    return [
                        'sort' => "Asbestos Presumed",
                        'long' => "Management Survey conducted; Inaccessible Room/locations; Asbestos must be Presumed to be Present"
                    ];
                }
                // Management Survey was completed and there was an ACM Item found in the Survey
                if($ACM_in_management_survey) {
                    return [
                        'sort' => "Asbestos Identified",
                        'long' => "Management Survey conducted; Asbestos Present within Property"
                    ];
                }
                // Management Survey was completed and there was no Inaccessible Room/locations OR ACM Items found in the Survey.
                if(!$location_in_access_in_management_survey || $ACM_in_management_survey) {
                    return [
                        'sort' => "Asbestos Presumed",
                        'long' => "Management Survey conducted and No Presumed/Identified Asbestos Found; Further Inspection to be Undertaken Prior to Refurbishment Works"
                    ];
                }

                if($item_acm_register){
                    // Management Survey completed and there is No ACMs or Inaccessible Room/locations in Register but there is Inaccessible Void Investigations in Register
                    return [
                        'sort' => "Asbestos Identified",
                        'long' => "Management Survey conducted; Asbestos Present within the Property"
                    ];
                } else if ($location_in_accessible) {
                    // When a <2000 Property on the shineAsbestos Software has had a Management Survey completed and Inaccessible Room/locations were found.
                    return [
                        'sort' => "Asbestos Presumed",
                        'long' => "Management Survey conducted; Inaccessible Room/locations; Asbestos must be Presumed to be Present"
                    ];
                } else {
                    // When a <2000 Property on the shineAsbestos Software has had a Management Survey completed and no Inaccessible Room/locations or ACMs were found.
                    return [
                        'sort' => "Asbestos Presumed",
                        'long' => "Management Survey conducted; No Presumed/Identified Asbestos Found; Further Inspection to be Undertaken Prior to Refurbishment Works"
                    ];
                }
            }

            return [
                'sort' => "Asbestos Presumed",
                'long' => "Management Survey Not Completed; Asbestos must be Presumed to be Present"
            ];
        }
    }

    public function searchProperty($query_string, $type = 0, $parent =  null) {

        if ($type == 1) {
            $searchSQL = " p.`property_reference` LIKE '%" . $query_string . "%' ";
        } elseif ($type == 2) {
            $searchSQL = " p.`pblock` LIKE '%" . $query_string . "%'";
        } else {
            $searchSQL = " ( p.`name` LIKE '%" . $query_string . "%'
                        OR p.`property_reference` LIKE '%" . $query_string . "%'
                        OR  REPLACE(pi.postcode, ' ', '') LIKE '%" . str_replace(" ", "", $query_string) . "%'
                        OR p.`reference` LIKE '%" . $query_string . "%'
                        OR p.`pblock` LIKE '%" . $query_string . "%' ) ";
        }
        if (!is_null($parent)) {
             $searchSQL = " ( p.`name` LIKE '%" . $query_string . "%'
                        OR p.`property_reference` LIKE '%" . $query_string . "%'
                        OR  REPLACE(pi.postcode, ' ', '') LIKE '%" . str_replace(" ", "", $query_string) . "%'
                        OR p.`reference` LIKE '%" . $query_string . "%'
                        OR p.`pblock` LIKE '%" . $query_string . "%' )
                        AND parent_reference IS NULL";
        }

        if($type == 3){
            $decommission = "1";
        }else{
            $decommission = "0";
        }

        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $sql = "SELECT p.id, p.name, p.reference, p.property_reference, pi.postcode, p.pblock
                FROM `tbl_property` as p
                LEFT JOIN tbl_property_info as pi ON pi.property_id = p.id
                JOIN $table_join_privs ON permission.prop_id = p.id
                WHERE $searchSQL
                AND p.`decommissioned` = $decommission
                ORDER BY
                CASE
                    WHEN p.`name` LIKE '" . $query_string . "' THEN 1
                    WHEN p.`name` LIKE '" . $query_string . "%' THEN 2
                    ELSE 3
                END, p.`property_reference`
             LIMIT 0,30";


        $list = DB::select($sql);

        return $list;
    }

    public function getPropertyByZone($zone_id){
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        $property_ids = $this->model->where('zone_id',$zone_id)->join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')->pluck('id')->toarray();
        return $property_ids ?? [];
    }

    public function getSubProperty($property_id, $query, $limit){
        $builder = $this->model->where('parent_id', $property_id);
        if($query){
            $condition_raw = "( `name` LIKE '%" . $query . "%'
                                OR `property_reference` LIKE '%" . $query . "%'
                                OR `reference` LIKE '%" . $query . "%'
                                OR `pblock` LIKE '%" . $query . "%' )";
            $builder->whereRaw($condition_raw);
        }
        return $builder->orderBy('pblock')->paginate($limit);
    }
}
