<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssessmentManagementValue;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentManagementValueRepository extends BaseRepository
{

    public function model()
    {
        return AssessmentManagementValue::class;
    }

    public function getAllByAssessId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)->get();
    }
}
