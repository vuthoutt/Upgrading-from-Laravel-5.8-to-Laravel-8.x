<?php

namespace App\Repositories\ShineCompliance;



use App\Models\ShineCompliance\ApiAssessmentBackupManifest;
use Prettus\Repository\Eloquent\BaseRepository;

class ApiAssessmentBackupManifestRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return ApiAssessmentBackupManifest::class;
    }

    public function getBackupsByAssessId($assess_id)
    {
        return $this->model->where('assess_id', $assess_id)
                            ->where('status', 2)
                            ->get();
    }

}
