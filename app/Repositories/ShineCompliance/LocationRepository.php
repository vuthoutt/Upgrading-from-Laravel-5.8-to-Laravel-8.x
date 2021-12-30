<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\Location;
use App\Models\ShineCompliance\LocationInfo;
use App\Models\ShineCompliance\LocationVoid;
use App\Models\ShineCompliance\LocationConstruction;
use App\Models\ShineCompliance\DropdownLocation;
use App\Models\ShineCompliance\DropdownDataLocation;
use Prettus\Repository\Eloquent\BaseRepository;

class LocationRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Location::class;
    }

    public function getLocationInArea($area_id, $request, $limit) {
        if($request->has('q') || $request->has('status') || $request->has('status')){
            //todo add identified_risks | system_type | equipment_types filter
            $builder = $this->model->where('area_id', $area_id)->where('survey_id', 0);
            if(isset($request->q) && !empty($request->q)){
                $condition_raw = "( `description` LIKE '%" . $request->q . "%'
                                    OR `location_reference` LIKE '%" . $request->q . "%'
                                    OR `reference` LIKE '%" . $request->q . "%' )";
                $builder->whereRaw($condition_raw);
            }

            if(isset($request->accessibility)){
                $condition = explode(",", $request->accessibility);
                if(count($condition) > 0){
                    $builder->whereIn('state', $condition);
                }
            }

            if(isset($request->status)){
                $condition = explode(",", $request->status);
                if(count($condition) > 0){
                    $builder->whereIn('decommissioned', $condition);
                }
            }

            return $builder->paginate($limit);
        }
        return Location::where('area_id', $area_id)->where('survey_id', 0)->paginate($limit);
    }

    public function getLocationDropdown($ids) {
        $locationDropdown= DropdownLocation::whereIn('id', $ids)->get();
        return !is_null($locationDropdown) ? $locationDropdown : [];
    }

    public function getDropdownById($id, $parent_id = 0) {
        $dropdowns = DropdownDataLocation::with('allChildrenAccounts')->where('dropdown_location_id', $id)
        ->where('parent_id', $parent_id)->where('decommissioned',0)->get();
        return !is_null($dropdowns) ? $dropdowns : [];
    }

    public function getLocation($id) {
        $location = Location::with('locationInfo', 'locationVoid', 'locationConstruction','decommissionedReason')->where('id', $id)->first();
        return !is_null($location) ? $location : [];
    }

    public function updateOrCreateLocationInfo($id, $data) {
        LocationInfo::updateOrCreate(['location_id' => $id], $data);
        return true;
    }

    public function updateOrCreateLocationVoid($id, $data) {
        LocationVoid::updateOrCreate(['location_id' => $id], $data);
        return true;
    }

    public function updateOrCreateLocationConstruction($id, $data) {
        LocationConstruction::updateOrCreate(['location_id' => $id], $data);
        return true;
    }

    public function decommission($property_id, $type){
        return $this->model->where('property_id',$property_id)->update(['decommissioned' => $type]);
    }
    public function decommissionArea($area_id){
        return $this->model->where('area_id',$area_id)->update(['decommissioned' => LOCATION_DECOMMISSION]);
    }

    public function decommissionLocation($location_id,$reason){
        return $this->model->where('id',$location_id)->update(['decommissioned' => LOCATION_DECOMMISSION,'decommissioned_reason' => $reason]);
    }

    public function recommissionArea($area_id, $reason){
        return $this->model->where('area_id', $area_id)->update(['decommissioned' => LOCATION_UNDECOMMISSION,'decommissioned_reason' => $reason]);
    }

    public function recommissionLocation($location_id, $reason){
        return $this->model->where('id', $location_id)->update(['decommissioned' => LOCATION_UNDECOMMISSION,'decommissioned_reason' => $reason]);
    }

    public function getLocationArea($area_id){
        return $this->model->where('area_id',$area_id)->get();
    }

    public function searchLocation($q, $survey_id = 0){
        // property privilege
        $table_join_privs = \CompliancePrivilege::getPropertyPermission();

        return $this->model->whereRaw("(location_reference LIKE '%$q%' OR reference LIKE '%$q%')")
                    ->where('survey_id','=',$survey_id)
                    ->where('decommissioned','=',0)->orderBy('location_reference','asc')
                    ->limit(LIMIT_SEARCH)->get();
    }

    public function getAssessmentLocation($assess_id, $property_id, $relations){
        return $this->model->with($relations)
            ->where(['assess_id' => $assess_id, 'decommissioned' => LOCATION_UNDECOMMISSION])
            ->orWhere(function($query) use ($property_id) {
                $query->where('property_id', $property_id);
                $query->where('assess_id', COMPLIANCE_ASSESSMENT_REGISTER);
                $query->where('survey_id', COMPLIANCE_ASSESSMENT_REGISTER);
                $query->where('decommissioned', LOCATION_UNDECOMMISSION);
            })
            ->get();
    }

    public function getRegisterLocationByRecordId($record_id)
    {
        $result = $this->model->where('survey_id', 0)
            ->where('assess_id', 0)
            ->where('record_id', $record_id)
            ->first();
        if ($result) {
            return $result->id;
        } else {
            return 0;
        }
    }

    public function getLocationsByAssessmentIdAndAreaId($assess_id, $area_id)
    {
        //location in area, will be in the register or in a assessment
        return $this->model->where('area_id', $area_id)
                            ->where('decommissioned', LOCATION_UNDECOMMISSION)
                            ->get();
    }

    public function getAreaLocation($area_id, $survey_id, $decommissioned = 0) {
        $locations = $this->model->with('allItems')->where(['area_id' => $area_id,'survey_id' => $survey_id, 'decommissioned' => $decommissioned])->get();
        return !is_null($locations) ? $locations : [];
    }
}
