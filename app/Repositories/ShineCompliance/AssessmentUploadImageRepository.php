<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssessmentUploadImage;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentUploadImageRepository extends BaseRepository
{

    public function model()
    {
        return AssessmentUploadImage::class;
    }
}
