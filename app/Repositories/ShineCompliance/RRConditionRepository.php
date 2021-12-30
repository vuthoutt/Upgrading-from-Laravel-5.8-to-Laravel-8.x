<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\RRCondition;
use Prettus\Repository\Eloquent\BaseRepository;

class RRConditionRepository extends BaseRepository
{

    public function model()
    {
        return RRCondition::class;
    }

    public function getRRConditions(){
        return $this->model->orderBy('order', 'asc')->get();
    }
}
