<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssemblyPoint;
use Prettus\Repository\Eloquent\BaseRepository;

class AssemblyPointRepository extends BaseRepository
{
    use PullAssessmentToRegister;

    public function model()
    {
        return AssemblyPoint::class;
    }

    public function lockAssemblyPointsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => true]);
    }

    public function unlockAssemblyPointsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => false]);
    }

    public function getAssemblyPointRegister($property_id, $relations){
        return $this->model->with($relations)->where(['property_id' => $property_id, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
    }

    public function listAssemblyPointRegisterByID($assembly_point_ids, $relations){
        if(count($assembly_point_ids)){
            return $this->model->with($relations)->whereIn('id',$assembly_point_ids)
                ->where(['assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
        }
        return [];
    }

    public function getListAssemblyPoint($property_id, $limit, $request){
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
        return $this->model->where(['property_id' => $property_id, 'assess_id' => 0])->paginate($limit);
    }
}
