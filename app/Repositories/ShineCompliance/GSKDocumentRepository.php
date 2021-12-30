<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\GSKDocument;
use Prettus\Repository\Eloquent\BaseRepository;

class GSKDocumentRepository extends BaseRepository
{

    public function model()
    {
        return GSKDocument::class;
    }

    public function getAllGSKDocument($property_id,$survey_id){
        return $this->model->all();
    }
}
