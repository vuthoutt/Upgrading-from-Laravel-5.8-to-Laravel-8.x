<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\ComplianceSystem;
use App\Models\ShineCompliance\ComplianceSystemType;
use App\Models\ShineCompliance\ComplianceSystemClassification;
use Prettus\Repository\Eloquent\BaseRepository;

class ComplianceSystemRepository extends BaseRepository {

    use PullAssessmentToRegister;

    function model()
    {
        return ComplianceSystem::class;
    }

    public function getAllSystemGroupType($property_id, $relations){
        return $this->model->with($relations)->where('property_id', $property_id)->get()->groupBy('type')->all();
    }

    public function getAllSystem($property_id, $limit,$request) {
        if($request->has('q')) {
            $builder = $this->model
                ->where('property_id', $property_id)
                ->where('assess_id', COMPLIANCE_ASSESSMENT_REGISTER);
            if (isset($request->q) && !empty($request->q)) {
                $condition_raw = "( `name` LIKE '%" . $request->q . "%'
                                    OR `reference` LIKE '%" . $request->q . "%' )";
                $builder->whereRaw($condition_raw);
            }
            return $builder->paginate($limit);
        }
        return ComplianceSystem::where('property_id', $property_id)
                                ->where('assess_id', COMPLIANCE_ASSESSMENT_REGISTER)
                                ->paginate($limit);
    }

    public function getAllSystemClassification() {
        return ComplianceSystemClassification::orderBy('description')->get();
    }

    public function getAllSystemType() {
        return ComplianceSystemType::orderBy('description')->get();
    }

    public function searchSystem($q){
        // property privilege
        return $this->model->whereRaw("(name LIKE '%$q%' OR reference LIKE '%$q%')")
            ->where('decommissioned','=',0)
            ->orderBy('reference','asc')->limit(LIMIT_SEARCH)->get();
    }


    public function listSystemProperty($property_id){
        return $this->model->where(['property_id' => $property_id, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
    }

    public function listSystemRegisterByID($system_ids, $relations){
        if(count($system_ids)){
            return $this->model->with($relations)->whereIn('id',$system_ids)
                                ->where(['assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
        }
        return [];
    }

    public function listRegisterSystemProperty($property_id, $relations){
        return $this->model->with($relations)->where([
            'property_id' => $property_id,
            'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER,
            'decommissioned' => COMPLIANCE_ASSESSMENT_UNDECOMMISSIONED,
            ])->get();
    }

    public function searchSystemInAssessment($query_string, $property_id = 0, $assess_id = 0) {

        $data =  ComplianceSystem::where('property_id', $property_id)
                        ->where('assess_id', $assess_id)
                        ->whereRaw("(name LIKE '%$query_string%' OR reference LIKE '%$query_string%')")->limit(10)
                        ->where('decommissioned', 0)->get();
        return $data;
    }

    public function lockSystemsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => true]);
    }

    public function unlockSystemsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => false]);
    }
}
