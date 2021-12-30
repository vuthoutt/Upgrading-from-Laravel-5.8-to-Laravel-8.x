<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssessmentUploadData;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentUploadDataRepository extends BaseRepository
{

    public function model()
    {
        return AssessmentUploadData::class;
    }
}
