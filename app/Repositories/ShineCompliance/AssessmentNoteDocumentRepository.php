<?php
namespace App\Repositories\ShineCompliance;
use App\Models\Property;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Models\ShineCompliance\AssessmentNoteDocument;
use Illuminate\Support\Facades\DB;

class AssessmentNoteDocumentRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return AssessmentNoteDocument::class;
    }

    public function getAssessmentNotes($property_id,$assess_id){
        $assessorsNotes = $this->model->where('property_id',$property_id)->where('assess_id', $assess_id)->latest('plan_date')->get();
        return $assessorsNotes ?? [];
    }

    public function createPropertyNote($dataPlan){
        return $this->model->create($dataPlan);
    }

    public function updatePropertyNote($id,$data){
        return $this->model->where('id', $id)->update($data);
    }

    public function getFirstAssessmentNoteDocument($id)
    {
        return $this->model->where('id', $id)->first();
    }
}
