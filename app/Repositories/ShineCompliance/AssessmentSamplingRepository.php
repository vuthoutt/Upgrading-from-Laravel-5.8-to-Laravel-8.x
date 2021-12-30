<?php
namespace App\Repositories\ShineCompliance;
use App\Models\Property;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\AssessmentSample;
use Illuminate\Support\Facades\DB;

class AssessmentSamplingRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return AssessmentSample::class;
    }

    public function getAssessmentSample($assess_id){
        $assessorsSample = $this->model->where('assess_id', $assess_id)->get();
        return $assessorsSample ?? [];
    }

    public function createSampling($dataSample){
        return $this->model->create($dataSample);
    }

    public function updateSampling($id,$data){
        return $this->model->where('id', $id)->update($data);
    }

    public function getFirstAssessmentSampling($id)
    {
        return $this->model->where('id', $id)->first();
    }
}
