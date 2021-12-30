<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\FireExit;
use Prettus\Repository\Eloquent\BaseRepository;

class FireExitRepository extends BaseRepository
{
    use PullAssessmentToRegister;

    public function model()
    {
        return FireExit::class;
    }

    public function lockExitsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => true]);
    }

    public function unlockExitsByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => false]);
    }

    public function getFireExistRegister($property_id, $relations){
        return $this->model->with($relations)->where(['property_id' => $property_id, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
    }

    public function listFireExistRegisterByID($fire_exist_ids, $relations){
        if(count($fire_exist_ids)){
            return $this->model->with($relations)->whereIn('id',$fire_exist_ids)
                ->where(['assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
        }
        return [];
    }

    public function getListFireExit($property_id, $limit, $request){
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
