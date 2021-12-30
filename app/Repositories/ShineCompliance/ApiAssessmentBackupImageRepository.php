<?php

namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\ApiAssessmentBackupImage;
use Prettus\Repository\Eloquent\BaseRepository;

class ApiAssessmentBackupImageRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return ApiAssessmentBackupImage::class;
    }

}
