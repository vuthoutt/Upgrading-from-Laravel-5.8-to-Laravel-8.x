<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\Policy;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class PolicyRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Policy::class;
    }

    public function createPolicy($data){
        return $this->model->create($data);
    }

    public function updatePolicy($id,$data){
        return $this->model->where('id', $id)->update($data);
    }

    public function getPolicyFist($id){
        return $this->model->where('id', $id)->first();
    }


}
