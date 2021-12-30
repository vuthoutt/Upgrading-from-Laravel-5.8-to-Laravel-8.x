<?php
namespace App\Repositories\ShineCompliance;
use App\Models\Property;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\AssessmentPlanDocument;
use Illuminate\Support\Facades\DB;

class AssessmentPlanDocumentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return AssessmentPlanDocument::class;
    }

    public function getAssessmentPlans($property_id,$assess_id){
        $assessPlans = $this->model->where('property_id',$property_id)->where('assess_id', $assess_id)->latest('plan_date')->get();
        return $assessPlans ?? [];
    }

    public function createPropertyPlan($dataPlan){
        return $this->model->create($dataPlan);
    }

    public function updatePropertyPlan($id,$data){
        return $this->model->where('id', $id)->update($data);
    }

    public function getFirstAssessmentPlanDocument($id)
    {
        return $this->model->where('id', $id)->first();
    }
}
