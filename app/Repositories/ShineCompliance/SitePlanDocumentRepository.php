<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\SitePlanDocument;
use Prettus\Repository\Eloquent\BaseRepository;

class SitePlanDocumentRepository extends BaseRepository
{

    public function model()
    {
        return SitePlanDocument::class;
    }

    public function getSitePlanbySurvey($property_id,$survey_id){
        return $this->model->where('property_id', $property_id)->where('survey_id', $survey_id)->latest('added')->get();
    }

    public function getSurveyorsNotes($property_id,$survey_id){
        return $this->model->where('property_id', $property_id)->where('survey_id', 0)->where('category',$survey_id)->latest('added')->get();
    }

    public function getSitePlanDocumentbySurvey($property_id,$category) {
        $sitePlanDocument = $this->model->where('property_id', $property_id)->where('survey_id', 0)->where('category', $category)->orderBy('added', 'asc')->get();
        return $sitePlanDocument ?? [];
    }

    public function getSitePlanDocumentBySurveyPDF($relation, $survey_id) {
        $sitePlanDocument = $this->model->with($relation)->where('survey_id',$survey_id)->get();
        return $sitePlanDocument ?? [];
    }
}
