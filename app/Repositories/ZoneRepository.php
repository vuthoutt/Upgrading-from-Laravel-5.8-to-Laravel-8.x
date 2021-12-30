<?php
namespace App\Repositories;
use App\Models\Property;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;

class ZoneRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Zone::class;
    }

    public function createZone($data) {
        try {
            $zone = Zone::create($data);
            if ($zone) {
                $refZone = "PG" . $zone->id;
                Zone::where('id', $zone->id)->update(['reference' => $refZone]);
                //log audit
                $comment = \Auth::user()->full_name . " added group " . $zone->zone_name;
                \CommonHelpers::logAudit(PROPERTY_GROUP_TYPE, $zone->id, AUDIT_ACTION_ADD, $refZone, $zone->client_id, $comment);
                if (isset($data['zone_image'])) {
                    $saveZoneImage = \CommonHelpers::saveFileShineDocumentStorage($data['zone_image'], $zone->id, ZONE_PHOTO);
                }
            }
            return $response = \CommonHelpers::successResponse('Property Group Added Successfully!', $zone->id);
        } catch (\Exception $e) {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Failed to create zone. Please try again!');
        }

    }

    public function updateZone($id, $data) {

        $zone = Zone::where('id', $id)->first();

        if (!is_null($zone)) {
            try {
                $zoneUpdate = $zone->update(['zone_name' => $data['zone_name']]);
                //log audit
                $comment = \Auth::user()->full_name . " edited group " . $zone->zone_name;
                \CommonHelpers::logAudit(PROPERTY_GROUP_TYPE, $zone->id, AUDIT_ACTION_EDIT, $zone->reference, $zone->client_id, $comment);

                if (isset($data['zone_image'])) {
                    $saveZoneImage = \CommonHelpers::saveFileShineDocumentStorage($data['zone_image'], $id, ZONE_PHOTO);
                }
                return $response = \CommonHelpers::successResponse('Property Group Updated Successfully!', $id);
            } catch (\Exception $e) {
                return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Can not update zone. Please try again!');
            }
        } else {
            return $response = \CommonHelpers::failResponse(STATUS_FAIL,'Can not find zone. Please try again!');
        }
    }


    public function getClientZone($client_id) {
        $sql = "SELECT DISTINCT z.id, z.zone_name
                    FROM tbl_zones as z
                    LEFT JOIN tbl_property as p ON p.zone_id = z.id
                    WHERE z.deleted_at is null And z.parent_id = 0
                    ORDER BY z.id";
        $results = DB::select($sql);
        return $results;
    }

    public function getClientZoneChild($client_id, $parent_id) {
        $sql = "SELECT DISTINCT z.id, z.zone_name
                    FROM tbl_zones as z
                    LEFT JOIN tbl_property as p ON p.zone_id = z.id
                    WHERE z.deleted_at is null And z.parent_id = $parent_id
                    ORDER BY z.id";
        $results = DB::select($sql);
        return $results;
    }

    public function getZoneOperative($client_id){
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();
        $zone_privs = Property::join(\DB::raw("$table_join_privs"), 'permission.prop_id', 'id')
            ->join('tbl_zones as z2', 'tbl_property.zone_id', 'z2.id')
            ->groupBy('z2.parent_id')->pluck('z2.parent_id')->toArray();

        $zones = Zone::with('itemACM','firstProperty')
            ->whereIn('id', $zone_privs)
            ->where(['parent_id' => 0, 'decommissioned' => 0
//                , 'id'=>13
            ])
            ->orderBy('zone_name')
            ->get();

        if(count($zones)){
            foreach ($zones as $zone){
                $zone->zone_operative = ['bg_color'=>'#d6e9c6','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'No Property Detected'];
                $list_properties = DB::select(DB::raw("SELECT count(tbl_property.id) as count_property, GROUP_CONCAT(tbl_property.id) as ids FROM tbl_property WHERE zone_id = $zone->id GROUP BY zone_id"));
                $count_properties = $list_properties[0]->count_property ?? 0;
                $property_ids = $list_properties[0]->ids ?? '';
                $list_after_2000 = 0;
                if($property_ids){
                    $list_after_2000 = DB::select(DB::raw(
                        "SELECT IFNULL(count(p1.id), 0) as count_property  FROM tbl_property p1
                               JOIN property_property_type p2 ON p1.id = p2.property_id AND p2.property_type_id = 2
                               WHERE zone_id = $zone->id GROUP BY zone_id"
                    ));
                    $list_after_2000 = $list_after_2000[0]->count_property ?? 0;
                }


                if($count_properties > 0 AND $count_properties == $list_after_2000){
                    $zone->zone_operative = ['bg_color'=>'#d6e9c6','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'Property Build in or After 2000'];
                } else {
                    if(!is_null($zone->itemACM)){
                        $zone->zone_operative = ['bg_color'=>'#f2dede','border_color'=>'#eed3d7','text_color'=>'#b94a48','text'=>'Asbestos Present'];
                    } else {
                        $first_inacc_void_location =  DB::select("SELECT lv.* from tbl_location_void lv
                                                            JOIN tbl_location l ON lv.location_id = l.id
                                                            LEFT JOIN tbl_property p ON l.property_id = p.id
                                                            WHERE p.zone_id = $zone->id AND l.decommissioned = 0 AND l.survey_id = 0 AND
                                                            (
                                                                FIND_IN_SET(1108, lv.ceiling)
                                                                OR FIND_IN_SET(1453, lv.floor)
                                                                OR FIND_IN_SET(1216, lv.cavities)
                                                                OR FIND_IN_SET(1280, lv.risers)
                                                                OR FIND_IN_SET(1344, lv.ducting)
                                                                OR FIND_IN_SET(1733, lv.boxing)
                                                                OR FIND_IN_SET(1606, lv.pipework)
                                                            ) LIMIT 1");
                        if(!is_null($zone->locationInaccessible) || $first_inacc_void_location || !is_null($zone->itemInaccACM)){
                            $zone->zone_operative = ['bg_color'=>'#f2dede','border_color'=>'#eed3d7','text_color'=>'#b94a48','text'=>'Asbestos Presumed'];
                        } else {
                            $zone->zone_operative = ['bg_color'=>'#dff0d8','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'No Asbestos Detected'];
                        }
                    }
                }
            }
        }
        return $zones;

    }

    public function getParentZoneOperative($client_id, $parent_id){
        // property privilege
        if (\CommonHelpers::isSystemClient()) {
            $property_id_privs =    \CompliancePrivilege::getPermission(PROPERTY_PERMISSION);
        }else {
            $property_id_privs =    \CompliancePrivilege::getPropertyContractorPermission();
        }
        $zone_privs = Property::whereIn('id', $property_id_privs)->groupBy('zone_id')->pluck('zone_id')->toArray();

        // $zone_parent_privs = Zone::whereIn('id', $zone_privs)->where('parent_id', $parent_id)->groupBy('parent_id')->pluck('id')->toArray();

        $zones = Zone::whereIn('id', $zone_privs)->where('parent_id', $parent_id)->get();
        if(count($zones)){
            foreach ($zones as $zone){
                $zone->zone_operative = ['bg_color'=>'#d6e9c6','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'No Property Detected'];
                $list_properties = DB::select(DB::raw("SELECT count( p.id ) AS count_property, GROUP_CONCAT( p.id ) AS ids
                                                            FROM
                                                                tbl_property p
                                                            JOIN tbl_zones z1 ON z1.id = p.zone_id AND z1.parent_id != 0
                                                            JOIN tbl_zones z2 ON z2.id = z1.parent_id AND z2.parent_id = 0
                                                            WHERE
                                                                z2.id = $zone->id
                                                            GROUP BY
                                                                z2.id"));

                $count_properties = $list_properties[0]->count_property ?? 0;
                $property_ids = $list_properties[0]->ids ?? '';
                $list_after_2000 = 0;
                if($property_ids){
                    $list_after_2000 = DB::select(DB::raw(
                        "SELECT IFNULL(count(p1.id), 0) as count_property  FROM tbl_property p1
                               JOIN property_property_type p2 ON p1.id = p2.property_id AND p2.property_type_id = 2
                               JOIN tbl_zones z1 ON z1.id = p1.zone_id AND z1.parent_id != 0
                               JOIN tbl_zones z2 ON z2.id = z1.parent_id AND z2.parent_id = 0
                               WHERE z2.id = $zone->id GROUP BY z2.id"
                    ));
                    $list_after_2000 = $list_after_2000[0]->count_property ?? 0;
                }


                if($count_properties > 0 AND $count_properties == $list_after_2000){
                    $zone->zone_operative = ['bg_color'=>'#d6e9c6','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'Property Build in or After 2000'];
                } else {
                    $first_acm_item = DB::select(DB::raw(
                        "SELECT i.id FROM tbl_items i
                               JOIN tbl_property p1 ON i.property_id = p1.id
                               JOIN tbl_zones z1 ON z1.id = p1.zone_id AND z1.parent_id != 0
                               JOIN tbl_zones z2 ON z2.id = z1.parent_id AND z2.parent_id = 0
                               WHERE i.survey_id = 0 AND i.decommissioned= 0
                               AND ((i.state = ". ITEM_ACCESSIBLE_STATE ." && i.total_risk > 0) OR i.state = ".ITEM_ACCESSIBLE_STATE .")
                            AND  z2.id = $zone->id LIMIT 1"
                    ));
                    $first_acm_item = $first_acm_item[0]->id ?? 0;
                    if($first_acm_item){
                        $zone->zone_operative = ['bg_color'=>'#f2dede','border_color'=>'#eed3d7','text_color'=>'#b94a48','text'=>'Asbestos Present'];
                    } else {
                        $first_inacc_void_location =  DB::select("SELECT lv.id as id from tbl_location_void lv
                                                            JOIN tbl_location l ON lv.location_id = l.id
                                                            JOIN tbl_property p ON l.property_id = p.id
                                                            JOIN tbl_zones z1 ON z1.id = p.zone_id AND z1.parent_id != 0
                                                            JOIN tbl_zones z2 ON z2.id = z1.parent_id AND z2.parent_id = 0
                                                            WHERE z2.id = $zone->id AND l.decommissioned = 0 AND l.survey_id = 0 AND
                                                            (
                                                                FIND_IN_SET(1108, lv.ceiling)
                                                                OR FIND_IN_SET(1453, lv.floor)
                                                                OR FIND_IN_SET(1216, lv.cavities)
                                                                OR FIND_IN_SET(1280, lv.risers)
                                                                OR FIND_IN_SET(1344, lv.ducting)
                                                                OR FIND_IN_SET(1733, lv.boxing)
                                                                OR FIND_IN_SET(1606, lv.pipework)
                                                            ) LIMIT 1");
                        $first_inacc_void_location = $first_inacc_void_location[0]->id ?? 0;
                        $first_inacc_location = DB::select("SELECT l.id as id from tbl_location l
                                                            JOIN tbl_property p ON l.property_id = p.id
                                                            JOIN tbl_zones z1 ON z1.id = p.zone_id AND z1.parent_id != 0
                                                            JOIN tbl_zones z2 ON z2.id = z1.parent_id AND z2.parent_id = 0
                                                            WHERE z2.id = $zone->id AND l.decommissioned = 0 AND l.survey_id = 0 AND
                                                            l.state = 0 LIMIT 1");
                        $first_inacc_location = $first_inacc_location[0]->id ?? 0;
                        $first_inacc_acm_item = DB::select(DB::raw(
                            "SELECT i.id as id FROM tbl_items i
                               JOIN tbl_property p1 ON i.property_id = p1.id
                               JOIN tbl_zones z1 ON z1.id = p1.zone_id AND z1.parent_id != 0
                               JOIN tbl_zones z2 ON z2.id = z1.parent_id AND z2.parent_id = 0
                               WHERE i.survey_id = 0 AND i.decommissioned= 0
                               AND ((i.state = ". ITEM_INACCESSIBLE_STATE ." && i.total_risk > 0) OR i.state = ".ITEM_INACCESSIBLE_STATE .")
                            AND  z2.id = $zone->id LIMIT 1"));
                        $first_inacc_acm_item = $first_inacc_acm_item[0]->id ?? 0;
                        if($first_inacc_void_location || $first_inacc_location || $first_inacc_acm_item){
                            $zone->zone_operative = ['bg_color'=>'#f2dede','border_color'=>'#eed3d7','text_color'=>'#b94a48','text'=>'Asbestos Presumed'];
                        } else {
                            $zone->zone_operative = ['bg_color'=>'#dff0d8','border_color'=>'#d6e9c6','text_color'=>'#468847','text'=>'No Asbestos Detected'];
                        }
                    }
                }
            }
        }
        return $zones;

    }

    public function searchZone($q){
        return $this->model->where('reference','like',"%$q%")->orWhere('zone_name','like',"%$q%")->limit(LIMIT_SEARCH)->get();
    }
}
