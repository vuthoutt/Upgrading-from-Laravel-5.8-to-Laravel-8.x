<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\PublishedSurvey;
use Prettus\Repository\Eloquent\BaseRepository;

class PublishedSurveyRepository extends BaseRepository
{

    public function model()
    {
        return PublishedSurvey::class;
    }

    public function createPublishedSurvey($data_ps){
        return $this->model->create($data_ps);
    }

    public function getPublishedSurvey($id){
        return $this->model->where('id',$id)->first();
    }
}
