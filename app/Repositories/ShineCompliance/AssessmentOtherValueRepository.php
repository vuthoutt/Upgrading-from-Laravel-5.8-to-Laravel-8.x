<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssessmentOtherValue;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentOtherValueRepository extends BaseRepository
{

    public function model()
    {
        return AssessmentOtherValue::class;
    }

    public function getAllByAssessId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->get();
    }
}
