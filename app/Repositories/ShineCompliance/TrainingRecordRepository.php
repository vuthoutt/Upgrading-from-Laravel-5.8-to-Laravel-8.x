<?php
namespace App\Repositories\ShineCompliance;
use App\Models\ShineCompliance\TrainingRecord;
use Prettus\Repository\Eloquent\BaseRepository;
use Illuminate\Support\Facades\DB;

class TrainingRecordRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return TrainingRecord::class;
    }

    public function createTrainingRecord($data){
        return $this->model->create($data);
    }

    public function updateTrainingRecord($id,$data){
        return $this->model->where('id', $id)->update($data);
    }

    public function getTrainingRecordFist($id){
        return $this->model->where('id', $id)->first();
    }

    public function getClientPrivilege($type){
        return $this->model->where('client_id', $type)->get();
    }


}
