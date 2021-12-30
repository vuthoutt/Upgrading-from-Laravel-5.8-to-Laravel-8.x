<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\ProjectType;
use Prettus\Repository\Eloquent\BaseRepository;

class ProjectTypeRepository extends BaseRepository
{

    public function model()
    {
        return ProjectType::class;
    }

    public function getProjectTypes(){
        $project_types = $this->model->orderBy('order', 'asc')->get();
        return $project_types;
    }
}
