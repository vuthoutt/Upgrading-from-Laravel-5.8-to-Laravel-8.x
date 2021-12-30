<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssessmentInfo;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentInfoRepository extends BaseRepository
{

    public function model()
    {
        return AssessmentInfo::class;
    }
}
