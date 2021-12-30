<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssessmentManagementQuestion;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentManagementQuestionRepository extends BaseRepository
{

    public function model()
    {
        return AssessmentManagementQuestion::class;
    }

    public function getManagementQuestionsByAssessId($assess_id)
    {
        return $this->model->with([
            'answerValue' => function($query) use ($assess_id) {
                $query->where('assess_id', $assess_id);
            },
            'answerValue.answer'
        ])->get();
    }
}
