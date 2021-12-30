<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\SurveyInfo;
use Prettus\Repository\Eloquent\BaseRepository;

class SurveyInfoRepository extends BaseRepository
{

    public function model()
    {
        return SurveyInfo::class;
    }

}
