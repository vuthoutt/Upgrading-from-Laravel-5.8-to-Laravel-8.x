<?php

namespace App\Repositories\ShineCompliance;

use App\Models\ShineCompliance\Assessment;
use App\Models\ShineCompliance\IncidentReport;
use App\Models\ShineCompliance\IncidentReportDropdownData;
use App\Models\ShineCompliance\IncidentReportInvolvedPerson;
use Prettus\Repository\Eloquent\BaseRepository;

class IncidentReportInvolvedPersonRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return IncidentReportInvolvedPerson::class;
    }

    public function createIncidentReportInvolvedPerson($data)
    {
        return $this->model->create($data);
    }

    public function updateIncidentReportInvolvedPerson($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function deleteIncidentPersons($incident_report_id)
    {
        return $this->model->where('incident_report_id', $incident_report_id)->delete();
    }
}
