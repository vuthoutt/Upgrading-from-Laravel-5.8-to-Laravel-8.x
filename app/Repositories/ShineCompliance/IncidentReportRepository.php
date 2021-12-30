<?php

namespace App\Repositories\ShineCompliance;

use App\Models\PropertyInfo;
use App\Models\ShineCompliance\Assessment;
use App\Models\ShineCompliance\IncidentReport;
use App\Models\ShineCompliance\IncidentReportDropdownData;
use Prettus\Repository\Eloquent\BaseRepository;
use App\User;

class IncidentReportRepository extends BaseRepository {

    /**
     * Specify Model class name
     *
     * @return string
     */

    function model()
    {
        return IncidentReport::class;
    }

    public function getIncidentReportDetail($id, $relations) {
        return $this->model->with($relations)->where('id', $id)->first();
    }

    public function getIncidentReportTypes($dropdown_id) {
        return IncidentReportDropdownData::where('dropdown_id', $dropdown_id)
            ->orderBy('order')
            ->orderBy('description')->get();
    }

    public function createIncidentReporting($data)
    {
        return $this->model->create($data);
    }

    public function updateIncidentReporting($id, $data)
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function getDecommissionedIncidentReports()
    {
        $user_id = \Auth::user()->id ?? 0;
        return $this->model->where('decommissioned', INCIDENT_DECOMMISSIONED)
            ->where(function($query) use($user_id) {
                $query->where('confidential', INCIDENT_NOT_CONFIDENTIAL)
                    ->orWhere(function($q1) use($user_id) {
                        $q1->where('confidential', INCIDENT_CONFIDENTIAL)
                            ->where(function($q2) use($user_id) {
                                $q2->where('asbestos_lead', $user_id)
                                    ->orWhere('second_asbestos_lead', $user_id)
                                    ->orWhere('report_recorder', $user_id);
                            });
                    });
            })
            ->get();
    }

    public function getIncidentReportByStatus($status)
    {
        $builder = $this->model->where('decommissioned', INCIDENT_UNDECOMMISSION);
        if (is_array($status)) {
            $builder->whereIn('status', $status);
        } else {
            $builder->where('status', $status);
        }

        $user_id = \Auth::user()->id ?? 0;
        $builder->where(function($query) use($user_id) {
            $query->where('confidential', INCIDENT_NOT_CONFIDENTIAL)
                ->orWhere(function($q1) use($user_id) {
                    $q1->where('confidential', INCIDENT_CONFIDENTIAL)
                        ->where(function($q2) use($user_id) {
                            $q2->where('asbestos_lead', $user_id)
                                ->orWhere('second_asbestos_lead', $user_id)
                                ->orWhere('report_recorder', $user_id);
                        });
                });
        });

        return $builder->get();

    }

    public function getApprovalIncident()
    {
        $user_id = \Auth::user()->id ?? 0;

        return $this->model->where('status', INCIDENT_REPORT_AWAITING_APPROVAL)
            ->where(function($query) use($user_id) {
                $query->where('confidential', INCIDENT_NOT_CONFIDENTIAL)
                    ->orWhere(function($q1) use($user_id) {
                        $q1->where('confidential', INCIDENT_CONFIDENTIAL)
                            ->where(function($q2) use($user_id) {
                                $q2->where('asbestos_lead', $user_id)
                                    ->orWhere('second_asbestos_lead', $user_id)
                                    ->orWhere('report_recorder', $user_id);
                            });
                    });
            })
            ->where('decommissioned', INCIDENT_UNDECOMMISSION)
            ->get();
    }

    public function getUserApprovalIncident()
    {
        $user_id = \Auth::user()->id ?? 0;

        return $this->model->where('status', INCIDENT_REPORT_AWAITING_APPROVAL)
            ->where(function($query) use($user_id) {
                $query->where('asbestos_lead', $user_id)
                    ->orWhere('second_asbestos_lead', $user_id)
                    ->orWhere('report_recorder', $user_id);
            })
            ->where('decommissioned', INCIDENT_UNDECOMMISSION)
            ->get();
    }

    public function getRejectIncident()
    {
        $user_id = \Auth::user()->id ?? 0;

        return $this->model->where('status', INCIDENT_REPORT_REJECT)
            ->where(function($query) use($user_id) {
                $query->where('confidential', INCIDENT_NOT_CONFIDENTIAL)
                    ->orWhere(function($q1) use($user_id) {
                        $q1->where('confidential', INCIDENT_CONFIDENTIAL)
                            ->where(function($q2) use($user_id) {
                                $q2->where('asbestos_lead', $user_id)
                                    ->orWhere('second_asbestos_lead', $user_id)
                                    ->orWhere('report_recorder', $user_id);
                            });
                    });
            })
            ->where('decommissioned', INCIDENT_UNDECOMMISSION)
            ->get();
    }

    public function getUserRejectIncident()
    {
        $user_id = \Auth::user()->id ?? 0;

        return $this->model->where('status', INCIDENT_REPORT_REJECT)
            ->where(function($query) use($user_id) {
                $query->where('asbestos_lead', $user_id)
                    ->orWhere('second_asbestos_lead', $user_id)
                    ->orWhere('report_recorder', $user_id);
            })
            ->where('decommissioned', INCIDENT_UNDECOMMISSION)
            ->get();
    }

    public function approvalIncident($id, $data_approval){
       return $this->model->where('id', $id)->update($data_approval) ;
    }

    public function rejectIncident($id, $data_reject){
        return $this->model->where('id', $id)->update($data_reject) ;
    }

    public function searchIncidentReport($q){
        // property privilege
        return $this->model->whereRaw("(reference LIKE '%$q%')")
            ->where('decommissioned', '=', 0)
            ->orderBy('reference','asc')
            ->limit(LIMIT_SEARCH)->get();
    }


    public function getIncidentReportsProperty($property_id = null, $decommission = INCIDENT_UNDECOMMISSION) {
        $query = $this->model->where(['decommissioned' => $decommission]);
        if(!empty($property_id)) {
            $query = $query->where(['property_id' => $property_id]);
        }
        $query = $query->get();
        return $query;
    }

    public function getIncidentReportsUser($user_id = null, $decommission = INCIDENT_UNDECOMMISSION) {
        return $this->model->where(['decommissioned' => $decommission])
            ->where(function($query) use($user_id) {
                $query->where('asbestos_lead', $user_id)
                    ->orWhere('second_asbestos_lead', $user_id)
                    ->orWhere('report_recorder', $user_id);
            })
            ->get();
    }

    public function getIncidentHousingOfficerContacts($property_id) {
        $contact = PropertyInfo::where('property_id', $property_id)->first()->team ?? [];
        $contactUser = User::with('contact')->whereIn('id', $contact)->where('housing_officer', 1)->get();

        return is_null($contactUser) ? [] : $contactUser;
    }
}
