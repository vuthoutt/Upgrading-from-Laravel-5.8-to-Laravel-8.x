<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\WorkStream;
use Prettus\Repository\Eloquent\BaseRepository;

class WorkstreamRepository extends BaseRepository
{

    public function model()
    {
        return WorkStream::class;
    }

    public function getWorkStreams(){
        return $this->model->where('is_deleted', '!=', 1)->get();
    }
}
