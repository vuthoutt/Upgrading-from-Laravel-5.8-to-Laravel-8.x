<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\SurveySetting;
use Prettus\Repository\Eloquent\BaseRepository;

class SurveySettingRepository extends BaseRepository
{

    public function model()
    {
        return SurveySetting::class;
    }

}
