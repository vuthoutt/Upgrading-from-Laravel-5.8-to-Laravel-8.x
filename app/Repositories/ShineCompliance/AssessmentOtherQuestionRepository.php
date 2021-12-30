<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssessmentOtherQuestion;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentOtherQuestionRepository extends BaseRepository
{

    public function model()
    {
        return AssessmentOtherQuestion::class;
    }

    public function getOtherQuestionsByAssessId($assess_id)
    {
        return $this->model->with([
            'answerValue' => function($query) use ($assess_id) {
                $query->where('assess_id', $assess_id);
            },
            'answerValue.answer'
        ])->get();
    }
}
