<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\VehicleParking;
use Prettus\Repository\Eloquent\BaseRepository;

class VehicleParkingRepository extends BaseRepository
{
    use PullAssessmentToRegister;

    public function model()
    {
        return VehicleParking::class;
    }

    public function getRegisterVehicleParkings($property_id, $limit ,$request) {
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
        return VehicleParking::where('property_id', $property_id)->where('assess_id', 0)->paginate($limit);
    }

    public function lockVehicleParkingByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => true]);
    }

    public function unlockVehicleParkingByAssessmentId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->update(['is_locked' => false]);
    }

    public function getVehicleParkingRegister($property_id, $relations){
        return $this->model->with($relations)->where(['property_id' => $property_id, 'assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
    }

    public function listVehicleParkingRegisterByID($vehicle_parking_ids, $relations){
        if(count($vehicle_parking_ids)){
            return $this->model->with($relations)->whereIn('id',$vehicle_parking_ids)
                ->where(['assess_id' => COMPLIANCE_ASSESSMENT_REGISTER])->get();
        }
        return [];
    }
}
