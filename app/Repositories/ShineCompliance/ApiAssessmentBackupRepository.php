<?php

namespace App\Repositories\ShineCompliance;



use App\Models\ShineCompliance\ApiAssessmentBackup;
use Prettus\Repository\Eloquent\BaseRepository;

class ApiAssessmentBackupRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return ApiAssessmentBackup::class;
    }

}
