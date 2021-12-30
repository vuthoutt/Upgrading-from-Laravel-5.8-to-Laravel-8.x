<?php


namespace App\Repositories\ShineCompliance;


use App\Models\ShineCompliance\AssessmentUploadManifest;
use Prettus\Repository\Eloquent\BaseRepository;

class AssessmentUploadManifestRepository extends BaseRepository
{

    public function model()
    {
        return AssessmentUploadManifest::class;
    }
}
