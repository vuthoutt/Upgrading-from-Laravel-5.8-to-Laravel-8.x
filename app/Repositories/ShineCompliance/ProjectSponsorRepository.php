<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\ProjectSponsor;
use Prettus\Repository\Eloquent\BaseRepository;

class ProjectSponsorRepository extends BaseRepository
{

    public function model()
    {
        return ProjectSponsor::class;
    }

    public function getSponsorList(){
        return $this->model->all();
    }
}
