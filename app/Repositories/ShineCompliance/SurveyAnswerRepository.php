<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\SurveyAnswer;
use Prettus\Repository\Eloquent\BaseRepository;

class SurveyAnswerRepository extends BaseRepository
{

    public function model()
    {
        return SurveyAnswer::class;
    }

}
