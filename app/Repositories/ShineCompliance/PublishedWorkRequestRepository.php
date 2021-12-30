<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\PublishedWorkRequest;
use Prettus\Repository\Eloquent\BaseRepository;

class PublishedWorkRequestRepository extends BaseRepository
{

    public function model()
    {
        return PublishedWorkRequest::class;
    }

    public function getPublishedWorkRequest($id){
        return $this->model->where('id',$id)->first();
    }
}
