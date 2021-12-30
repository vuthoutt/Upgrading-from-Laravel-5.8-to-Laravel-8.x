<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\Hazard;
use App\Models\ShineCompliance\HazardSpecificLocation;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class HazardRepository extends BaseRepository {

    use PullAssessmentToRegister;

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Hazard::class;
    }

    public function getHazardDetail($hazard_id, $relations){
        return $this->model->with($relations)->where('id', $hazard_id)->first();
    }

    public function listHazardRegisterByID($hazard_ids, $relation){
        if(count($hazard_ids)){
            return $this->model->with($relation)->whereIn('id',$hazard_ids)
                ->where(['assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
        }
        return [];
    }

    public function lockHazardsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => true]);
    }

    public function unlockHazardsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => false]);
    }

    // register n/a room/area and normal hz
    public function listNaHazards($property_id, $assess_type, $relations){
        return $this->model->with($relations)->where([
            'property_id' => $property_id,
            'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER,
            'assess_type' => $assess_type,
            'decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
            'is_locked' => COMPLIANCE_ASSESSMENT_UNLOCKED,
            'area_id' => COMPLIANCE_NA_ROOM_AREA,
            'location_id' => COMPLIANCE_NA_ROOM_AREA,
            'is_temp' => COMPLIANCE_NORMAL_HAZARD
        ])->get();
    }

    public function getRegisterHazardRisk($property_id, $type, $min, $max) {

        $risk_hazard = \DB::select("SELECT count(id) count from cp_hazards
                                                where property_id = $property_id
                                                and decommissioned = 0
                                                and assess_id = 0
                                                and is_temp = 0
                                                and is_deleted = 0
                                                and total_risk > $min
                                                and total_risk < $max
                                                and assess_type = $type");

        return $risk_hazard[0]->count ?? false;
    }

    // remove hazard when accessible
    public function removeHazardAccessible($hazard_id)
    {
        return $this->model->where(['id' => $hazard_id])->update(['is_deleted' => 1]);
    }

    // revert hazard when accessible
    public function revertHazardAccessible($hazard_id)
    {
        return $this->model->where(['id' => $hazard_id])->update(['is_deleted' => 0, 'is_temp' => 1,'decommissioned' => 0]);
    }

    // register n/a room/area and normal hz
    public function listRegisterHazardsByType($property_id, $assess_type, $relations){
        return $this->model->with($relations)->where([
            'property_id' => $property_id,
            'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER,
            'assess_type' => $assess_type,
            'decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
            'is_temp' => COMPLIANCE_NORMAL_HAZARD
        ])->get();
    }

    public function getHazardsProperty($property_id){
        return $this->model->where([
            'property_id' => $property_id,
            'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER,
            'assess_type' => ASSESSMENT_FIRE_TYPE,
            'decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
        ])->get();
    }

    public function getHazardsProject($id){
        if (!is_null($id)) {
            $hazard_ids = explode(",",$id);
            $hazards = $this->model->whereIn('id', $hazard_ids)->where('decommissioned', 0)->get();
        } else {
            $hazards = null;
        }
        return $hazards;
    }

    public function searchHazard($query_string, $classification = 0)
    {

        $checkType = ($classification) ? "AND a.classification = " . $classification : "";

        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        $sql = "SELECT h.id as id, h.reference as reference, h.name AS name
                FROM `cp_hazards` as h
                LEFT JOIN cp_assessments as a ON h.assess_id = a.id
                LEFT JOIN tbl_property as p ON p.id = a.property_id
                JOIN $table_join_privs ON permission.prop_id = a.property_id
                WHERE
                    h.`reference` LIKE '%" . $query_string . "%'
                    $checkType
                    AND a.`decommissioned` = 0
                ORDER BY h.`reference` ASC
                LIMIT 0,20";

        $list = DB::select($sql);

        return $list;
    }
}
