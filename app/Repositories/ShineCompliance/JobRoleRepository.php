<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\JobRole;
use Illuminate\Support\Facades\Auth;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class JobRoleRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return JobRole::class;
    }

    public function getDetail($id, $request = NULL, $relations = [])
    {
        return $this->model->with($relations)->where('id',$id)->first();
    }

    public function getDetailByClient($id, $request = NULL, $client_id, $zone_ids)
    {
        return $this->model->with(['propertyView' => function($join) use($zone_ids){
            $join->whereIn('zone_id',$zone_ids);
        }, 'propertyEdit' => function($join) use($zone_ids){
            $join->whereIn('zone_id',$zone_ids);
        }, 'jobRoleViewValue', 'jobRoleEditValue'])->where('id',$id)->first();
    }

    public function getListJobRoles(){
        return $this->model->all();
    }

    public function updateOrCreateRole($data, $job_id){
        return $this->model->updateOrCreate(['id' => $job_id], $data);
    }

}
