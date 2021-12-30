<?php

namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\Assessment;
use App\Models\ShineCompliance\IncidentReport;
use App\Models\ShineCompliance\IncidentReportDropdownData;
use App\Models\ShineCompliance\IncidentReportPublished;
use Prettus\Repository\Eloquent\BaseRepository;

class IncidentReportPublishedRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return IncidentReportPublished::class;
    }


}
