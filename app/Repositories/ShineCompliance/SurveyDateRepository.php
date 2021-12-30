<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\SurveyDate;
use Prettus\Repository\Eloquent\BaseRepository;

class SurveyDateRepository extends BaseRepository
{

    public function model()
    {
        return SurveyDate::class;
    }

}
