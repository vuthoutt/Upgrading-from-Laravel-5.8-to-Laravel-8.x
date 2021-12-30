<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\SurveyType;
use Prettus\Repository\Eloquent\BaseRepository;

class SurveyTypeRepository extends BaseRepository
{

    public function model()
    {
        return SurveyType::class;
    }

    public function getSurveyTypes(){
        $survey_types = $this->model->orderBy('order', 'asc')->get();
        return $survey_types;
    }
}
